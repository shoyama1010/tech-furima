<?php

namespace Tests\Feature\Auth;

use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_会員登録画面を表示できる(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_正常な値で会員登録できる(): void
    {
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
        ]);

        $response->assertRedirect();
    }

    public function test_名前が未入力だと会員登録できない(): void
    {
        $response = $this->from('/register')->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_メールアドレスが未入力だと会員登録できない(): void
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'テスト太郎',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_パスワード確認が一致しないと会員登録できない(): void
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different-password',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_会員登録後にメール認証通知が送信される(): void
    {
        // 実際のメール送信を行わず、通知を記録する
        Notification::fake();

        // 新規会員登録
        $response = $this->post('/register', [
            'name' => 'メール認証テスト',
            'email' => 'verify-test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // 作成されたユーザーを取得
        $user = User::where('email', 'verify-test@example.com')->firstOrFail();

        // 認証メール通知が発行されたことを確認
        Notification::assertSentTo($user, VerifyEmail::class);

        // 登録直後はメール未認証
        $this->assertNull($user->email_verified_at);

        // 認証案内画面へ遷移することを確認
        $response->assertRedirect(route('verification.notice'));

        // 登録後のログイン状態を確認
        $this->assertAuthenticatedAs($user);
    }
}
