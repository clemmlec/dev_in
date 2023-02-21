
![GitHub top language](https://img.shields.io/github/languages/top/clemmlec/dev_in)
![GitHub language count](https://img.shields.io/github/languages/count/clemmlec/dev_in)
![GitHub last commit](https://img.shields.io/github/last-commit/clemmlec/dev_in?label=Last%20Commit)
![GitHub commit activity](https://img.shields.io/github/commit-activity/m/clemmlec/dev_in?label=Commit%20Activity)
<!-- ![GitHub release (latest SemVer)](https://img.shields.io/github/v/release/clemmlec/dev_in?display_name=tag) -->

Dev In est un site d'information et d'échange pour les développeurs web et présenté pour le passage du titre de développeur web FullStack.
<br>
  - [Présentation](#présentation-et-fonctionalités) <br>
  - [Instalation](#instalation)

![Bannière](https://github.com/clemmlec/dev_in/blob/master/img/dev_in_home.png)
<img src="https://github.com/clemmlec/dev_in/blob/master/img/dev_in_home.png">
## Présentation et fonctionalités
  - [Les articles](#les-articles) <br>
  - [Les sujets](#les-sujets) <br>
  - [Crédibilité des utilisateurs](#crédibilité-des-utilisateurs) <br>
  - [Personalisation de la bannière de profil](#personalisation-de-la-bannière-de-profil) <br>
  - [Coté Admin](#coté-admin) <br>

  ### Les articles
![Articles sur Dev In](https://github.com/clemmlec/dev_in/blob/master/img/articles.png)
L'administrateur du site écrit des articles sur les languages et tecnhologies autours du développement web.<br>
Les utilisateurs connéctés peuvent les ajouter en favoris pour les retrouver plus facilement (icone étoile) et faire des suggestions (icone ?).<br>
L'ajout en favoris et les signalements sont envoyé au controlleur PHP par le biais d'Axios en Javascript pour éviter le rechargement de page.<br>
Ici les résultats sont listés par groupe de 20 articles et le rechargement se fait en AJAX avec la mise ene place d'un scroll infini.

  ### Les sujets
![Sujets sur Dev In](https://github.com/clemmlec/dev_in/blob/master/img/sujets.png)
Les sujets sont postés par la communauté.<br>
Ici aussi les utilisateurs connectés peuvent les liker, mais aussi les signaler en cas de contenu indésirable. <br>
En allant sur le detail du sujet en question, l'utilisateur poura lire les commentaires et en poster :<br>
![Sujet sur Dev In](https://github.com/clemmlec/dev_in/blob/master/img/sujet.png)<br>
Mais également les noter : <br>
![Note des sujets sur Dev In](https://github.com/clemmlec/dev_in/blob/master/img/note.png)
Les notes vont de 1 à 5. On affiche 5 étoiles coté TWIG si l'utilisateur n'a pas encore effectué de note pour le sujet et si il n'en est pas l'auteur.<br>
Une première verification est faite en Javascript (note bien comprise entre 1 et 5) et une deuxième verification est faite dans le controlleur (le sujet est il déjà noter, est ce un sujet de l'utilisateur) avant d'envoyer la note en base de données.

  ### Crédibilité des utilisateurs


Coté administrateur, on liste les signalements fait pour les commentaires et les sujets.<br>
Dès lors un systeme de crédibilité est mise en place pour aider à la modération.<br>
Quand un sujet ou commentaire est supprimé coté administrateur après un signalement, l'auteur du contenu indésirable perd un point de crédibilité et à l'inverse, les utilisateurs l'ayant signalé en gagne un.<br>
Si un signalement est jugé abusif, son auteur perd un point à sa suppression.<br>
<br>
Dès lors, un utilisateur ayant une forte crédibilité (plus de 20) aura plus d'impact sur la modération : <br>
![Signalement des sujets sur Dev In](https://github.com/clemmlec/dev_in/blob/master/img/moderation1.png)<br>
Une fois le signalements envoyé, le sujet concerné sera automatiquement passé en non actif en base de données : <br>
![Signalement des sujets sur Dev In](https://github.com/clemmlec/dev_in/blob/master/img/moderation2.png)<br>
Et ainsi il ne sera plus affiché sur le site.<br>
Toute fois l'administrateur garde le controlle en pouvant le repasser en actif en cas de signalement abusif.<br>
<br>
Un utilisateur ayant par contre une mauvaise crédibilité (moins de 10) ne pourra plus poster de sujet, commentaire ou de note. On affiche alors un message : <br>
![Signalement des sujets sur Dev In](https://github.com/clemmlec/dev_in/blob/master/img/trollSujet.png)<br>
L'utilisateur aura alors une seulle voix pour pour améliorer sa crédibilité: faire des signalements justifié.

  ### Personalisation de la bannière de profil
![Bannière de profil sur Dev In](https://github.com/clemmlec/dev_in/blob/master/img/profilCouleurs.png)<br>

Les utilisateurs peuvent changer leur couleur de bannière de profil.<br>
Pour cela quelques fonctionalités sont mise en place en Javascipt pour afficher différentes couleur aléatoirement. Et on affiche des boutons pour selectionner nos options de couleurs et pouvoir choisir des couleurs de dégradé, de bordure, ...

On obtiens avec ça une originalité dans les profil utilisateur : <br>
![Utilisateurs de profil sur Dev In](https://github.com/clemmlec/dev_in/blob/master/img/users.png)<br>

  ### Coté Admin
![Admin sur Dev In](https://github.com/clemmlec/dev_in/blob/master/img/admin.png)<br>

Sur la page d'accueil de la partie Admin, on affiche trois graphique grâce à la librairie Javascript open-source chart.JS pour afficher les derniers utilisateurs inscrits au cours de la semaine, afficher les signalements et suggestions et le nombre d'articles postés par catégories.



## Instalation

Prérequis pour les stacks :
<br>
![GitHub language PHP](https://img.shields.io/badge/Php-8.1-brightgreen?style=flat&logo=php&color=787CB5")
![GitHub language NODE](https://img.shields.io/badge/Node-18.0-brightgreen?style=flat&logo=nodedotjs&color=3C873A)
![GitHub language YARN](https://img.shields.io/badge/Yarn-4.0-brightgreen?style=flat&logo=yarn&color=25799f)
![GitHub language MySQL](https://img.shields.io/badge/Mysql-8.0.25-brightgreen?style=flat&logo=mysql&color=00758F&logoColor=F29111)

 
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
  
  
