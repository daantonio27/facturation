<?php

function format_money($money)
{
    if(!$money) {
        return "0 FCFA";
    }

    $money = number_format($money);

    if(strpos($money, '-') !== false) {
        $formatted = str_replace(","," ",explode('-', $money));
        return "$formatted[1] FCFA";
    }

    return "$money FCFA";
}
