<?php
/**
 * Group: 5
 * Members: Jacky (23577648), James (25530933), Tsoi Kit (25525116)
 * Date: 2026-11-22
 * Assignment: Final Project Part II - TerminalHK
 *
 * Mock product data for early product-card prototypes; the live catalogue is data/items.xml.
 */


// Simulated database fetch. In a real project, this would call a Model class method.
return [
    'recommend_products' => [
        [
            'title' => '【ROG潮競白2.0】AMD 9800X3D + ROG RTX5080 16GB 全塔式全景電競組合',
            'price' => 36599,
            'original_price' => 39371,
            'img' => 'https://placehold.co/400x300/333/fff?text=ROG+RTX5080+Combo',
            'is_sale' => true
        ],
        // ... (array data omitted for brevity; paste the full dataset from the previous iteration here)
    ],
    'new_products' => [
        [
            'title' => 'Seagate 4TB One Touch with Password USB-C - 白色',
            'price' => 1499,
            'img' => 'https://placehold.co/200x250/e0e0e0/888?text=Seagate+4TB+White'
        ],
        // ...
    ],
    'hot_products' => [
        [
            'title' => '【現貨】INNO3D GeForce RTX™ 5070 TWIN X2 12GB GDDR7',
            'price' => 7599,
            'img' => 'https://placehold.co/250x200/2b2b2b/7cb342?text=RTX+5070'
        ],
        // ...
    ]
];
?>