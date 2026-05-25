<?php

namespace Tests\Feature\Purchase;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PurchaseFlowTest extends TestCase
{
    use RefreshDatabase;

    private function createItem(int $userId, bool $isSold = false): int
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
            'is_sold' => $isSold,
            'status' => $isSold ? 'sold' : 'available',
            'image_url' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_購入画面を表示できる(): void
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        $itemId = $this->createItem($seller->id);

        $response = $this->actingAs($buyer)
            ->get("/purchase/{$itemId}");

        $response->assertStatus(200);
    }

    public function test_自分の商品は購入できない(): void
    {
        $seller = User::factory()->create();

        $itemId = $this->createItem($seller->id);

        $response = $this->actingAs($seller)
            ->post("/purchase/{$itemId}");

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_売却済み商品は購入できない(): void
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        $itemId = $this->createItem($seller->id, true);

        $response = $this->actingAs($buyer)
            ->post("/purchase/{$itemId}");

        $this->assertDatabaseCount('orders', 0);
    }
}
