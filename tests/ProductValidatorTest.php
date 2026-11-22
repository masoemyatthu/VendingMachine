<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/helpers/ProductValidator.php';

class ProductValidatorTest extends TestCase

{

    public function testValidProduct()

    {

        $errors = ProductValidator::validate('Pepsi', 6.885, 10);

        $this->assertEmpty($errors);

    }

    public function testEmptyProductName()

    {

        $errors = ProductValidator::validate('', 6.885, 10);

        $this->assertContains('Product name is required.', $errors);

    }

    public function testNegativePrice()

    {

        $errors = ProductValidator::validate('Pepsi', -5, 10);

        $this->assertContains('Price must be greater than zero.', $errors);

    }

    public function testInvalidStock()

    {

        $errors = ProductValidator::validate('Pepsi', 6.885, -1);

        $this->assertContains('Stock must be a non-negative integer.', $errors);

    }

    public function testZeroPrice()

    {

        $errors = ProductValidator::validate('Pepsi', 0, 10);

        $this->assertContains('Price must be greater than zero.', $errors);

    }

}