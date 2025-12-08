<?php

namespace App\Controller;

use App\DTO\CreateProductRequest;
use App\DTO\ProductsQuery;
use App\DTO\UpdateProductRequest;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductRepository;
use App\Services\ProductService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api')]
class ProductController extends AbstractController
{

    public function __construct(
        private readonly productRepository $productRepository,
        private readonly ProductService $productService,
        private readonly ObjectMapperInterface $mapper
    ) {}

    #[Route('/products', name: 'api.products.list', methods: ['GET'])]
    public function products(#[MapQueryString] ProductsQuery $query): JsonResponse
    {
        $products = $this->productRepository->findByQuery($query);
        $total = $this->productRepository->count([]);
        return $this->json([
            'total' => $total,
            'page' => $query->page,
            'itemsPerPage' => $query->itemsPerPage,
            'data' => $products,
        ]);
    }

    #[Route('/products/{id}', name: 'api.products.show', methods: ['GET'])]
    public function show(#[MapEntity(message: 'Product not found')] Product $product): JsonResponse
    {
        return $this->json($product);
    }

    #[Route('/products', name: 'api.products.create', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(#[MapRequestPayload] CreateProductRequest $request): JsonResponse
    {
        $product = $this->mapper->map($request, Product::class);

        $product = $this->productService->saveProduct($product);
        return $this->json($product);
    }

    #[Route('/products/{id}', name: 'api.products.update', methods: ['PATCH', 'PUT'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(
        #[MapEntity(message: 'Product not found')] Product $product,
        #[MapRequestPayload] UpdateProductRequest $request
    ): JsonResponse {
        if ($request->category !== null)     $product->setCategory($request->category);
        if ($request->name !== null)         $product->setName($request->name);
        if ($request->code !== null)         $product->setCode($request->code);
        if ($request->price !== null)        $product->setPrice($request->price);
        if ($request->quantity !== null)     $product->setQuantity($request->quantity);
        if ($request->image !== null)        $product->setImage($request->image);
        if ($request->internalReference !== null) $product->setInternalReference($request->internalReference);
        if ($request->inventoryStatus !== null)   $product->setInventoryStatus($request->inventoryStatus);
        if ($request->rating !== null)       $product->setRating($request->rating);

        $product = $this->productService->saveProduct($product);
        return $this->json($product);
    }

    #[Route('/products/{id}', name: 'api.products.delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function remove(#[MapEntity(message: 'Product not found')] Product $product): JsonResponse
    {
        $this->productService->removeProduct($product);
        return $this->json(null, 204);
    }
}
