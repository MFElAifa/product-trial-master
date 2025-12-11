<?php

namespace App\Tests\Unit\DTO;

use App\DTO\CreateProductRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class CreateProductRequestTest extends TestCase
{
    public function testCreateProductRequestIsValid()
    {
        $dto = new CreateProductRequest(
            code: "P0001",
            name: "Produit de test",
            description: "Une longue description de produit",
            price: 9.99,
            quantity: 10,
            category: "Catégorie",
            image: "https://example.com/image.jpg",
            internalReference: "REF-123",
            shellId: 5,
            inventoryStatus: "INSTOCK",
            rating: 4
        );

        $validator = Validation::createValidatorBuilder()->enableAttributeMapping()->getValidator();
        $errors = $validator->validate($dto);

        $this->assertCount(0, $errors);
    }

    public function testCreateProductRequestFailsWithMissingFields()
    {
        $dto = new CreateProductRequest(
            code: "",
            name: "",
            //description: "short",
            price: null,
            quantity: null
        );

        $validator = Validation::createValidatorBuilder()->enableAttributeMapping()->getValidator();
        $errors = $validator->validate($dto);
        //dump(count($errors));
        $this->assertEquals(6, count($errors));
        $this->assertSame('Le code est obligatoire.', $errors[0]->getMessage());
    }

    public function testDefaultInventoryStatus()
    {
        $dto = new CreateProductRequest(
            code: "Code",
            name: "Nom",
            description: "Description longue",
            price: 1.99,
            quantity: 5
        );

        $this->assertEquals("INSTOCK", $dto->inventoryStatus);
    }
}
