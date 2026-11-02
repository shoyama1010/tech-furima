<?php

namespace Tests\Feature\Like;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LikeToggleTest extends TestCase
{
    use RefreshDatabase;

    private function createItem(int $userId): int
    {
        $categoryId = DB::table('categories')->insertGetId([
            'name' => 'ファッション',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return DB::table('items')->insertGetId([
            'user_id' => $userId,
            'category_id' => $categoryId,
            'name' => 'テスト商品',
            'description' => 'これはテスト商品です。',
            'price' => 3000,
            'condition' => '良好',
            'is_sold' => false,
            'status' => 'available',
            'image_url' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_未ログインユーザーはいいねできない(): void
    {
        $seller = User::factory()->create();
        $itemId = $this->createItem($seller->id);

        $response = $this->postJson("/items/{$itemId}/like");

        $response->assertStatus(401);

        $this->assertDatabaseCount('likes', 0);
    }

    public function test_ログインユーザーは商品にいいねできる(): void
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        $itemId = $this->createItem($seller->id);

        $response = $this->actingAs($buyer)->postJson("/items/{$itemId}/like");

        $response->assertStatus(200)
            ->assertJson([
                'liked' => true,
                'likes_count' => 1,
            ]);

        $this->assertDatabaseHas('likes', [
            'user_id' => $buyer->id,
            'item_id' => $itemId,
        ]);
    }

    public function test_いいね済みの商品を再度押すといいね解除できる(): void
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        $itemId = $this->createItem($seller->id);

        DB::table('likes')->insert([
            'user_id' => $buyer->id,
            'item_id' => $itemId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($buyer)->postJson("/items/{$itemId}/like");

        $response->assertStatus(200)
            ->assertJson([
                'liked' => false,
                'likes_count' => 0,
            ]);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $buyer->id,
            'item_id' => $itemId,
        ]);
    }

    public function test_メール未認証ユーザーはいいねできない(): void
    {
        // メール未認証ユーザーを作成
        $user = User::factory()->unverified()->create();

        // 出品者と商品を作成
        $seller = User::factory()->create();
        $itemId = $this->createItem($seller->id);

        // 未認証ユーザーとしてログイン
        $this->actingAs($user);

        // いいね操作を実行
        $response = $this->post(
            route('items.toggle-like', $itemId)
        );

        // メール認証案内画面へリダイレクトされる
        $response->assertRedirect(route('verification.notice'));

        // いいねがDBに保存されていないことを確認
        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $itemId,
        ]);

        // ログイン状態は維持される
        $this->assertAuthenticatedAs($user);
    }


    public function test_メール未認証ユーザーは商品詳細からいいねできない(): void
    {
        // メール未認証ユーザーを作成
        $user = User::factory()->unverified()->create();

        // 出品者と商品を作成
        $seller = User::factory()->create();
        $itemId = $this->createItem($seller->id);

        // 未認証ユーザーとしてログイン
        $this->actingAs($user);

        // 商品詳細画面からのいいねAPIを実行
        $response = $this->postJson(
            route('items.like', $itemId)
        );

        // メール認証が必要なため403を返す
        $response->assertStatus(403);

        // いいねが保存されていないことを確認
        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $itemId,
        ]);

        // ログイン状態は維持される
        $this->assertAuthenticatedAs($user);
    }
}
