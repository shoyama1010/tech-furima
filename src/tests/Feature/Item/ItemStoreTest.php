<?php

namespace Tests\Feature\Item;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ItemStoreTest extends TestCase
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

    public function test_未ログインユーザーは商品出品画面にアクセスできない(): void
    {
        $response = $this->get('/items/create');

        $response->assertRedirect('/login');
    }

    public function test_ログインユーザーは商品出品画面を表示できる(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/items/create');

        $response->assertStatus(200);
    }

    public function test_正常な値で商品を出品できる(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $categoryId = $this->createCategory();

        $response = $this->actingAs($user)->post('/items', [
            'name' => 'テスト商品',
            'description' => 'これはテスト商品です。',
            'price' => 3000,
            'condition' => '良好',
            // 'category_id' => $categoryId,
            'categories' => [$categoryId],
            // 'image' => UploadedFile::fake()->image('item.jpg'),
            'image' => UploadedFile::fake()->create('item.jpg', 100, 'image/jpeg'),
        ]);

        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'name' => 'テスト商品',
            'description' => 'これはテスト商品です。',
            'price' => 3000,
            'condition' => '良好',
            // 'category_id' => $categoryId,
            // 'categories' => [$categoryId],
            'category_id' => $categoryId,
        ]);

        $response->assertRedirect();
    }

    public function test_商品名が未入力だと出品できない(): void
    {
        $user = User::factory()->create();
        $categoryId = $this->createCategory();

        $response = $this->actingAs($user)->from('/items/create')->post('/items', [
            'name' => '',
            'description' => 'これはテスト商品です。',
            'price' => 3000,
            'condition' => '良好',
            // 'category_id' => $categoryId,
            'categories' => [$categoryId],
            // 'image' => UploadedFile::fake()->image('item.jpg'),
            'image' => UploadedFile::fake()->create('item.jpg', 100, 'image/jpeg'),
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_価格が未入力だと出品できない(): void
    {
        $user = User::factory()->create();
        $categoryId = $this->createCategory();

        $response = $this->actingAs($user)->from('/items/create')->post('/items', [
            'name' => 'テスト商品',
            'description' => 'これはテスト商品です。',
            'price' => '',
            'condition' => '良好',
            // 'category_id' => $categoryId,
            'categories' => [$categoryId],
            // 'image' => UploadedFile::fake()->image('item.jpg'),
            'image' => UploadedFile::fake()->create('item.jpg', 100, 'image/jpeg'),
        ]);

        $response->assertSessionHasErrors('price');
    }

    public function test_カテゴリ未選択だと出品できない(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from('/items/create')->post('/items', [
            'name' => 'テスト商品',
            'description' => 'これはテスト商品です。',
            'price' => 3000,
            'condition' => '良好',
            'category_id' => '',
            // 'image' => UploadedFile::fake()->image('item.jpg'),
            'image' => UploadedFile::fake()->create('item.jpg', 100, 'image/jpeg'),
        ]);
        // $response->assertSessionHasErrors('category_id');
        $response->assertSessionHasErrors('categories');
    }

    public function test_商品状態未選択だと出品できない(): void
    {
        $user = User::factory()->create();
        $categoryId = $this->createCategory();

        $response = $this->actingAs($user)->from('/items/create')->post('/items', [
            'name' => 'テスト商品',
            'description' => 'これはテスト商品です。',
            'price' => 3000,
            'condition' => '',
            // 'category_id' => $categoryId,
            'categories' => [$categoryId],
            // 'image' => UploadedFile::fake()->image('item.jpg'),
            'image' => UploadedFile::fake()->create('item.jpg', 100, 'image/jpeg'),
        ]);

        $response->assertSessionHasErrors('condition');
    }
}
