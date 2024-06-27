<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Categories
        $categories = [
            1 => 'Massage',
            2 => 'Yoga',
            3 => 'Retreat'
        ];

        $categoryEntities = [];
        foreach ($categories as $id => $name) {
            $category = new Category();
/*            $category->setId($id); // If using auto-generated IDs, you don't need this line.*/
            $category->setName($name);
            $manager->persist($category);
            $categoryEntities[$id] = $category;
        }

        // Products
        $products = [
            [
                'id' => 1,
                'category_id' => 1,
                'name' => 'Le massage Thaï',
                'price' => 60,
                'image' => 'yoga3.png',
                'stock' => 5,
                'created_date' => new \DateTime('2024-02-09'),
                'description' => 'Le massage thaïlandais recouvre le corps entier. La théorie du massage thaïlandais se fonde sur le concept de lignes d’énergie liées au corps qui sont appelées sen. Si les écrits traditionnels de la médecine thaïe mentionnent 72 000 lignes d\'énergie, il existe 10 lignes principales (sen sip). Le massage thaï est une thérapie manuelle qui vise à rééquilibrer les flux énergétiques le long des sen en vue de renforcer ou de rétablir la bonne santé.
                Les sen ne doivent pas être confondus avec les méridiens qui sont les canaux énergétiques de la médecine traditionnelle chinoise. Les trajets des sen, qui relient la région abdominale aux extrémités ou aux orifices du corps, sont très différents de ceux des méridiens. Et, contrairement aux méridiens, les sen ne correspondent pas à des organes déterminés.',
                'comment' => null
            ],
            [
                'id' => 2,
                'category_id' => 1,
                'name' => 'Le massage Balinais',
                'price' => 60,
                'image' => 'yoga2.png',
                'stock' => 5,
                'created_date' => new \DateTime('2024-02-09'),
                'description' => 'Le massage balinais, trouvant son origine en Indonésie, s’adresse en particulier aux personnes ayant des douleurs musculaires ou qui sont soumises quotidiennement au stress. Ce massage permet de revitaliser le corps autant que l’esprit et de retrouver une énergie positive qui ne pourra qu’être bénéfique sur l’ensemble de l’organisme.
                Grâce à un travail profond, on harmonise le corps et l’esprit. Le massage balinais se divise en deux parties. Durant la première, on opère une décontraction dynamisante à l\'aide de pétrissages, de pressions sur les méridiens, de frictions, ainsi que de lissages profonds. La deuxième partie est plus relaxante. On y effectue des étirements doux, et on termine par un modelage de la tête. Le ou la praticien(ne) utilise une huile de massage et masse avec les doigts ou la paume de la main pour commencer. Puis avec les avant-bras, voire parfois avec les coudes : c’est un massage à la fois doux et profond.',
                'comment' => null
            ],
            [
                'id' => 3,
                'category_id' => 1,
                'name' => 'Le massage Californien',
                'price' => 60,
                'image' => 'yoga4.png',
                'stock' => 5,
                'created_date' => new \DateTime('2024-02-09'),
                'description' => 'Plus communément appelé le « toucher du cœur », le massage californien est une pratique psycho-corporelle datant des années 70. Le massage californien est une approche globale qui vise autant la détente que l\'éveil d\'une conscience psychocorporelle. Ce massage utilise de longs mouvements lents et fluides qui permettent une profonde relaxation physique et psychique. Partant d\'effleurages doux, enveloppants et relaxants, les manoeuvres s’enchaînent et s’intensifient pour soulager des tensions plus profondes. Cela peut faire surgir et libérer des émotions cachées, inscrites dans la mémoire corporelle. Plus la personne massée s’abandonne à l\'expérience, plus elle s\'ouvre à ce que le massage californien qualifie de toucher du coeur. Profondément sensible, le bon déroulement du massage californien dépend totalement de la connexion entre le donneur et le receveur. En effet, il est primordial que le thérapeute soit à l\'écoute et que le massé soit en totale confiance.',
                'comment' => null
            ],
            [
                'id' => 4,
                'category_id' => 1,
                'name' => 'Le massage aux pierre chaudes',
                'price' => 60,
                'image' => 'yoga5.png',
                'stock' => 5,
                'created_date' => new \DateTime('2024-02-09'),
                'description' => 'Le massage aux pierres chaudes est un type de massage au cours duquel des pierres, généralement issues de la pierre de basalte, sont utilisées. Afin de faire chauffer les pierres volcaniques, ces dernières sont placées dans de l’eau chaude pour qu’elles obtiennent une température suffisamment élevée.
                Une fois que les pierres sont chaudes et le patient allongé, les pierres peuvent être déposées délicatement sur les différentes zones du corps. Très souvent, les pierres de basalte sont positionnées les unes après les autres sur les méridiens du corps, sur et autour de la colonne vertébrale.
                Lors du massage aux pierres chaudes, la chaleur des pierres ainsi que leur poids permet de réchauffer et détendre les muscles du corps. La chaleur se propage progressivement et aide à dissiper les tensions. C’est idéal pour masser en profondeur sans avoir forcément à appliquer une pression plus importante.
                Comme pour tous les autres massages, il convient bien sûr d’effectuer une bonne préparation afin que les étapes du massage se déroulent au mieux. Le but étant de réunir toutes les conditions propices à la détente du corps et de l’esprit.',
                'comment' => null
            ],
            [
                'id' => 5,
                'category_id' => 2,
                'name' => 'Hatha Yoga',
                'price' => 30,
                'image' => 'yoga6.png',
                'stock' => 15,
                'created_date' => new \DateTime('2023-02-09'),
                'description' => 'Le Yoga Hatha est certainement la variante la plus connue chez nous. C’est un terme générique qui englobe toutes les formes physiques de yoga. Une introduction aux fondamentaux de cette discipline, avec une approche classique des postures et des techniques de respiration. Dans de nombreux cas, il est également pratiqué sous forme d’étirements avant ou après un autre type d’entraînement.
                L’approche du Yoga Hatha est d’apprendre en premier lieu à contrôler son corps pour pouvoir apprendre à contrôler son esprit. Alors que le yoga mental propose le contraire. Ainsi, nous pourrions définir le Yoga Hatha comme un ensemble d’exercices qui cherche la pleine conscience par la maîtrise du corps, en effectuant différentes postures physiques et en apprenant à contrôler la respiration.
                En sanskrit, cela signifie soleil (masculin) et lune (féminin). Ainsi, l’objectif de ce Yoga est de parvenir à un équilibre entre les énergies masculine et féminine qui existent en nous, à travers l’utilisation de postures qui cherchent à trouver l’équilibre entre force et flexibilité.
                Le Hatha considère le corps comme un instrument, et en tant que tel, il doit être maintenu dans les meilleures conditions possibles pour atteindre un niveau de conscience plus élevé. Et, bien qu’il soit vrai qu’il peut être pratiqué dans le seul objectif d’améliorer la santé, la vérité est que c’est aussi un excellent moyen d’atteindre le bien-être mental et un meilleur chemin vers la méditation.',
                'comment' => null
            ],
            [
                'id' => 6,
                'category_id' => 2,
                'name' => 'Vinyasa Yoga',
                'price' => 30,
                'image' => 'yoga.png',
                'stock' => 15,
                'created_date' => new \DateTime('2024-02-09'),
                'description' => 'Le Vinyasa est un des cours de yoga les plus populaires en France, avec le Hatha et l’Ashtanga. Dynamique et fluide, il permet aux élèves de s’offrir un temps de lâcher-prise tout en se tonifiant et s’étirant en profondeur.
                Le Vinyasa est un yoga dynamique, qui associe à la fois postures, exercices de respiration (pranayama), intention. Tous les mouvements sont synchronisés au rythme de la respiration, ce qui rend l’enchaînement, appelé aussi Flow, fluide et presque chorégraphique.',
                'comment' => null
            ],
            [
                'id' => 7,
                'category_id' => 2,
                'name' => 'Fly Yoga',
                'price' => 40,
                'image' => 'yoga.png',
                'stock' => 15,
                'created_date' => new \DateTime('2023-02-09'),
                'description' => 'Le yoga aérien (ou Fly Yoga et Flying Yoga) a été créé dans les années 2000 aux États-Unis. C’est un type de yoga doux qui se pratique dans un hamac suspendu et qui fait travailler l’ensemble des muscles du corps. Ce type de yoga intègre de la relaxation, de la méditation, des étirements et des mouvements de danse, de pilates, et de gymnastique acrobatique. Le yoga aérien donne l’impression de « flotter » et permet de faire l’expérience de différentes sensations qui favorisent la sérénité et la confiance en soi. On pratique des postures dans un hamac situé à 1 mètre du sol en alternant plusieurs mouvements avec appui au sol et postures aériennes. Le hamac est suffisamment haut pour que la tête ne touche pas le sol dans les inversions, mais les bras peuvent facilement se mettre au sol depuis le hamac pour avoir un appui supplémentaire dans certaines postures. Ce type de yoga suspendu permet à la fois d’étirer le corps et la colonne et de renforcer les muscles en profondeur.',
                'comment' => null
            ],
            [
                'id' => 8,
                'category_id' => 3,
                'name' => 'Vipassana',
                'price' => 150,
                'image' => 'yoga2.png',
                'stock' => 10,
                'created_date' => new \DateTime('2023-02-09'),
                'description' => 'Vipassanā (pāli) ou vipaśyanā (विपश्यना, sanskrit ; chinois 觀 guān; tibétain ལྷག་མཐོང་, lhaktong) désigne dans la tradition bouddhique la « vue profonde » ou « inspection », ainsi que les pratiques de méditation qui y sont associées. C\'est la deuxième étape des pratiques de méditation dans le bouddhisme, qui est utilisée après samatha, « la pacification mentale ».
                Vipassanā peut être défini comme « la lumière intuitive apparaissant brusquement et révélant la vérité sur l\'impermanence, sur la misère de l\'existence, ou sur l\'absence d\'un soi ». C’est donc une manière de se libérer des conditionnements mentaux, des attachements, des illusions qui nous emprisonnent dans la souffrance.',
                'comment' => null
            ]
        ];

        foreach ($products as $productData) {
            $product = new Product();
            //$product->setId($productData['id']); // If using auto-generated IDs, you don't need this line.
            $product->setCategory($categoryEntities[$productData['category_id']]);
            $product->setName($productData['name']);
            $product->setPrice($productData['price']);
            $product->setImage($productData['image']);
            $product->setStock($productData['stock']);
            $product->setCreatedDate($productData['created_date']);
            $product->setDescription($productData['description']);
            $product->setComment($productData['comment']);
            $manager->persist($product);
        }

        $manager->flush();
    }
}


