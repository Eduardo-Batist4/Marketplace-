<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

use function App\Helpers\deleteFile;
use function App\Helpers\uploadImage;

class ProductService
{
    public function getAllProducts(array $filter): LengthAwarePaginator
    {
        return Product::applyFilters($filter)->with('discounts')->paginate(15);
    }

    public function createProduct(array $data): Product
    {
        $data['image_path'] = uploadImage($data['image_path'] ?? null, 'products');

        $product = Product::create($data);
        return $product;
    }

    public function getProduct(int $id): Product
    {
        /*
            If there are many feedbacks, a new route must be created to load the feedbacks with pagination
        */
        return Product::where('id', $id)->with(['discounts', 'feedbacks'])->first();
    }

    public function updateProduct(array $data, int $id): Product
    {
        $product = Product::findOrFail($id);
        DB::beginTransaction();
        try {
            if(isset($data['image_path']) && $data['image_path']) {
                $oldImage = $product->image_path;
                $data['image_path'] = uploadImage($data['image_path'], 'products');

                if($oldImage) {
                    deleteFile($oldImage);
                }
            }

            $product->update($data);

            DB::commit();

            return $product->fresh();
        } catch (\Exception $error) {
            DB::rollBack();

            if (isset($data['image_path'])) {
                deleteFile($data['image_path']);
            }
            throw $error;
        }
    }

    public function deleteProduct(int $id): bool
    {
        $product = Product::findOrFail($id);
        return $product->delete();
    }
}
