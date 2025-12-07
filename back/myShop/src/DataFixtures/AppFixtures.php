<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Product;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    public function __construct(protected SluggerInterface $slugger, protected UserPasswordHasherInterface $encoder) {}

    public function load(ObjectManager $manager): void
    {

        // User Admin 
        $admin = new User();
        $hashPassword = $this->encoder->hashPassword($admin, "password");

        $admin->setEmail("admin@admin.com")
            ->setUsername("Admin")
            ->setFirstname("Admin")
            ->setLastname("Admin")
            ->setPassword($hashPassword)
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        // User Admin 
        $user = new User();
        $hashPassword = $this->encoder->hashPassword($user, "password");

        $user->setEmail("user@user.com")
            ->setUsername("User")
            ->setFirstname("User")
            ->setLastname("User")
            ->setPassword($hashPassword)
            ->setRoles(['ROLE_USER']);
        $manager->persist($user);


        $faker = Factory::create();
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker));

        for ($p = 0; $p < mt_rand(15, 50); $p++) {
            $product = new Product;
            $product->setName($faker->productName)
                ->setCode('PR' . mt_rand(2, 100))
                ->setPrice(mt_rand(10, 200))
                ->setQuantity(mt_rand(2, 100))
                ->setCategory($faker->department())
                ->setDescription($faker->paragraph())
                ->setInternalReference('FR' . $product->getCode())
                ->setShellId(mt_rand(100, 10000))
                ->setInventoryStatus(Product::INSTOCK)
                ->setRating(mt_rand(0, 20))
                ->setImage($faker->imageUrl(400, 400, true));
            // ->setCreatedAt(new DateTime())
            // ->setUpdatedAt(new DateTime());


            $manager->persist($product);
        }
        $manager->flush();
    }
}
