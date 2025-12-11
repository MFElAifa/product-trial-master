<?php

namespace App\Tests\Unit\DTO;

use App\DTO\CreateUserRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class CreateUserRequestTest extends TestCase
{
    public function testCreateUserRequestIsValid()
    {
        $dto = new CreateUserRequest(
            email: "test@mail.com",
            password: "123456",
            username: "user123",
            firstname: "John",
            lastname: "Doe"
        );

        $validator = Validation::createValidatorBuilder()->enableAttributeMapping()->getValidator();
        $errors = $validator->validate($dto);

        $this->assertCount(0, $errors);
    }

    public function testCreateUserRequestInvalid()
    {
        $dto = new CreateUserRequest(
            email: "",
            password: "",
            username: "",
            firstname: "",
            lastname: ""
        );

        $validator = Validation::createValidatorBuilder()->enableAttributeMapping()->getValidator();
        $errors = $validator->validate($dto);

        $this->assertGreaterThan(0, count($errors));
    }
}
