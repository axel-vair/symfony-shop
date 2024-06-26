<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Create a Faker instance
        $faker = Factory::create();

        // Create and persist Categories
        $categories = [];
        for ($i = 0; $i < 10; $i++) {
            $category = new Category();
            $category->setName($faker->word);

            $categories[] = $category;
            $manager->persist($category);
        }

        // Create and persist Users
        $users = [];
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setPassword($faker->password);

            $users[] = $user;
            $manager->persist($user);

        }

        // Create and persist Products
        $products = [];
        for ($i = 0; $i < 10; $i++) {
            $product = new Product();
            $product->setName($faker->name);
            $product->setDescription($faker->paragraph);
            $product->setPrice($faker->randomFloat(2, 1, 100));
            $product->setStock($faker->numberBetween(0, 100));
            $product->setCreatedDate($faker->dateTime);
            // Associate random Category to Product
            $randomCategory = $categories[array_rand($categories)];
            $product->addCategory($randomCategory);

            $products[] = $product;
            $manager->persist($product);
        }

        // Create and persist Comments
        for ($i = 0; $i < 10; $i++) {
            $comment = new Comment();
            $comment->setDescription($faker->sentence);
            $comment->setRating($faker->randomFloat(1, 0, 5));

            // Associate random User and Product to Comment
            $randomUser = $users[array_rand($users)];
            $randomProduct = $products[array_rand($products)];

            $comment->addUserId($randomUser);
            $comment->addProductId($randomProduct);

            $manager->persist($comment);
        }

        // Flush all changes to the database
        $manager->flush();
    }
}