<?php

namespace Tests\Feature\Comment;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CommentStoreTest extends TestCase
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

    public function test_未ログインユーザーはコメント投稿できない(): void
    {
        $seller = User::factory()->create();
        $itemId = $this->createItem($seller->id);

        $response = $this->post('/comments', [
            'item_id' => $itemId,
            'content' => 'テストコメントです。',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseCount('comments', 0);
    }

    public function test_ログインユーザーはコメント投稿できる(): void
    {
        $seller = User::factory()->create();
        $user = User::factory()->create();
        $itemId = $this->createItem($seller->id);

        $response = $this->actingAs($user)->post('/comments', [
            'item_id' => $itemId,
            'content' => 'テストコメントです。',
        ]);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $itemId,
            'content' => 'テストコメントです。',
        ]);

        $response->assertRedirect();
    }

    public function test_コメントが空だと投稿できない(): void
    {
        $seller = User::factory()->create();
        $user = User::factory()->create();
        $itemId = $this->createItem($seller->id);

        $response = $this->actingAs($user)->from("/items/{$itemId}")->post('/comments', [
            'item_id' => $itemId,
            'content' => '',
        ]);

        $response->assertSessionHasErrors('content');
    }

    public function test_コメントが501文字以上だと投稿できない(): void
    {
        $seller = User::factory()->create();
        $user = User::factory()->create();
        $itemId = $this->createItem($seller->id);

        $response = $this->actingAs($user)->from("/items/{$itemId}")->post('/comments', [
            'item_id' => $itemId,
            'content' => str_repeat('あ', 501),
        ]);

        $response->assertSessionHasErrors('content');
    }
}
