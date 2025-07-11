# NovaBlog

NovaBlog est une application de blog construite avec le framework Symfony 5.2. Elle permet la gestion d'articles classés par catégories, la publication de commentaires et la gestion des utilisateurs avec un espace d'administration.

## Fonctionnalités principales

- Consultation de la liste des articles et des catégories
- Création, modification et suppression d'articles (selon le rôle de l'utilisateur)
- Ajout de commentaires sur un article
- Inscription et authentification des utilisateurs
- Interface d'administration pour gérer les utilisateurs

## Architecture du projet

L'application suit l'architecture MVC classique de Symfony :

- **src/Controller/** : contient les contrôleurs (BlogController, AdminController, SecurityController) gérant les différentes pages et actions.
- **src/Entity/** : définit les entités Doctrine (Article, Category, Comment, User).
- **src/Form/** : déclare les formulaires utilisés pour la création d'articles, l'inscription, la gestion des utilisateurs, etc.
- **templates/** : les vues Twig affichées aux utilisateurs.
- **migrations/** : les fichiers de migration de base de données générés par Doctrine.

## Installation et exécution en local

### Prérequis

- PHP >= 7.2.5
- [Composer](https://getcomposer.org/)
- Une base de données compatible avec Doctrine (MySQL, PostgreSQL, …)

### Étapes

1. Cloner ce dépôt puis se placer dans le dossier du projet :
   ```bash
   git clone <repository-url>
   cd novablog
   ```
2. Installer les dépendances PHP :
   ```bash
   composer install
   ```
3. Configurer la connexion à la base de données dans le fichier `.env` (ou `.env.local`).
4. Exécuter les migrations Doctrine pour créer les tables :
   ```bash
   php bin/console doctrine:migrations:migrate
   ```
5. (Optionnel) Charger les données de démonstration grâce aux fixtures :
   ```bash
   php bin/console doctrine:fixtures:load
   ```
6. Lancer le serveur de développement :
   ```bash
   php bin/console server:run
   ```
   L'application est alors disponible sur `http://localhost:8000`.

## Dépendances principales

Les dépendances sont déclarées dans `composer.json`. On y retrouve notamment :

- Symfony Framework Bundle et ses composants (twig, security, form, etc.)
- Doctrine ORM et Doctrine Migrations
- MakerBundle pour générer le code
- Faker pour les fixtures de test

Pour la liste complète, consulter le fichier `composer.json` fourni dans le dépôt.

Lancez le serveur avec php bin/console server:run

## Comment exécuter le projet

Pour démarrer l'application avec Docker, assurez-vous d'être à la racine du projet puis exécutez :

```bash
docker-compose up --build
```

L'application sera alors accessible sur `http://localhost:8000`.

## Utilisation avec GitHub Codespaces

Ce dépôt inclut une configuration **devcontainer** permettant de démarrer rapideme
nt l'application dans GitHub Codespaces avec PHP 8.3.14, OpenSSL et Composer préinstallés. Il suffit d'ouvrir le projet dans Codespaces puis d'exécuter :

```bash
composer install
php bin/console doctrine:migrations:migrate
symfony serve -d
```

L'application sera accessible sur `http://localhost:8000`.
