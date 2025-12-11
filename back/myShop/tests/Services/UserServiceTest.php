<?php

namespace App\Tests\Unit\Services;

use App\Entity\User;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    public function testSaveUser()
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $service = new UserService($em);

        $user = new User();

        $em->expects($this->once())->method('persist')->with($user);
        $em->expects($this->once())->method('flush');

        $result = $service->saveUser($user);

        $this->assertSame($user, $result);
    }
}
