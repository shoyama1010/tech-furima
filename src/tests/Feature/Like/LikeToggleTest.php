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
}
