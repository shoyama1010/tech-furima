<?php

namespace Tests\Unit\Models;

use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ItemTest extends TestCase
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

    private function createItem(int $userId, bool $isSold = false): Item
    {
        return Item::create([
            'user_id' => $userId,
            'category_id' => $this->createCategory(),
            'name' => 'テスト商品',
            'description' => 'これはテスト商品です。',
            'price' => 3000,
            'condition' => '良好',
            'is_sold' => $isSold,
            'status' => $isSold ? 'sold' : 'available',
            'image_url' => null,
        ]);
    }

    public function test_is_soldがtrueなら購入済みと判定する(): void
    {
        $user = User::factory()->create();
        $item = $this->createItem($user->id, true);

        $this->assertTrue($item->isSold());
    }

    public function test_注文データが存在すれば購入済みと判定する(): void
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        $item = $this->createItem($seller->id, false);

        Order::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'total_price' => 3000,
        ]);

        $this->assertTrue($item->isSold());
    }

    public function test_is_soldがfalseかつ注文データが無ければ未購入と判定する(): void
    {
        $user = User::factory()->create();
        $item = $this->createItem($user->id, false);

        $this->assertFalse($item->isSold());
    }
}
