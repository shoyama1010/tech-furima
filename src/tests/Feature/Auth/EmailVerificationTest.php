<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_メール認証完了後に商品一覧へ遷移する(): void
    {
        // ① メール未認証ユーザーを作成
        $user = User::factory()->unverified()->create();

        // ② ログイン状態にする
        $this->actingAs($user);

        // ③ 署名付きメール認証URLを生成
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );
        // ④ 認証URLへアクセス
        $response = $this->get($verificationUrl);
        // ⑤ 商品一覧へのリダイレクトを確認
        $response->assertRedirect(route('items.index'));
        // ⑥ DB上でメール認証が完了したことを確認
        $this->assertNotNull(
            $user->fresh()->email_verified_at
        );
        // ⑦ ログイン状態が維持されていることを確認
        $this->assertAuthenticatedAs($user);
    }

    public function test_メール未認証ユーザーはマイページにアクセスできない(): void
    {
        // メール未認証ユーザーを作成
        $user = User::factory()->unverified()->create();

        // 未認証ユーザーとしてログイン
        $this->actingAs($user);

        // マイページにアクセス
        $response = $this->get(route('mypage'));

        // メール認証案内画面へリダイレクトされる
        $response->assertRedirect(route('verification.notice'));

        // ログイン状態は維持される
        $this->assertAuthenticatedAs($user);

        // メール認証状態は更新されていない
        $this->assertNull($user->fresh()->email_verified_at);
    }
}
