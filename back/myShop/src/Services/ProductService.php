<?php

namespace App\Services;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class ProductService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager

    ) {}

    public function saveProduct(Product $product): ?Product
    {
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }


    public function removeProduct(Product $product): void
    {
        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }
}
