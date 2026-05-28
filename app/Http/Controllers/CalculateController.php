<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalculateController extends Controller
{
    public function add() {
        $a = 1;
        $b = 2;
        $sum = $a + $b;

        return "Sum is:" .$sum;
    }

    public function subtract() {
        $a = 1;
        $b = 2;
        $difference = $a - $b;

        return "Difference is:" .$difference;
    }

    public function divide() {
        $a = 1;
        $b = 2;
        $quotient = $a / $b;

        return "Quotient is:" .$quotient;
    }

    public function multiply() {
        $a = 1;
        $b = 2;
        $product = $a * $b;

        return "Product is:" .$product;
    }

    public function modulo() {
        $a = 1;
        $b = 2;
        $mod = $a % $b;

        return "Remainder is:" .$mod;
    }
}
