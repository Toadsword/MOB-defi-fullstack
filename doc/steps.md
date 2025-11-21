# Etapes de développement

Ce fichier recense les étapes entreprises pour réaliser le défi-technique proposé avec le plus de transparence possible.

## 1. Mise en place de l'environnement
La première étape est d'initialiser et créer l'environnement de travail.

N'étant pas familier avec la création d'environnement Docker et Vue, j'ai d'abord cherché à générer une base avec une IA, afin de comprendre une base que je pouvais travailler selon mes besoins. Cependant, la structure de fichier de base n'a pas été respecté et j'ai ainsi opté pour la documentation en ligne pour créer mon environnement.

Mon IDE de préférence est : Visual Studio Code

J'ai choisis d'adopter la structure de dossier suivante :
Dossier ´frontend´ : où vue.js sera installé
Dossier ´backend´ : où je vais référencer ma création de base de données et y enregistrer mes requêtes importantes

N'ayant aucune idée de si c'est la meilleure structure au long terme, elle va cependant m'aider dans un premier temps à réaliser mon docker-compose, et me permettre de séparer la vue du back.

J'ai ainsi installé vue.js dans le dossier ´frontend´, et testé qu'il fonctionne correctement via les commandes :
´´´
npm create vue@latest
cd ./frontend/
npm install
npm run dev
´´´

Une fois vérifié et accédé, j'ai opté pour la même option, mais via docker, en configurant un docker-compose simple qui comprend le lancement de PostgresDB en plus de l'environnement Vue.js

## 2. Création de la base de données

Le fichier openapi.yml donne une indication très claire de ce qui est attendu de l'API REST, et ainsi de la structure de la base de donnée attendu. 
