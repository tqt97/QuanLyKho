<?php

use Filament\Support\RawJs;

if (! function_exists('format_price')) {
    function format_price($price)
    {
        return number_format($price, 0, ',', '.');
    }
}

if (! function_exists('number_format')) {
    function number_format($number, $decimals = 0, $dec_point = '.', $thousands_sep = ',')
    {
        return number_format($number, $decimals, $dec_point, $thousands_sep);
    }
}

if (! function_exists('moneyMask')) {
    function moneyMask()
    {
        return RawJs::make(
            <<<'JS'
            (input) => $money($input, ',', '.', 2)
            JS
        );
    }
}
