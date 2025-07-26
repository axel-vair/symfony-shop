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
