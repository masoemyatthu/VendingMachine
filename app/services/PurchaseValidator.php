<?php

require_once __DIR__ . '/../models/ProductInterface.php';

class PurchaseValidator
{
    private $product;

    public function __construct(ProductInterface $product)
    {
        $this->product = $product;
    }

    public function canPurchase($productId, $quantity)
    {
        if ($productId <= 0 || $quantity <= 0) {
            return false;
        }

        $products = $this->product->getProducts();

        foreach ($products as $item) {
            if ($item['id'] == $productId) {
                return $item['stock'] >= $quantity;
            }
        }

        return false;
    }
}