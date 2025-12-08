<?php

namespace App\DTO;

final readonly class ItemRequest
{
    public function __construct(public int $productId = 0, public int $quantity = 1) {}
}
