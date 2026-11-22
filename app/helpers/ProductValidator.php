<?php

class ProductValidator

{

    public static function validate($name, $price, $stock)

    {

        $errors = [];

        if (trim($name) === '') {

            $errors[] = "Product name is required.";

        }

        if (!is_numeric($price) || $price <= 0) {

            $errors[] = "Price must be greater than zero.";

        }

        if (

            filter_var($stock, FILTER_VALIDATE_INT) === false

            || $stock < 0

        ) {

            $errors[] = "Stock must be a non-negative integer.";

        }

        return $errors;

    }

}