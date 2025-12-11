<?php

namespace App\Tests\Unit\DTO;

use App\DTO\UpdateProductRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class UpdateProductRequestTest extends TestCase
{
    public function testUpdateProductRequestIsValid()
    {
        $dto = new UpdateProductRequest(
            code: "ABC123",
            name: "Produit",
            description: "Description longue",
            price: 5.99,
            quantity: 3,
            category: "Test",
            image: "https://example.com/image.jpg",
            internalReference: "INT-234",
            shellId: 1,
            inventoryStatus: "LOWSTOCK",
            rating: 4
        );

        $validator = Validation::createValidatorBuilder()->enableAttributeMapping()->getValidator();
        $errors = $validator->validate($dto);

        $this->assertCount(0, $errors);
    }

    public function testInvalidImageUrl()
    {
        $dto = new UpdateProductRequest(
            image: "not-a-url"
        );

        $validator = Validation::createValidatorBuilder()->enableAttributeMapping()->getValidator();
        $errors = $validator->validate($dto);

        $this->assertGreaterThan(0, count($errors));
    }
}
