## 🧭 Expression des besoins du projet

Ce projet a été réalisé dans un cadre scolaire, selon un **cahier des charges imposé**. L'objectif principal était de développer une **boutique en ligne complète avec Symfony**, en respectant les bonnes pratiques de développement, d’architecture logicielle et de DevOps.

### 🎯 Objectifs fonctionnels

L’application devait inclure les modules suivants :

- Authentification (inscription, connexion)
- Page de profil utilisateur
- Catalogue d’articles avec :
    - Filtres par **catégorie**
    - Tri par **date de publication**
    - **Pagination** (5 articles par page minimum)
- Panier d’achat
- Paiement (réel ou simulé)
- Historique des commandes

### 🛠️ Objectifs techniques

L’application devait impérativement utiliser :

- **Symfony CLI** pour la gestion du projet
- **Doctrine ORM** pour la gestion des entités et requêtes (DQL)
- Composants techniques :
    - `Entities`, `Repositories`, `DTO`, `Migrations`
    - `Controllers`, `Services`, `Interfaces`, `Routes`, `BuildForm`, `Middlewares`
- Documentation de l'API via **API Platform**
- Base de données nommée `symfony-shop` avec les tables :
    - `User`, `Article`, `Category`, `Comment`, `Role`
- Tous les formulaires générés via `BuildForm`
- Utilisation de middlewares pour restreindre l’accès aux routes

### 🔒 Sécurité

- Gestion des droits d’accès par rôles (`user`, `admin`)
- Vérification de l’authentification avant l’accès à certaines routes
- Définition des routes via contrôleurs ou fichier YAML

### 🚀 Fonctionnalités avancées optionnelles

- Implémenter un **scheduler** (cron) pour effectuer des sauvegardes régulières de la base de données
- Utilisation de **PHP Mailer** pour l’envoi de mails :
    - Lors de l’inscription
    - Lors d’un achat
- Intégration de **Faker.js** pour la génération de données fictives
- Utilisation de **DTOs** pour encapsuler et valider les données d’entrée dans les contrôleurs

---

## 🚫 Limites du projet

Certaines fonctionnalités ont été exclues ou partiellement développées dans le cadre du projet, notamment :

- 🔒 Paiement réel en ligne : non implémenté (simulation seulement)
- 📅 Scheduler : non intégré (proposé comme amélioration future)
- 📤 Envoi de mail : partiellement implémenté (proposé comme amélioration future)


---

## ✅ Traduction du cahier des charges en livrables

| Besoin exprimé                            | Réalisation                               | Statut     |
|------------------------------------------|-------------------------------------------|------------|
| Symfony CLI, Doctrine, BuildForm         | Intégrés et utilisés dans tout le projet  | ✅ Fait     |
| Authentification + page profil           | Fonctionnels                              | ✅ Fait     |
| Catalogue avec filtres et pagination     | Filtres par catégorie et date, pagination | ✅ Fait     |
| Panier d’achat                           | Fonctionnel, stockage en session          | ✅ Fait     |
| Historique des commandes                 | Accessible depuis le profil utilisateur   | ✅ Fait     |
| Paiement                                  | Intégration de Stripe                     | ✅ Fait  |
| API Platform                     | API Platform utilisé                      | ✅ Fait  |
| Scheduler (backup auto BDD)              | Non réalisé                               | ❌ Non fait |
| Envoi d’e-mails (PHP Mailer)             | Non réalisé                               |  ❌ Non fait  |
| Faker.js (génération de données fictives) | Utilisé pour pré-remplir la base          | ✅ Fait     |




## ✳️ SonarQube configuration  

- Ajout de la configuration SonarQube dans le compose.yaml 
- Lancement des containers
- Allez sur le port 9001 pour configurer le projet
- Créer un sonar-project.properties et renseigner les informations (et token généré)
- Installation de sonar-scanner via homebrew

###  Launch Sonar-Scanner

```
sonar-scanner \
  -Dsonar.projectKey=butterfly \
  -Dsonar.sources=. \
  -Dsonar.host.url=http://localhost:9001 \
  -Dsonar.token={SONAR_LOGIN}

```

### Launch XDEBUG code coverage that will be get by sonar

``
 XDEBUG_MODE=coverage ./vendor/bin/phpunit --coverage-text
``


## 📄 Dossier technique CI/CD

### CI/CD Summary (EN)

CI is handled by GitHub Actions triggered on push and pull_request on the `main` branch.  
Tests are automated using PHPUnit, and static analysis is done with PHPStan (level 8).  
Reports are interpreted through GitHub Actions logs.  
Docker is used for containerization.  
In case of failure, the error logs are read and the problem is debugged locally.


### 🔧 Environnement technique
| Élément                 | Détail                          |
|-------------------------|---------------------------------|
| Langage principal       | PHP 8.3                         |
| Framework               | Symfony                         |
| Outil de tests          | PHPUnit                         |
| Analyse de code         | PHPStan (niveau 8)              |
| Système CI              | GitHub Actions                  |
| OS du runner CI         | Ubuntu (latest)                 |
| Base de données de test | Postgresql                      |
| Gestionnaire de paquets | Composer                        |
| Conteneurisation        | Docker (cf. fichier ci-dessous) |
| E2E test                | Geckodriver & firefox           |


### ⚙️ Infrastructure utilisée

**CI/CD via GitHub Actions** : déclencheur sur push et pull_request vers main.


**Outils intégrés dans le pipeline** :
- composer install
- installation de firefox & geckodriver
- drop de la database de test
- création de la database de test
- création du shéma de la database
- exécution des fixtures
- install importmap
- clear du cache
- phpunit pour l'exécution des tests (y compris tests endtoend)
- phpstan pour l’analyse de code statique


### 🔁 Déroulement du pipeline
- Checkout du code
- Installation de PHP et des dépendances Composer
- Installation de Firefox et de Gecko Driver
- Création de la base Postgresql 
- Exécution des tests avec PHPUnit
- Analyse statique avec PHPStan

### 📈 Indicateurs suivis

- **Taux de couverture de tests (via PHPUnit/XDEBUG)** : 
  - classes 38,64% 
  - Méthods: 44,21%
  - Lines: 42,74%
- **Nombre d'erreurs PHPStan** : 0 (niveau 8)
- **Bugs/vulnérabilités SonarQube** : 0 critiques

### 🧪 Interprétation des rapports
- PHPUnit : en cas d’échec, le job CI affiche les tests KO dans la console GitHub Actions.
- PHPStan : le niveau 8 indique une exigence élevée. Chaque erreur est listée avec ligne et explication.

### 🧯 Démarche de résolution de problème
- Identifier le job qui échoue sur GitHub Actions.
- Lire le journal du job pour repérer l’erreur (ex : test cassé, dépendance manquante).
- Reproduire localement si besoin.
- Appliquer une correction ou ouvrir une issue GitHub.
- Ajouter un test de non-régression si nécessaire.

### 🕵️ Veille technologique DevOps
Je consulte régulièrement les sources suivantes :

- Site de Symfony
- Site php.net
- Blog GitHub Actions
- OWASP pour les vulnérabilités
- PHPStan changelog (ex : nouvelles règles disponibles)