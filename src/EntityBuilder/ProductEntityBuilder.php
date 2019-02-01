<?php

namespace App\EntityBuilder;

use App\Entity\Product;

class ProductEntityBuilder extends AbstractEntityBuilder
{
    /**
     * @param Product[] $products
     * @return array
     */
    public static function toArrayList(array $products = []): array
    {
        $productsArray = [];
        /** @var Product $product */
        foreach ($products as $product) {
            $productsArray[] = self::toArraySingle($product);
        }

        return self::toListArray($productsArray);
    }

    /**
     * @param Product $product
     * @return array
     */
    public static function toArraySingle(Product $product): array
    {
        return array_merge(self::toDefaultArray($product), [
            'name' => $product->getName(),
            'price' => $product->getPrice()
        ]);
    }
}