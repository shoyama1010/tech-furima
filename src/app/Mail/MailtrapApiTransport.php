<?php

namespace App\Mail;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Mail\Transport\Transport;
use Swift_Mime_SimpleMessage;
use Swift_TransportException;

class MailtrapApiTransport extends Transport
{
    private $client;
    private $token;

    public function __construct(ClientInterface $client, string $token)
    {
        $this->client = $client;
        $this->token = $token;
    }

    public function send(Swift_Mime_SimpleMessage $message, &$failedRecipients = null)
    {
        $this->beforeSendPerformed($message);

        $addresses = function ($contacts) {
            $result = [];

            foreach ((array) $contacts as $email => $name) {
                $address = ['email' => $email];

                if ($name) {
                    $address['name'] = $name;
                }

                $result[] = $address;
            }

            return $result;
        };

        $from = $addresses($message->getFrom());

        if (empty($from)) {
            throw new Swift_TransportException('Email sender is missing.');
        }

        $payload = [
            'from' => $from[0],
            'to' => $addresses($message->getTo()),
            'subject' => $message->getSubject(),
        ];

        if ($message->getCc()) {
            $payload['cc'] = $addresses($message->getCc());
        }

        if ($message->getBcc()) {
            $payload['bcc'] = $addresses($message->getBcc());
        }

        $parts = [$message];

        while ($parts) {
            $part = array_shift($parts);

            foreach ($part->getChildren() as $child) {
                $parts[] = $child;
            }
            

            $type = $part->getContentType();

            if (in_array($type, ['text/html', 'text/plain'], true)) {
                $key = $type === 'text/html' ? 'html' : 'text';
                $payload[$key] = $part->getBody();
            } elseif (
                $part === $message &&
                $type === 'multipart/alternative' &&
                $part->getBody() !== null
            ) {
                // SwiftMailerでは親メッセージがHTML本文を保持する場合がある
                $payload['html'] = $part->getBody();
            } elseif (empty($part->getChildren()) && $part !== $message) {
                throw new Swift_TransportException(
                    'Unsupported email part or attachment.'
                );
            }

        }

        if (!isset($payload['html']) && !isset($payload['text'])) {
            throw new Swift_TransportException('Email body is missing.');
        }

        try {
            $response = $this->client->request(
                'POST',
                'https://send.api.mailtrap.io/api/send',
                [
                    'headers' => [
                        // 'Api-Token' => $this->token,
                        'Authorization' => 'Bearer ' . $this->token,
                        'Accept' => 'application/json',
                    ],
                    'json' => $payload,
                    'timeout' => 15,
                ]
            );
        } catch (GuzzleException $e) {
            throw new Swift_TransportException(
                'Mailtrap API request failed.',
                0,
                $e
            );
        }

        $result = json_decode((string) $response->getBody(), true);

        if (empty($result['success'])) {
            throw new Swift_TransportException('Mailtrap did not accept the email.');
        }

        $this->sendPerformed($message);

        return $this->numberOfRecipients($message);
    }
}
