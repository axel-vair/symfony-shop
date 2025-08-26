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
                'Massage Balinais',
                'Massage Californien',
                'Massage aux pierres chaudes',
            ],
            'Soins du Corps' => [
                'Gommages',
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
            // --- YOGA ---
            ['name' => 'Séance de Hatha Yoga matin', 'price' => 28, 'stock' => 15, 'description' => 'Séance douce pour bien commencer la journée avec des postures de base et respiration contrôlée.', 'subCategory' => 'Hatha Yoga',  'image' => '/hatha.jpg' ],
            ['name' => 'Atelier Hatha Yoga & Relaxation', 'price' => 35, 'stock' => 10, 'description' => 'Un atelier complet combinant postures physiques et techniques de relaxation profonde.', 'subCategory' => 'Hatha Yoga',  'image' => '/atelier-hatha-relaxation.jpg' ],

            ['name' => 'Initiation Fly Yoga', 'price' => 45, 'stock' => 12, 'description' => 'Première approche du yoga aérien avec hamac sécurisé.', 'subCategory' => 'Fly Yoga',  'image' => '/fly-yoga-initiation.jpg' ],
            ['name' => 'Fly Yoga tonique', 'price' => 50, 'stock' => 10, 'description' => 'Enchaînement dynamique de figures en suspension.', 'subCategory' => 'Fly Yoga',  'image' => '/fly-yoga-tonique.jpg' ],

            ['name' => 'Introduction à la méditation', 'price' => 18, 'stock' => 30, 'description' => 'Apprenez à méditer facilement avec des exercices guidés.', 'subCategory' => 'Méditation',  'image' => '/meditation.jpg' ],
            ['name' => 'Méditation anti-stress guidée', 'price' => 22, 'stock' => 25, 'description' => 'Idéal pour se détendre en fin de journée.', 'subCategory' => 'Méditation',  'image' => '/meditation-guidee-antistress.jpg' ],

            // --- HUILES ESSENTIELLES & AROMATHÉRAPIE ---
            ['name' => 'Huile essentielle de Menthe Poivrée', 'price' => 11.90, 'stock' => 30, 'description' => 'Pour les maux de tête, digestion difficile et fraîcheur respiratoire.', 'subCategory' => 'Huiles essentielles pures',  'image' => '/huile-menthe.jpg'],
            ['name' => 'Huile essentielle de Tea Tree', 'price' => 10.50, 'stock' => 40, 'description' => 'Antibactérienne puissante pour les soins de peau.', 'subCategory' => 'Huiles essentielles pures',  'image' => '/huile-the.jpg'],
            ['name' => 'Huile essentielle de citron bio', 'price' => 9.90, 'stock' => 50, 'description' => 'Tonifiante, purifiante, parfaite pour la diffusion.', 'subCategory' => 'Huiles essentielles pures',  'image' => '/huile-citron.jpg'],

            ['name' => 'Mélange Sommeil Profond', 'price' => 16.00, 'stock' => 30, 'description' => 'Synergie lavande, mandarine, camomille pour faciliter l’endormissement.', 'subCategory' => 'Mélanges', 'image' => '/melange-sommeil.jpg'],
            ['name' => 'Mélange Respiration Libre', 'price' => 15.50, 'stock' => 25, 'description' => 'Contre les nez bouchés et allergies saisonnières.', 'subCategory' => 'Mélanges', 'image' => '/melange-respiration.jpg'],
            ['name' => 'Roll-on anti-stress', 'price' => 13.00, 'stock' => 40, 'description' => 'Application simple au poignet en cas d’anxiété.', 'subCategory' => 'Mélanges', 'image' => '/roll-on-antistress.jpg'],

            ['name' => 'Diffuseur ultrasonique bois clair', 'price' => 39.90, 'stock' => 20, 'description' => 'Silencieux, avec veilleuse LED intégrée.', 'subCategory' => 'Diffuseurs', 'image' => '/diffuseur-bois-clair.jpg'],
            ['name' => 'Diffuseur nomade USB', 'price' => 25.00, 'stock' => 30, 'description' => 'Compact, idéal pour la voiture ou le bureau.', 'subCategory' => 'Diffuseurs', 'image' => '/diffuseur-nomade.jpg'],
            ['name' => 'Diffuseur programmable 8h', 'price' => 49.00, 'stock' => 10, 'description' => 'Programmation avec télécommande et arrêt automatique.', 'subCategory' => 'Diffuseurs', 'image' => '/diffuseur-programmable.jpg'],

            // --- LIVRES & GUIDES BIEN-ÊTRE ---
            ['name' => 'Les super aliments au quotidien', 'price' => 18.50, 'stock' => 30, 'description' => 'Recettes et astuces autour des graines, baies, spiruline.', 'subCategory' => 'Nutrition', 'image' => '/livre-superaliments.jpg'],
            ['name' => 'Recettes végétariennes & vitalité', 'price' => 24.90, 'stock' => 15, 'description' => '80 recettes simples et gourmandes sans viande.', 'subCategory' => 'Nutrition', 'image' => '/livre-vegetarien.jpg'],
            ['name' => 'Booster sa motivation', 'price' => 19.00, 'stock' => 18, 'description' => 'Techniques de coaching et pensée positive.', 'subCategory' => 'Développement personnel', 'image' => '/livre-motivation.jpg'],

            // --- COSMÉTIQUES BIO ---
            ['name' => 'Masque purifiant à l’argile verte', 'price' => 14.90, 'stock' => 25, 'description' => 'Détoxifie les pores et limite les imperfections.', 'subCategory' => 'Soins du visage', 'image' => '/cosmetique-masque-argile.jpg'],

            // --- MASSAGES ---

            ['name' => 'Massage Balinais corps complet – 1h', 'price' => 65.00, 'stock' => 10, 'description' => 'Un massage envoûtant mêlant douceur et tonus.', 'subCategory' => 'Massage Balinais', 'image' => '/massage-balinais.jpg'],
            ['name' => 'Massage Californien détente – 1h', 'price' => 58.00, 'stock' => 14, 'description' => 'Un toucher enveloppant pour libérer les tensions émotionnelles.', 'subCategory' => 'Massage Californien', 'image' => '/massage-californien.jpg'],
            ['name' => 'Massage aux pierres chaudes – 1h', 'price' => 70.00, 'stock' => 10, 'description' => 'La chaleur des pierres volcaniques favorise la détente musculaire.', 'subCategory' => 'Massage aux pierres chaudes', 'image' => '/massage-pierre-corps.jpg'],
            ['name' => 'Massage pierres chaudes visage – 30min', 'price' => 35.00, 'stock' => 10, 'description' => 'Chaleur douce pour détendre les traits.', 'subCategory' => 'Massage aux pierres chaudes', 'image' => '/massage-pierre-visage.jpg'],

            // --- SOINS DU CORPS ---
            ['name' => 'Gommage au sucre et huile d’amande douce', 'price' => 25.00, 'stock' => 30, 'description' => 'Exfolie et nourrit pour une peau soyeuse.', 'subCategory' => 'Gommages', 'image' => '/gommage-sucre.jpg'],
            ['name' => 'Gommage au sel de mer et lavande', 'price' => 24.00, 'stock' => 25, 'description' => 'Purifie en douceur avec un parfum relaxant.', 'subCategory' => 'Gommages', 'image' => '/gommage-sucre.jpg'],


            ['name' => 'Serviette hammam 100% coton bio', 'price' => 15.00, 'stock' => 50, 'description' => 'Grande, douce et ultra-absorbante.', 'subCategory' => 'Accessoires spa', 'image' => '/serviette-hammam.jpg'],
            ['name' => 'Brosse corps exfoliante', 'price' => 18.50, 'stock' => 30, 'description' => 'Stimule la circulation et prépare la peau au soin.', 'subCategory' => 'Accessoires spa', 'image' => '/brosse-exfoliante.jpg'],
        ];


        foreach ($productsData as $productData) {
            $product = new Product();
            $product->setName($productData['name']);
            $product->setPrice($productData['price']);
            $product->setStock($productData['stock']);
            $product->setDescription($productData['description']);
            $product->setCreatedDate(new DateTime());
            $product->setImage($productData['image']);
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
