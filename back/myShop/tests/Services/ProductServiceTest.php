<?php

namespace App\Tests\Unit\Services;

use App\Entity\Product;
use App\Services\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class ProductServiceTest extends TestCase
{
    public function testSaveProduct()
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $service = new ProductService($em);

        $product = new Product();

        $em->expects($this->once())->method('persist')->with($product);
        $em->expects($this->once())->method('flush');

        $result = $service->saveProduct($product);

        $this->assertSame($product, $result);
    }

    public function testRemoveProduct()
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $service = new ProductService($em);

        $product = new Product();

        $em->expects($this->once())->method('remove')->with($product);
        $em->expects($this->once())->method('flush');

        $service->removeProduct($product);

        $this->assertTrue(true);
    }
}
