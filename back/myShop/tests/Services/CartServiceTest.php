<?php

namespace App\Tests\Unit\Services;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Entity\User;
use App\Services\CartService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class CartServiceTest extends TestCase
{
    /** @var EntityManagerInterface&\PHPUnit\Framework\MockObject\MockObject */
    private EntityManagerInterface $em;
    private CartService $service;

    protected function setUp(): void
    {
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->service = new CartService($this->em);
    }

    public function testGetOrCreateCartWhenCartExists()
    {
        $user = new User();
        $existingCart = new Cart();
        $user->setCart($existingCart);

        $cart = $this->service->getOrCreateCart($user);

        $this->assertSame($existingCart, $cart);
    }

    public function testGetOrCreateCartWhenCartDoesntExist()
    {
        $user = new User();

        $this->em->expects($this->once())->method('persist');

        $cart = $this->service->getOrCreateCart($user);

        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertSame($cart, $user->getCart());
    }

    public function testAddProductCreatesNewItem()
    {
        $cart = new Cart();
        $product = $this->createProductWithPrice10();

        $this->em->expects($this->exactly(2))->method('persist');
        $this->em->expects($this->once())->method('flush');

        $this->service->addProduct($cart, $product, 2);

        $this->assertCount(1, $cart->getCartItems());
        $this->assertEquals(20, $cart->getTotalTtc());
    }

    public function testAddProductIncrementsExistingItem()
    {
        $cart = new Cart();
        $product = $this->createProductWithPrice10();

        $item = new CartItem();
        $item->setProduct($product);
        $item->setQuantity(3);

        $cart->addCartItem($item);

        $this->em->expects($this->exactly(2))->method('persist');
        $this->em->expects($this->once())->method('flush');

        $this->service->addProduct($cart, $product, 2);

        $this->assertEquals(5, $item->getQuantity());
        $this->assertEquals(50, $cart->getTotalTtc());
    }

    public function testRemoveProduct()
    {
        $cart = new Cart();
        $product = $this->createProductWithPrice10();

        $item = new CartItem();
        $item->setProduct($product);
        $item->setQuantity(1);

        $cart->addCartItem($item);

        $this->em->expects($this->once())->method('remove');
        $this->em->expects($this->once())->method('flush');

        $this->service->removeProduct($cart, $product);

        $this->assertCount(0, $cart->getCartItems());
        $this->assertEquals(0, $cart->getTotalTtc());
    }

    /**
     * Return a product with id = 1 and price = 10.
     *
     * @return ?Product
     */
    private function createProductWithPrice10(): ?Product
    {
        $product = new Product();
        // Id
        $reflection = new \ReflectionProperty(Product::class, 'id');
        $reflection->setValue($product, 1);
        // Price
        $reflection = new \ReflectionProperty(Product::class, 'price');
        $reflection->setValue($product, 10);

        return $product;
    }
}
