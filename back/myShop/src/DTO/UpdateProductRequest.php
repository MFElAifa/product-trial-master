<?php

namespace App\DTO;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Validator\Constraints as Assert;

#[Map(target: Product::class)]
final readonly class UpdateProductRequest
{
    public function __construct(
        #[Assert\Length(min: 3, max: 255, minMessage: "Le code doit contenir au moins {{ limit }} caractères.")]
        public ?string $code = null,

        #[Assert\Length(min: 3, max: 255)]
        public ?string $name = null,

        #[Assert\Length(min: 10)]
        public ?string $description = null,

        public ?float $price = null,

        public ?int $quantity = null,

        public ?string $category = null,

        #[Assert\Url(message: "L'URL de l'image n'est pas valide.")]
        public ?string $image = null,

        #[Assert\Length(min: 3)]
        public ?string $internalReference = null,

        public ?int $shellId = null,

        public ?string $inventoryStatus = "INSTOCK",

        public ?int $rating = null
    ) {}
}
