<?php

namespace App\DTO;

final readonly class UserRequest
{
    public function __construct(public string $email, public string $password) {}
}
