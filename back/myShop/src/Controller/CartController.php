<?php

namespace App\Controller;

use App\DTO\ItemRequest;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Services\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/cart')]
class CartController extends AbstractController
{
    public function __construct(
        private CartService $cartService,
        private readonly ProductRepository $productRepository
    ) {}

    #[Route('', name: 'api.cart.get', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getCart(): JsonResponse
    {
        $user = $this->getUser();
        $cart = $this->cartService->getOrCreateCart($user);

        $items = [];
        foreach ($cart->getCartItems() as $item) {
            $items[] = [
                'productId' => $item->getProduct()->getId(),
                'productCode' => $item->getProduct()->getCode(),
                'productName' => $item->getProduct()->getName(),
                'quantity' => $item->getQuantity(),
                'price' => $item->getProduct()->getPrice(),
                'total' => $item->getQuantity() * $item->getProduct()->getPrice(),
            ];
        }

        return $this->json([
            'items' => $items,
            'totalTTC' => $cart->getTotalTtc(),
        ]);
    }

    #[Route('/add/product', name: 'api.cart.add', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function addProduct(#[MapRequestPayload] ItemRequest $itemRequest): JsonResponse
    {

        $product = $this->productRepository->find($itemRequest->productId);
        if (!$product) {
            return $this->json(['error' => 'Produit introuvable'], 404);
        }

        $user = $this->getUser();
        $cart = $this->cartService->getOrCreateCart($user);

        $this->cartService->addProduct($cart, $product, $itemRequest->quantity);

        return $this->json(['message' => 'Produit ajouté au panier']);
    }

    #[Route('/remove/product/{id}', methods: ['DELETE'])]
    #[IsGranted('ROLE_USER')]
    public function removeProduct(Product $product): JsonResponse
    {
        $user = $this->getUser();
        $cart = $this->cartService->getOrCreateCart($user);

        $this->cartService->removeProduct($cart, $product);

        return $this->json(['message' => 'Produit retiré']);
    }
}
