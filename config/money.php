<?php

return [
    /*
     |--------------------------------------------------------------------------
     | Laravel money
     |--------------------------------------------------------------------------
     */
    'locale' => config('app.locale', 'fr_FR'),
    'defaultCurrency' => config('app.currency', 'XOF'),
    'currencies' => [
        'iso' => ['XOF'],
        'bitcoin' => 'all',
        'custom' => [
            // 'MY1' => 2,
            // 'MY2' => 3
        ]
    ]
];
