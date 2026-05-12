<?php

namespace Tests\Integration;

use App\Models\Category;
use App\Models\Discount;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    private ProductService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new ProductService();
    }

    public function test_get_all_products_returns_paginated_results(): void
    {
        Product::factory()->count(20)->create();

        $response = $this->service->getAllProducts([]);

        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $response);

        $this->assertCount(15, $response->items());
        $this->assertEquals(20, $response->total());
    }

    public function test_get_all_products_applies_name_filter_correctly(): void
    {
        Product::factory()->count(5)->create();
        $targetName = Product::factory()->create(['name' => 'flamengo']);

        $response = $this->service->getAllProducts(['name' => 'flamengo']);
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $response);

        $this->assertCount(1, $response->items());
        $this->assertEquals($targetName->name, $response->first()->name);
    }

    public function test_get_all_products_by_category_id(): void
    {
        $category1 = Category::factory()->create(['name' => 'eletronicos']);
        $category2 = Category::factory()->create(['name' => 'roupas']);

        Product::factory()->count(3)->create(['category_id' => $category1->id]);
        Product::factory()->count(2)->create(['category_id' => $category2->id]);

        $response = $this->service->getAllProducts(['category_id' => $category1->id]);

        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $response);

        $this->assertCount(3, $response->items());
        $response->each(function ($product) use ($category1) {
            $this->assertEquals($category1->id, $product->category_id);
            $this->assertEquals('eletronicos', $product->category->name);
            $this->assertNotEquals('roupas', $product->category->name);
        });
    }

    public function test_get_product_with_discount(): void
    {
        $products = Product::factory()->create();
        Discount::factory()->for($products->first())->create([
            'discount_percentage' => 20,
            'description' => 'Desconto de 20%',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);

        $response = $this->service->getAllProducts([]);

        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $response);

        $firstProduct = $response->first();

        $this->assertTrue($firstProduct->discounts->isNotEmpty());
        $this->assertCount(1, $firstProduct->discounts);
        $this->assertEquals(20, $firstProduct->discounts->first()->discount_percentage);
    }

    public function test_get_all_products_with_min_price(): void
    {
        $minPrice = 150;

        Product::factory()->create(['price' => '50']);
        Product::factory()->create(['price' => '100']);
        Product::factory()->create(['price' => '150']);
        Product::factory()->create(['price' => '200']);
        Product::factory()->create(['price' => '300']);

        $response = $this->service->getAllProducts(['min_price' => $minPrice]);

        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $response);

        $this->assertCount(3, $response);

        $response->each(function ($p) use ($minPrice) {
            $this->assertGreaterThanOrEqual($minPrice, $p->price);
        });
    }

    public function test_get_all_products_with_max_price(): void
    {
        $maxPrice = 200;

        Product::factory()->create(['price' => '50']);
        Product::factory()->create(['price' => '100']);
        Product::factory()->create(['price' => '150']);
        Product::factory()->create(['price' => '200']);
        Product::factory()->create(['price' => '300']);

        $response = $this->service->getAllProducts(['max_price' => $maxPrice]);

        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $response);

        $this->assertCount(4, $response);

        $response->each(function ($p) use ($maxPrice) {
            $this->assertLessThanOrEqual($maxPrice, $p->price);
        });
    }

    public function test_get_product_by_id(): void
    {
        $product = Product::factory()->create();

        $response = $this->service->getProduct($product->id);

        $this->assertInstanceOf(\App\Models\Product::class, $response);
        $this->assertEquals($product->id, $response->id);
        $this->assertEquals($product->name, $response->name);
        $this->assertEquals($product->price, $response->price);
    }
}
