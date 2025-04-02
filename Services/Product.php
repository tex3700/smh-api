<?php

namespace Services;

use Services\Entity;
class Product extends Entity
{
    protected function getApiEndpoint(): string
    {
        return 'products';
    }

    protected function getDbTableName(): string
    {
        return 'products';
    }

    protected function mapFields(array $data): array
    {
        return [
            'product_id' => $data['id'],
            'title' => $data['title'],
            'description' => $data['description'] ?? '',
            'price' => $data['price'],
            'discount_percentage' => $data['discountPercentage'] ?? 0,
            'rating' => $data['rating'] ?? 0,
            'stock' => $data['stock'] ?? 0,
            'brand' => $data['brand'] ?? '',
            'category' => $data['category'] ?? '',
            'thumbnail' => $data['thumbnail'] ?? ''
        ];
    }

    protected function getUniqueIdField(): string
    {
        return 'product_id';
    }

    public function fetchAndSaveProducts($productName): void
    {
        $products = $this->fetchByKeyword($productName);
//        foreach ($products as $product) {
//            $this->saveToDb($product);
//        }
        $this->batchSaveToDb($products);
    }
}