<?php

namespace App\DTO;

use App\Entity\User;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Validator\Constraints as Assert;

#[Map(target: User::class)]
final readonly class CreateUserRequest
{
    public function __construct(
        #[Assert\NotBlank(message: "L'adresse mail de l'utilisateur est obligatoire.")]
        #[Assert\Length(min: 3, max: 255, minMessage: "L'adresse mail de l'utilisateur doit contenir au moins {{ limit }} caractères.")]
        public ?string $email = null,

        #[Assert\NotBlank(message: "Le mot de passe est obligatoire.")]
        #[Assert\Length(min: 3, max: 255)]
        public ?string $password = null,

        #[Assert\NotBlank(message: "L'username de l'utilisateur est obligatoire.")]
        #[Assert\Length(min: 3, max: 255)]
        public ?string $username = null,

        #[Assert\NotBlank(message: "Le firstname de l'utilisateur est obligatoire.")]
        #[Assert\Length(min: 3, max: 255)]
        public ?string $firstname = null,

        #[Assert\Length(min: 3)]
        public ?string $lastname = null
    ) {}
}
