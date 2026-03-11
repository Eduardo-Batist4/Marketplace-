<?php

namespace Tests\Integration;

use App\Exceptions\InsufficientQuantityException;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use App\Services\CartItemService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartItemsTest extends TestCase
{
    use RefreshDatabase;

    private CartItemService $service;
    private User $user;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new CartItemService();
        $this->user =  User::factory()->create();
    }

    public function test_get_all_cart_items_returns_correct_total(): void
    {
        $product = Product::factory()->create([
            'name' =>  'T-Shirt',
            'price' => 450.00,
            'stock' => 10,
        ]);

        CartItem::factory()->create([
            'cart_id'    => $this->user->cart->id,
            'product_id' => $product->id,
            'quantity'   => 3,
            'unit_price' => $product->price,
        ]);

        $items = $this->service->getAllCartItems($this->user->id);

        $this->assertEquals(1350, $items['total']);
    }

    public function test_get_all_cart_items_returns_empty_when_cart_has_no_items(): void
    {
        $items = $this->service->getAllCartItems($this->user->id);

        $this->assertEmpty($items['items']);
        $this->assertEquals(0, $items['total']);
    }

    public function test_get_all_cart_items_throws_exception_when_cart_not_found(): void
    {
        $noExistUserId = 999;
        $this->expectException(ModelNotFoundException::class);

        $this->service->getAllCartItems($noExistUserId);
    }

    public function test_create_cart_items_successfully(): void
    {
        $product = Product::factory()->create([
            'name' =>  'Hat',
            'price' => 119.99,
            'stock' => 2,
        ]);

        $cartItem = $this->service->createCartItem([
            'product_id' => $product->id,
            'quantity' => 1
        ], $this->user->id);

        $this->assertInstanceOf(CartItem::class, $cartItem);
        $this->assertEquals($product->id, $cartItem->product_id);
        $this->assertEquals(119.99, $cartItem->unit_price);
        $this->assertEquals(1, $cartItem->quantity);

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => 119.99,
        ]);
    }

    public function test_update_cart_item_successfully(): void
    {
        $product = Product::factory()->create([
            'price' => 14.99,
            'stock' => 5,
        ]);

        $cartItem = CartItem::factory()->create([
            'cart_id'    => $this->user->cart->id,
            'product_id' => $product->id,
            'quantity'   => 2,
            'unit_price' => $product->price,
        ]);

        $cartItemUpdated = $this->service->updateCartItem([
           'quantity' => 3,
        ], $this->user->id, $cartItem->id);

        $this->assertEquals(2, $cartItem->quantity);
        $this->assertEquals(3, $cartItemUpdated->quantity);

        $this->assertEquals($product->id, $cartItemUpdated->product_id);
        $this->assertEquals(14.99, $cartItemUpdated->unit_price);

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 3,
        ]);
    }

    public function test_create_cart_item_throws_exception_when_product_not_found(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->service->createCartItem([
            'product_id' => 23,
            'quantity' => 5,
        ], $this->user->id);
    }

    public function test_throws_insufficient_quantity_exception_when_stock_is_exceeded(): void
    {
        $product = Product::factory()->create([
            'name' =>  'Shoes',
            'price' => 814.00,
            'stock' => 3,
        ]);

        $this->expectException(InsufficientQuantityException::class);
        $this->expectExceptionMessage("Insufficient stock for product ID {$product->id}. " .
            "Requested: 5, Available: 3");

        $this->service->createCartItem([
            'product_id' => $product->id,
            'quantity' => 5,
        ], $this->user->id);
    }

}
