<?php

namespace App\Tests\Unit\DTO;

use App\DTO\ProductsQuery;
use PHPUnit\Framework\TestCase;

class ProductsQueryTest extends TestCase
{
    public function testDefaultValues()
    {
        $dto = new ProductsQuery();
        $this->assertEquals(1, $dto->page);
        $this->assertEquals(10, $dto->itemsPerPage);
    }

    public function testCustomValues()
    {
        $dto = new ProductsQuery(3, 50);
        $this->assertEquals(3, $dto->page);
        $this->assertEquals(50, $dto->itemsPerPage);
    }
}
