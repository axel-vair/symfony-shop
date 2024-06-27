-- Adminer 4.8.1 PostgreSQL 16.3 (Debian 16.3-1.pgdg120+1) dump

DROP TABLE IF EXISTS "categories";
CREATE TABLE "public"."categories" (
    "id" integer NOT NULL,
    "name" character varying(255) NOT NULL,
    CONSTRAINT "category_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

INSERT INTO "categories" ("id", "name") VALUES
(1,	'Massage'),
(2,	'Yoga'),
(3,	'Retreat');

DROP TABLE IF EXISTS "comment";
CREATE TABLE "public"."comment" (
    "id" integer NOT NULL,
    "description" text,
    "rating" double precision,
    CONSTRAINT "comment_pkey" PRIMARY KEY ("id")
) WITH (oids = false);


DROP TABLE IF EXISTS "doctrine_migration_versions";
CREATE TABLE "public"."doctrine_migration_versions" (
    "version" character varying(191) NOT NULL,
    "executed_at" timestamp(0),
    "execution_time" integer,
    CONSTRAINT "doctrine_migration_versions_pkey" PRIMARY KEY ("version")
) WITH (oids = false);

INSERT INTO "doctrine_migration_versions" ("version", "executed_at", "execution_time") VALUES
('DoctrineMigrations\Version20240626131653',	'2024-06-26 13:17:00',	42);

DROP TABLE IF EXISTS "messenger_messages";
DROP SEQUENCE IF EXISTS messenger_messages_id_seq;
CREATE SEQUENCE messenger_messages_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."messenger_messages" (
    "id" bigint DEFAULT nextval('messenger_messages_id_seq') NOT NULL,
    "body" text NOT NULL,
    "headers" text NOT NULL,
    "queue_name" character varying(190) NOT NULL,
    "created_at" timestamp(0) NOT NULL,
    "available_at" timestamp(0) NOT NULL,
    "delivered_at" timestamp(0),
    CONSTRAINT "messenger_messages_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

CREATE INDEX "idx_75ea56e016ba31db" ON "public"."messenger_messages" USING btree ("delivered_at");

CREATE INDEX "idx_75ea56e0e3bd61ce" ON "public"."messenger_messages" USING btree ("available_at");

CREATE INDEX "idx_75ea56e0fb7336f0" ON "public"."messenger_messages" USING btree ("queue_name");

COMMENT ON COLUMN "public"."messenger_messages"."created_at" IS '(DC2Type:datetime_immutable)';

COMMENT ON COLUMN "public"."messenger_messages"."available_at" IS '(DC2Type:datetime_immutable)';

COMMENT ON COLUMN "public"."messenger_messages"."delivered_at" IS '(DC2Type:datetime_immutable)';


DELIMITER ;;

CREATE TRIGGER "notify_trigger" AFTER INSERT OR UPDATE ON "public"."messenger_messages" FOR EACH ROW EXECUTE FUNCTION notify_messenger_messages();;

DELIMITER ;

DROP TABLE IF EXISTS "product";
CREATE TABLE "public"."product" (
    "id" integer NOT NULL,
    "category_id" integer,
    "name" character varying(255) NOT NULL,
    "price" double precision NOT NULL,
    "image" character varying(255),
    "stock" integer,
    "created_date" date,
    "description" text,
    "comment" text,
    CONSTRAINT "product_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

CREATE INDEX "idx_d34a04ad12469de2" ON "public"."product" USING btree ("category_id");

INSERT INTO "product" ("id", "category_id", "name", "price", "image", "stock", "created_date", "description", "comment") VALUES
(1,	1,	'Le massage Thaï',	60,	NULL,	5,	'2024-02-09',	'Le massage thaïlandais recouvre le corps entier. La théorie du massage thaïlandais se fonde sur le concept de lignes d’énergie liées au corps qui sont appelées sen. Si les écrits traditionnels de la médecine thaïe mentionnent 72 000 lignes d''énergie, il existe 10 lignes principales (sen sip). Le massage thaï est une thérapie manuelle qui vise à rééquilibrer les flux énergétiques le long des sen en vue de renforcer ou de rétablir la bonne santé.
Les sen ne doivent pas être confondus avec les méridiens qui sont les canaux énergétiques de la médecine traditionnelle chinoise. Les trajets des sen, qui relient la région abdominale aux extrémités ou aux orifices du corps, sont très différents de ceux des méridiens. Et, contrairement aux méridiens, les sen ne correspondent pas à des organes déterminés.
',	NULL),
(2,	1,	'Le massage Balinais',	60,	NULL,	5,	'2024-02-09',	'Le massage balinais, trouvant son origine en Indonésie, s’adresse en particulier aux personnes ayant des douleurs musculaires ou qui sont soumises quotidiennement au stress. Ce massage permet de revitaliser le corps autant que l’esprit et de retrouver une énergie positive qui ne pourra qu’être bénéfique sur l’ensemble de l’organisme. 

 Grâce à un travail profond, on harmonise le corps et l’esprit. Le massage balinais se divise en deux parties. Durant la première, on opère une décontraction dynamisante à l''aide de pétrissages, de pressions sur les méridiens, de frictions, ainsi que de lissages profonds. La deuxième partie est plus relaxante. On y effectue des étirements doux, et on termine par un modelage de la tête. Le ou la praticien(ne) utilise une huile de massage et masse avec les doigts ou la paume de la main pour commencer. Puis avec les avant-bras, voire parfois avec les coudes : c’est un massage à la fois doux et profond. 
',	NULL),
(3,	1,	'Le massage Californien',	60,	NULL,	5,	'2024-02-09',	'Plus communément appelé le « toucher du cœur », le massage californien est une pratique psycho-corporelle datant des années 70. Le massage californien est une approche globale qui vise autant la détente que l''éveil d''une conscience psychocorporelle. Ce massage utilise de longs mouvements lents et fluides qui permettent une profonde relaxation physique et psychique. Partant d''effleurages doux, enveloppants et relaxants, les manoeuvres s’enchaînent et s’intensifient pour soulager des tensions plus profondes. Cela peut faire surgir et libérer des émotions cachées, inscrites dans la mémoire corporelle. Plus la personne massée s’abandonne à l''expérience, plus elle s''ouvre à ce que le massage californien qualifie de toucher du coeur. Profondément sensible, le bon déroulement du massage californien dépend totalement de la connexion entre le donneur et le receveur. En effet, il est primordial que le thérapeute soit à l''écoute et que le massé soit en totale confiance.
',	NULL),
(4,	1,	'Le massage aux pierre chaudes',	60,	NULL,	5,	'2024-02-09',	'Le massage aux pierres chaudes est un type de massage au cours duquel des pierres, généralement issues de la pierre de basalte, sont utilisées. Afin de faire chauffer les pierres volcaniques, ces dernières sont placées dans de l’eau chaude pour qu’elles obtiennent une température suffisamment élevée.
Une fois que les pierres sont chaudes et le patient allongé, les pierres peuvent être déposées délicatement sur les différentes zones du corps. Très souvent, les pierres de basalte sont positionnées les unes après les autres sur les méridiens du corps, sur et autour de la colonne vertébrale.
Lors du massage aux pierres chaudes, la chaleur des pierres ainsi que leur poids permet de réchauffer et détendre les muscles du corps. La chaleur se propage progressivement et aide à dissiper les tensions. C’est idéal pour masser en profondeur sans avoir forcément à appliquer une pression plus importante.
Comme pour tous les autres massages, il convient bien sûr d’effectuer une bonne préparation afin que les étapes du massage se déroulent au mieux. Le but étant de réunir toutes les conditions propices à la détente du corps et de l’esprit.
',	NULL),
(5,	2,	'Hatha Yoga',	30,	NULL,	15,	'2023-02-09',	'Le Yoga Hatha est certainement la variante la plus connue chez nous. C’est un terme générique qui englobe toutes les formes physiques de yoga. Une introduction aux fondamentaux de cette discipline, avec une approche classique des postures et des techniques de respiration. Dans de nombreux cas, il est également pratiqué sous forme d’étirements avant ou après un autre type d’entraînement.
L’approche du Yoga Hatha est d’apprendre en premier lieu à contrôler son corps pour pouvoir apprendre à contrôler son esprit. Alors que le yoga mental propose le contraire. Ainsi, nous pourrions définir le Yoga Hatha comme un ensemble d’exercices qui cherche la pleine conscience par la maîtrise du corps, en effectuant différentes postures physiques et en apprenant à contrôler la respiration.
En sanskrit, cela signifie soleil (masculin) et lune (féminin). Ainsi, l’objectif de ce Yoga est de parvenir à un équilibre entre les énergies masculine et féminine qui existent en nous, à travers l’utilisation de postures qui cherchent à trouver l’équilibre entre force et flexibilité.
Le Hatha considère le corps comme un instrument, et en tant que tel, il doit être maintenu dans les meilleures conditions possibles pour atteindre un niveau de conscience plus élevé. Et, bien qu’il soit vrai qu’il peut être pratiqué dans le seul objectif d’améliorer la santé, la vérité est que c’est aussi un excellent moyen d’atteindre le bien-être mental et un meilleur chemin vers la méditation.
',	NULL),
(6,	2,	'Vinyasa Yoga',	30,	NULL,	15,	'2024-02-09',	'Le Vinyasa est un des cours de yoga les plus populaires en France, avec le Hatha et l’Ashtanga. Dynamique et fluide, il permet aux élèves de s’offrir un temps de lâcher-prise tout en se tonifiant et s’étirant en profondeur.
Le Vinyasa est un yoga dynamique, qui associe à la fois postures, exercices de respiration (pranayama), intention. Tous les mouvements sont synchronisés au rythme de la respiration, ce qui rend l’enchaînement, appelé aussi Flow, fluide et presque chorégraphique. 
',	NULL),
(7,	2,	'Fly Yoga',	40,	NULL,	15,	'2023-02-09',	'Le yoga aérien (ou Fly Yoga et Flying Yoga) a été créé dans les années 2000 aux États-Unis. C’est un type de yoga doux qui se pratique dans un hamac suspendu et qui fait travailler l’ensemble des muscles du corps. Ce type de yoga intègre de la relaxation, de la méditation, des étirements et des mouvements de danse, de pilates, et de gymnastique acrobatique. Le yoga aérien donne l’impression de « flotter » et permet de faire l’expérience de différentes sensations qui favorisent la sérénité et la confiance en soi. On pratique des postures dans un hamac situé à 1 mètre du sol en alternant plusieurs mouvements avec appui au sol et postures aériennes. Le hamac est suffisamment haut pour que la tête ne touche pas le sol dans les inversions, mais les bras peuvent facilement se mettre au sol depuis le hamac pour avoir un appui supplémentaire dans certaines postures. Ce type de yoga suspendu permet à la fois d’étirer le corps et la colonne et de renforcer les muscles en profondeur.
',	NULL),
(8,	3,	'Vipassana ',	150,	NULL,	10,	'2023-02-09',	'Vipassanā (pāli) ou vipaśyanā (विपश्यना, sanskrit ; chinois 觀 guān; tibétain ལྷག་མཐོང་, lhaktong) désigne dans la tradition bouddhique la « vue profonde » ou « inspection », ainsi que les pratiques de méditation qui y sont associées. C''est la deuxième étape des pratiques de méditation dans le bouddhisme, qui est utilisée après samatha, « la pacification mentale ».
Vipassanā peut être défini comme « la lumière intuitive apparaissant brusquement et révélant la vérité sur l''impermanence, sur la misère et sur l''impersonnalité de tous les phénomènes corporels et mentaux de l''existence.
La technique de méditation Vipassana est enseignée lors de cours résidentiels de dix jours pendant lesquels les participants apprennent les bases de la méthode, et pratiquent suffisamment pour obtenir des résultats bénéfiques.
« Cette pratique vise à nous libérer des mécanismes d’enfermement liés à la cognition, des jugements des intentions, afin de développer une forme de sagesse consistant à reconnaître et accepter que toute expérience est éphémère (impermanence) et que la présence est nécessaire pour ne pas y réagir trop fortement. La présence aux sensations nous libère des réactions excessives (sanskara) que nous pourrions manifester : Vipassana apprend à sentir la nature de la sensation, comprendre son mouvement, puis ne plus être dans la réaction. »
– Fabrice Midal (« La méditation pour les nuls »)
',	NULL),
(9,	3,	'Yoga retreat',	150,	NULL,	15,	'2023-02-09',	'Niché au-dessus de la plage d’Om Beach (Gokarna), le SwaSwara s’inspire de l’architecture traditionnelle konkani. Les 24 villas respirent au rythme de la nature environnante. Le lieu propose divers programmes axés sur la méditation, le yoga et l’ayurveda. Cette bulle de tranquillité offre des programmes riches : yoga, yoga nidra, pranayama, dharana (concentration), dhyana (méditation)… Soins ayurvédiques et nourriture végétarienne complètent votre ressourcement.',	NULL);

DROP TABLE IF EXISTS "user";
CREATE TABLE "public"."user" (
    "id" integer NOT NULL,
    "comment_id" integer,
    "email" character varying(180) NOT NULL,
    "roles" json NOT NULL,
    "password" character varying(255) NOT NULL,
    CONSTRAINT "uniq_identifier_email" UNIQUE ("email"),
    CONSTRAINT "user_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

CREATE INDEX "idx_8d93d649f8697d13" ON "public"."user" USING btree ("comment_id");


ALTER TABLE ONLY "public"."product" ADD CONSTRAINT "fk_d34a04ad12469de2" FOREIGN KEY (category_id) REFERENCES categories(id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."user" ADD CONSTRAINT "fk_8d93d649f8697d13" FOREIGN KEY (comment_id) REFERENCES comment(id) NOT DEFERRABLE;

-- 2024-06-27 08:15:47.992066+00
