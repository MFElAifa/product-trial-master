<?php

namespace App\DTO;

use App\Entity\Product;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Validator\Constraints as Assert;

#[Map(target: Product::class)]
final readonly class CreateProductRequest
{
    public function __construct(
        #[Assert\NotBlank(message: "Le code est obligatoire.")]
        #[Assert\Length(min: 3, max: 255, minMessage: "Le code doit contenir au moins {{ limit }} caractères.")]
        public ?string $code = null,

        #[Assert\NotBlank(message: "Le nom est obligatoire.")]
        #[Assert\Length(min: 3, max: 255)]
        public ?string $name = null,

        #[Assert\Length(min: 10)]
        public ?string $description = null,

        #[Assert\NotNull(message: "Le prix est obligatoire.")]
        #[Assert\PositiveOrZero(message: "Le prix doit être positif ou nul.")]
        public ?float $price = null,

        #[Assert\NotNull(message: "La quantité est obligatoire.")]
        #[Assert\PositiveOrZero(message: "La quantité doit être positive ou nulle.")]
        public ?int $quantity = null,

        public ?string $category = null,

        #[Assert\Url(requireTld: true, message: "L'URL de l'image n'est pas valide.")]
        public ?string $image = null,

        #[Assert\Length(min: 3)]
        public ?string $internalReference = null,

        public ?int $shellId = null,

        public ?string $inventoryStatus = "INSTOCK",

        public ?int $rating = null
    ) {}
}
