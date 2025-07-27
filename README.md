# Boutique en ligne Symfony

## Fonctionnalités principales

### Authentification
- ~~Inscription d'un nouvel utilisateur~~ ✅
- ~~Connexion/déconnexion d'un utilisateur~~ ✅
-  ~~Connexion OAuth2~~ ✅


_Optionnel_ :
- Récupération de mot de passe

### Gestion du compte utilisateur
- ~~_Affichage et modification des informations du compte_~~ ✅
- ~~Affichage de l'historique des commandes~~ ✅

### Catalogue de produits

- ~~Affichage de la liste des produits avec filtres et pagination~~ ✅
- ~~Page de détails d'un produit~~ ✅
- ~~Gestion des catégories de produits~~ ✅

### Panier d'achat
- ~~Ajout/suppression de produits dans le panier~~ ✅
- ~~Mise à jour des quantités~~ ✅
- ~~Calcul du total du panier~~ ✅

### Processus de commande
- Formulaire de commande (adresse de livraison, etc.)
- ~~Récapitulatif de la commande~~ ✅
- ~~Enregistrement de la commande en base de données~~ ✅

_Optionnel_ :
- Paiement en ligne (avec un module tiers comme Stripe)

### Gestion des commandes (partie administration)
- ~~Liste des commandes avec pagination~~ ✅
- ~~Détails d'une commande~~ ✅
- Changement de statut d'une commande

### Gestion des routes et sécurité 
- ~~Créer une route 404~~ (but dont work in dev) ✅
- ~~Autoriser l'accès au panier pour les utilisateurs connectés uniquement~~ ✅
- ~~Gérer l'accès au dashboard~~ ✅

### Communication
- Chat en direct à l'aide de Mercure


### Launch sonarqube scanner 

- Ajout de la configuration SonarQube dans le compose.yaml 
- Lancement des containers
- Allez sur le port 9001 pour configurer le projet
- Créer un sonar-project.properties et renseigner les informations (et token généré)
- Installation de sonar-scanner via homebrew
- Lancer la commande : 

```
sonar-scanner \
  -Dsonar.projectKey=butterfly \
  -Dsonar.sources=. \
  -Dsonar.host.url=http://localhost:9001 \
  -Dsonar.token=sqp_2d1b1c5bb608031e1dd6d84e87f8c29096ed2fcb

```


Code coverage avec XDEBUG:

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
| ----------------------- | ------------------------------- |
| Langage principal       | PHP 8.3                         |
| Framework               | Symfony                         |
| Outil de tests          | PHPUnit                         |
| Analyse de code         | PHPStan (niveau 8)              |
| Système CI              | GitHub Actions                  |
| OS du runner CI         | Ubuntu (latest)                 |
| Base de données de test | SQLite                          |
| Gestionnaire de paquets | Composer                        |
| Conteneurisation        | Docker (cf. fichier ci-dessous) |


### ⚙️ Infrastructure utilisée

**CI/CD via GitHub Actions** : déclencheur sur push et pull_request vers main.


**Outils intégrés dans le pipeline** :
- composer install
- phpunit pour l'exécution des tests
- phpstan pour l’analyse de code statique
- Serveur d’automatisation : GitHub Actions configuré par fichier YAML.
- Conteneurisation : environnement exécutable via Docker (voir fichier ci-dessous).

### 🔁 Déroulement du pipeline
- Checkout du code
- Installation de PHP et des dépendances Composer
- Création de la base SQLite 
- Exécution des tests avec PHPUnit
- Analyse statique avec PHPStan


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