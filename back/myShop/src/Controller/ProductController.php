<?php 

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class ProductController
{
    #[Route('/hello', methods: ['GET'])]
    public function hello(): JsonResponse
    {
        return new JsonResponse([
            'message' => 'API Symfony 7.4 (PHP 8.2)'
        ]);
    }
}