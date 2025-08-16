<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\SubCategory;
use App\Entity\Product;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categoriesWithSubs = [
            'Yoga' => [
                'Hatha Yoga',
                'Vinyasa Yoga',
                'Fly Yoga',
                'Méditation',
            ],
            'Huiles Essentielles & Aromathérapie' => [
                'Huiles essentielles pures',
                'Mélanges',
                'Diffuseurs',
            ],
            'Livres & Guides Bien-être' => [
                'Nutrition',
                'Méditation',
                'Développement personnel',
            ],
            'Cosmétiques Bio' => [
                'Soins du visage',
                'Soins du corps',
                'Huiles essentielles',
            ],
            'Tisanes & Infusions' => [
                'Tisanes Relaxantes',
                'Tisanes Detox',
                'Infusions Bio',
            ],
            'Massages' => [
                'Massage Thaï',
                'Massage Balinais',
                'Massage Californien',
                'Massage aux pierres chaudes',
            ],
            'Soins du Corps' => [
                'Gommages',
                'Masques',
                'Accessoires spa',
            ],
        ];

        // Tableaux pour stocker les entités afin d’y rattacher les produits ensuite
        $categoryEntities = [];
        $subCategoryEntities = [];

        // Création des catégories et sous-catégories
        foreach ($categoriesWithSubs as $categoryName => $subCategories) {
            $category = new Category();

            $category->setName($categoryName);
            $manager->persist($category);

            $categoryEntities[$categoryName] = $category;

            foreach ($subCategories as $subCatName) {
                $subCategory = new SubCategory();
                $subCategory->setName($subCatName);
                $subCategory->setCategory($category);
                $manager->persist($subCategory);

                $subCategoryEntities[$subCatName] = $subCategory;
            }
        }

        $productsData = [
            [
                'name' => 'Cours de Hatha Yoga débutant',
                'price' => 30,
                'stock' => 20,
                'description' => 'Cours d’initiation au Hatha Yoga pour débutants.',
                'subCategory' => 'Hatha Yoga',
            ],
            [
                'name' => 'Huile essentielle de Lavande pure',
                'price' => 12.50,
                'stock' => 50,
                'description' => 'Huile essentielle 100% naturelle de lavande fine.',
                'subCategory' => 'Huiles essentielles pures',
            ],
            [
                'name' => 'Livre "Méditation pour débutants"',
                'price' => 18,
                'stock' => 15,
                'description' => 'Guide pratique pour commencer la méditation.',
                'subCategory' => 'Méditation',
            ],
            [
                'name' => 'Tisane Relaxante Bio',
                'price' => 8,
                'stock' => 40,
                'description' => 'Mélange de plantes relaxantes, certifié bio.',
                'subCategory' => 'Tisanes Relaxantes',
            ],
            [
                'name' => 'Séance Massage Thaï traditionnel',
                'price' => 60,
                'stock' => 10,
                'description' => 'Massage thaïlandais traditionnel pour relaxation et bien-être.',
                'subCategory' => 'Massage Thaï',
            ],
            [
                'name' => 'Gommage corporel naturel',
                'price' => 25,
                'stock' => 30,
                'description' => 'Gommage à base d’ingrédients naturels pour exfolier la peau.',
                'subCategory' => 'Gommages',
            ],
            [
                'name' => 'Gommage coEAEAZErporel naturel',
                'price' => 25,
                'stock' => 30,
                'description' => 'Gommage à base d’ingrédients naturels pour exfolier la peau.',
                'subCategory' => 'Gommages',
            ],
            [
                'name' => 'GommRAZEREAage corporel naturel',
                'price' => 25,
                'stock' => 30,
                'description' => 'Gommage à base d’ingrédients naturels pour exfolier la peau.',
                'subCategory' => 'Gommages',
            ],
        ];

        foreach ($productsData as $productData) {
            $product = new Product();
            $product->setName($productData['name']);
            $product->setPrice($productData['price']);
            $product->setStock($productData['stock']);
            $product->setDescription($productData['description']);
            $product->setCreatedDate(new DateTime());
            $product->setImage(null);

            // Rattacher à la sous-catégorie
            $subCategory = $subCategoryEntities[$productData['subCategory']] ?? null;
            if ($subCategory !== null) {
                $category = $subCategory->getCategory();

                $product->setCategory($category);
                $product->setSubCategory($subCategory);
            }

            $manager->persist($product);
        }

        $manager->flush();
    }
}
