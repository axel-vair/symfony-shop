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
                'description' => "Découvrez les bases du Hatha Yoga à travers un cours spécialement conçu pour les débutants. 
Ce cours vous accompagne pas à pas dans l’apprentissage des postures fondamentales (asanas), de la respiration (pranayama) et de la relaxation. 
Une expérience idéale pour améliorer votre souplesse, apaiser votre esprit et renforcer votre bien-être général, même sans aucune expérience préalable.",
                'subCategory' => 'Hatha Yoga',
            ],
            [
                'name' => 'Huile essentielle de Lavande pure',
                'price' => 12.50,
                'stock' => 50,
                'description' => "Huile essentielle 100% naturelle et biologique de lavande fine, reconnue pour ses propriétés apaisantes et relaxantes. 
Elle s’utilise en diffusion pour créer une atmosphère calme et harmonieuse, en massage diluée dans une huile végétale pour soulager les tensions, 
ou encore dans vos soins de beauté maison. Issue d’une distillation traditionnelle, elle conserve toutes ses vertus aromatiques.",
                'subCategory' => 'Huiles essentielles pures',
            ],
            [
                'name' => 'Livre \"Méditation pour débutants\"',
                'price' => 18,
                'stock' => 15,
                'description' => "Un guide clair et inspirant pour découvrir la méditation pas à pas. 
Illustré d’exercices pratiques, de conseils simples et d’explications accessibles, ce livre vous aide à instaurer quelques minutes de méditation dans votre quotidien. 
Vous apprendrez à calmer votre mental, mieux gérer vos émotions et cultiver un état d’attention sereine pour retrouver l’équilibre intérieur.",
                'subCategory' => 'Méditation',
            ],
            [
                'name' => 'Tisane Relaxante Bio',
                'price' => 8,
                'stock' => 40,
                'description' => "Un mélange de plantes soigneusement sélectionnées pour favoriser la détente et le lâcher-prise. 
Composée de verveine, camomille et tilleul, cette infusion 100% biologique vous aide à retrouver un sommeil paisible et à réduire le stress du quotidien. 
Une tisane douce et parfumée qui transforme chaque pause en un vrai moment de bien-être.",
                'subCategory' => 'Tisanes Relaxantes',
            ],
            [
                'name' => 'Séance Massage Thaï traditionnel',
                'price' => 60,
                'stock' => 10,
                'description' => "Profitez d’une séance de massage thaïlandais traditionnel, transmis depuis des siècles par les maîtres thaï. 
Grâce à des pressions, étirements et mouvements fluides, ce massage libère les tensions, stimule la circulation énergétique et apporte un profond état de relaxation. 
Une expérience unique qui équilibre le corps et l’esprit, idéale pour relâcher le stress et retrouver vitalité.",
                'subCategory' => 'Massage Thaï',
            ],
            [
                'name' => 'Gommage corporel naturel',
                'price' => 25,
                'stock' => 30,
                'description' => "Un gommage doux et efficace à base d’ingrédients naturels pour exfolier votre peau en profondeur. 
À base de sucre de canne bio et d’huiles végétales nourrissantes, il élimine les cellules mortes tout en laissant la peau hydratée et soyeuse. 
Son parfum délicat transforme votre routine en un véritable rituel spa à domicile.",
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
