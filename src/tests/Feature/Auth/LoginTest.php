<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_ログイン画面を表示できる(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_正常な認証情報でログインできる(): void
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'login@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect();
    }

    public function test_メールアドレスが未入力だとログインできない(): void
    {
        $response = $this->from('/login')->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_パスワードが未入力だとログインできない(): void
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'login@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }

    public function test_誤ったパスワードではログインできない(): void
    {
        User::factory()->create([
            'email' => 'login@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'login@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_メール未認証ユーザーは認証案内画面へ遷移する(): void
    {
        $user = User::factory()->unverified()->create([
            'email' => 'unverified@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'unverified@example.com',
            'password' => 'password123',
        ]);

        // メール認証案内画面へリダイレクトされる
        $response->assertRedirect(route('verification.notice'));

        // ログイン状態が維持されている
        $this->assertAuthenticatedAs($user);

        // 認証案内画面へアクセスできる
        $this->get(route('verification.notice'))
            ->assertStatus(200);
    }
}
