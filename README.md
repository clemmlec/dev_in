
![GitHub top language](https://img.shields.io/github/languages/top/clemmlec/dev_in)
![GitHub language count](https://img.shields.io/github/languages/count/clemmlec/dev_in)
![GitHub language count](https://img.shields.io/github/last-commit/clemmlec/dev_in?label=Last%20Commit)
![GitHub language count](https://img.shields.io/github/commit-activity/m/clemmlec/dev_in?label=Commit%20Activity)

Dev In est un site d'information et d'échange pour les développeurs web et présenté pour le passage du titre de développeur web FullStack.

![Bannière](https://github.com/clemmlec/dev_in/blob/master/img/dev_in_home.png)

[Instalation](#instalation)

## Présentation et fonctionalités



## Instalation

Prérequis pour les stacks :
![GitHub language count](https://img.shields.io/badge/Php-8.1-brightgreen?style=flat&logo=php&color=787CB5")
![GitHub language count](https://img.shields.io/badge/Node-18.0-brightgreen?style=flat&logo=nodedotjs&color=3C873A)
![GitHub language count](https://img.shields.io/badge/Yarn-4.0-brightgreen?style=flat&logo=yarn&color=25799f)
![GitHub language count](https://img.shields.io/badge/Mysql-8.0.25-brightgreen?style=flat&logo=mysql&color=00758F&logoColor=F29111)

  - PHP Version 8.1 Installé en local
  - MySQL Installé en local
  - Installer Composer
  - Installer Yarn
  
 Installation
 
  Après avoir cloné le projet éxécutez les commandes suivante dans un terminal à la racine du dossier : 

    composer install  ( installer les dépendances composer du projet )

    yarn install      ( installer les dépendances yarn du projet )

  Ensuite installer la base de donnée MySQL et paramétrer la création de votre base de donné :
  
  - dans le fichier .env du projet modifier la variable d'environnement selon vos paramètres :
    
      DATABASE_URL="mysql://"login":"password"@127.0.0.1:3307/"nom_de_la_base"?serverVersion=5.7&charset=utf8mb4"

  Puis créer la base de donnée avec la commande : 
  
    php bin/console doctrine:database:create

  Exécuter la migration en base de donnée : 
    
    php bin/console doctrine:migration:migrate

Pour lancer le server local :

    symfony server:start
  
  
