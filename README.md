## Racoin

Racoin est une application de vente en ligne entre particulier.

## Remarques

Au vu du temps imparti et de la longueur des tâches, les models sont restés en français, pour des raisons de lisibilité avec la base de données
L'utilisation de service a été mis en place sur les méthodes utilisées de manière répétée, pour éviter la duplication de code

Utiliser la commande ci-dessous pour générer la documentation Open API
```bash
npm run openapi:generate
```

## Installation
Les commandes suivantes permettent d'installer les dépendances et de construire les fichiers statiques nécessaires au bon fonctionnement de l'application.
```bash
cp app/config/config.ini.dist app/config/config.ini
docker compose run --rm php composer install
docker compose run --rm php php sql/initdb.php
docker compose run node npm install
docker compose run node npm run build
```

## Utilisation
Pour lancer l'application, il suffit de lancer la commande suivante:
```bash
docker compose up
```
