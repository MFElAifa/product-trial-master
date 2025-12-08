<?php

namespace App\Services;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class CartService
{
    public function __construct(private readonly EntityManagerInterface $em) {}

    public function getOrCreateCart(User $user): Cart
    {
        $cart = $user->getCart();
        if (!$cart) {
            $cart = new Cart();
            $cart->setUser($user);
            $this->em->persist($cart);
        }
        return $cart;
    }

    public function addProduct(Cart $cart, Product $product, int $qty): Cart
    {
        foreach ($cart->getCartItems() as $item) {
            if ($item->getProduct()->getId() === $product->getId()) {
                $item->setQuantity($item->getQuantity() + $qty);
                $this->em->persist($item);

                $this->saveCart($cart);
                return $cart;
            }
        }
        // new cartItem
        $item = new CartItem();
        $item->setProduct($product);
        $item->setQuantity($qty);
        $cart->addCartItem($item);
        $this->em->persist($item);

        // update cart totalTTC
        $this->saveCart($cart);

        return $cart;
    }

    public function removeProduct(Cart $cart, Product $product): void
    {
        foreach ($cart->getCartItems() as $item) {
            if ($item->getProduct()->getId() === $product->getId()) {
                $cart->getCartItems()->removeElement($item);
                $this->em->remove($item);
                $this->saveCart($cart);
            }
        }
    }


    private function saveCart(Cart $cart): void
    {
        $total = 0.0;
        foreach ($cart->getCartItems() as $item) {
            // update cart totalTTC
            $total += $item->getQuantity() * $item->getProduct()->getPrice();
        }

        $cart->setTotalTtc($total);
        $this->em->persist($cart);

        $this->em->flush();
    }
}
