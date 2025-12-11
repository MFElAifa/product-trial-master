<?php

namespace App\Tests\Unit\DTO;

use App\DTO\ItemRequest;
use PHPUnit\Framework\TestCase;

class ItemRequestTest extends TestCase
{
    public function testItemRequestDefaultValues()
    {
        $dto = new ItemRequest();
        $this->assertEquals(0, $dto->productId);
        $this->assertEquals(1, $dto->quantity);
    }

    public function testItemRequestCustomValues()
    {
        $dto = new ItemRequest(10, 5);
        $this->assertEquals(10, $dto->productId);
        $this->assertEquals(5, $dto->quantity);
    }
}
