<?php

namespace Tests\Feature\Mypage;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MypageDisplayTest extends TestCase
{
    use RefreshDatabase;

    private function createCategory(): int
    {
        return DB::table('categories')->insertGetId([
            'name' => 'ファッション',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createItem(int $userId, string $name): int
    {
        return DB::table('items')->insertGetId([
            'user_id' => $userId,
            'category_id' => $this->createCategory(),
            'name' => $name,
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

    public function test_ログインユーザーはマイページを表示できる(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/mypage');

        $response->assertStatus(200);
    }

    public function test_マイページに出品商品が表示される(): void
    {
        $user = User::factory()->create();

        $this->createItem($user->id, '出品した商品');

        $response = $this->actingAs($user)->get('/mypage?tab=sell');

        $response->assertStatus(200);
        $response->assertSee('出品した商品');
    }

    public function test_マイページに購入商品が表示される(): void
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        $itemId = $this->createItem($seller->id, '購入した商品');

        DB::table('orders')->insert([
            'user_id' => $buyer->id,
            'item_id' => $itemId,
            'total_price' => 3000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // DB::table('orders')->insert([
        //     'user_id' => $buyer->id,
        //     'item_id' => $itemId,
        //     'payment_method' => 'card',
        //     'postal_code' => '123-4567',
        //     'address' => '東京都渋谷区',
        //     'building' => 'テストビル101',
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        $response = $this->actingAs($buyer)->get('/mypage?tab=buy');

        $response->assertStatus(200);
        $response->assertSee('購入した商品');
    }
}
