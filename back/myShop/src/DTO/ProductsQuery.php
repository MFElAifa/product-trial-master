<?php

namespace App\DTO;

final readonly class ProductsQuery
{
    public function __construct(public int $page = 1, public int $itemsPerPage = 10) {}
}
