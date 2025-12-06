<?php

namespace App\Controller;

use App\DTO\CreateProductRequest;
use App\DTO\ProductsQuery;
use App\DTO\UpdateProductRequest;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductRepository;
use App\Services\ProductService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

#[Route('/api')]
class ProductController extends AbstractController
{

    public function __construct(
        private readonly productRepository $productRepository,
        private readonly ProductService $productService,
        private readonly CategoryRepository $categoryRepository,
        private readonly ObjectMapperInterface $mapper
    ) {}

    #[Route('/products', name: 'api.products.list', methods: ['GET'])]
    public function products(#[MapQueryString] ProductsQuery $query): JsonResponse
    {
        $products = $this->productRepository->findByQuery($query);
        return $this->json($products);
    }

    #[Route('/products/{id}', name: 'api.products.show', methods: ['GET'])]
    public function show(#[MapEntity(message: 'Product not found')] Product $product): JsonResponse
    {
        return $this->json($product);
    }

    #[Route('/products', name: 'api.products.create', methods: ['POST'])]
    public function create(#[MapRequestPayload] CreateProductRequest $request): JsonResponse
    {

        $category = $this->categoryRepository->find($request->categoryId);
        if (!$category) {
            return $this->json([
                'status' => 'error',
                'errors' => ['categoryId' => 'Catégorie introuvable.']
            ], Response::HTTP_NOT_FOUND);
        }
        $product = $this->mapper->map($request, Product::class);
        $product->setCategory($category);

        $product = $this->productService->saveProduct($product);
        return $this->json($product);
    }

    #[Route('/products/{id}', name: 'api.products.update', methods: ['PATCH', 'PUT'])]
    public function edit(
        #[MapEntity(message: 'Product not found')] Product $product,
        #[MapRequestPayload] UpdateProductRequest $request
    ): JsonResponse {
        if ($request->categoryId !== null) {
            $category = $this->categoryRepository->find($request->categoryId);
            if (!$category) {
                return $this->json([
                    'status' => 'error',
                    'errors' => ['categoryId' => 'Catégorie introuvable.']
                ], Response::HTTP_NOT_FOUND);
            }
            $product->setCategory($category);
        }

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
    public function remove(#[MapEntity(message: 'Product not found')] Product $product): JsonResponse
    {
        $this->productService->removeProduct($product);
        return $this->json(null, 204);
    }
}
