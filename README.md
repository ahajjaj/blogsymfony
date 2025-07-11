# blogSymfony

Clonez le dépôt

Rendez vous dans le dossier

Ouvrez le projet sur VSCode

Installez les dépendances avec composer install

Lancez les migrations en tapant php bin/console doctrine:migrations:migrate

Lancez les fixtures en tapant php bin/console doctrine:fixtures:load

Lancez le serveur avec php bin/console server:run

## Comment exécuter le projet

Pour démarrer l'application avec Docker, assurez-vous d'être à la racine du projet puis exécutez :

```bash
docker-compose up --build
```

L'application sera alors accessible sur `http://localhost:8000`.
