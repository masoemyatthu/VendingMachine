
<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/models/ProductInterface.php';
require_once __DIR__ . '/../app/services/PurchaseValidator.php';

class PurchaseValidatorTest extends TestCase
{
    public function testCanPurchaseWhenStockIsAvailable()
    {
        $product = $this->createMock(ProductInterface::class);

        $product->expects($this->once())
            ->method('getProducts')
            ->willReturn([
                ['id' => 1, 'stock' => 10]
            ]);

        $validator = new PurchaseValidator($product);

        $this->assertTrue($validator->canPurchase(1, 2));
    }

    public function testCannotPurchaseWhenStockIsInsufficient()
    {
        $product = $this->createStub(ProductInterface::class);

        $product->method('getProducts')
            ->willReturn([
                ['id' => 1, 'stock' => 2]
            ]);

        $validator = new PurchaseValidator($product);

        $this->assertFalse($validator->canPurchase(1, 5));
    }

    public function testRejectsInvalidQuantity()
    {
        $product = $this->createMock(ProductInterface::class);

        $product->expects($this->never())
            ->method('getProducts');

        $validator = new PurchaseValidator($product);

        $this->assertFalse($validator->canPurchase(1, 0));
    }
}