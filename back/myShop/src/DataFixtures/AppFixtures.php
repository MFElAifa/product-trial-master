<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Enum\ProductStatus;
//use DateTime;

class AppFixtures extends Fixture
{
    public function __construct(protected SluggerInterface $slugger)
    {   
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker));

        for($c=0; $c < 3; $c++){
            $category = new Category;
            $category->setName($faker->department)
                ->setSlug(strtolower($this->slugger->slug($category->getName())));
                
            $manager->persist($category);
        
            for($p=0; $p < mt_rand(15,20); $p++){
                $product = new Product;
                $product->setName($faker->productName)
                    ->setCode($this->slugger->slug($category->getName()))
                    ->setPrice(mt_rand(10,200))
                    ->setQuantity(mt_rand(2,100))
                    ->setCategory($category)
                    ->setDescription($faker->paragraph())
                    ->setInternalReference('FR_'.$product->getCode())
                    ->setShellId(mt_rand(100,10000))
                    ->setInventoryStatus(ProductStatus::INSTOCK)
                    ->setRating(mt_rand(0,20))
                    ->setImage($faker->imageUrl(400,400, true));
                    // ->setCreatedAt(new DateTime())
                    // ->setUpdatedAt(new DateTime());

                
                $manager->persist($product);
            }
        }
        $manager->flush();
    }
}
