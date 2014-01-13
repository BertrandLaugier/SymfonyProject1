Symfony Projet 1 - REST API
==================


Présentation
-------------

Ce projet se base sur le projet codé pendant la semaine 1 sur Symfony, c'est à dire un système simple d'article et d'utilisateurs.
Le but de se projet et de changer le controller de article afin de construire les différentes méthodes du CRUD en REST.


Sources
------------

Pour m'aider, j'ai lu beaucoup de documentaton sur le net, notamment l'article écrit par [William Durand](http://williamdurand.fr/2012/08/02/rest-apis-with-symfony2-the-right-way/).

J'ai aussi testé quelques projets Git sur la structure en REST sans réel succès notamment l'architecture proposé dans la documentation du bundle FOSRestBundle:

https://github.com/gimler/symfony-rest-edition



Bundles utilisés
-----------------

Pour réaliser ce projet, j'ai utilisé principalement deux bundle :

le [FOSRestBundle](https://github.com/FriendsOfSymfony/FOSRestBundle)
[NelmioApiDocBundle](https://github.com/nelmio/NelmioApiDocBundle) qui permet de voir facilement les retours des différentes méthodes REST


Utilisation
-------

Pour utiliser ce projet il faut se rendre vers l'url de type 'monUrlDeProjet/web/app_dev.php/api/doc'

On a juste a remplir les différentes champ du formaulaire (id, title, content) pour obtenir, éditer, ou créer des articles.


Difficultés et problèmes
-------

Les principales difficultés on été de comprendre le fonctionnement de l'architecture REST et son utilisation avec Symfony.

Quelques petits bugs m'on ralenti n'étant pas encore très familier avec Symfony.



Users et logins
-------

dans le dossier projet se trouve un dossier sql comprenant un fichier sql permettant de commencer avec une petites base d'articles et d'utilisateurs

name : Bob
password : password


Précision d'utilisation
-------

Afin de bien utiliser l'API REST, lors des requetes passé avec le bundle NelmioApiDoc ( sur la page /api/doc), il faut préciser en tant que header :
Accept = application/json