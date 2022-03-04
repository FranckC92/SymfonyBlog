
# Cours Symfony

* Initiation
* Intermédiaire
* Perfectionnement
* Projet

## Table des matières

[TOC]

## MVC

Symfony est un framework PHP orienté objet, qui a une architecture de type "MVC"

MVC est l'abréviation de "Model", "View", et "Controller", qui sont les trois types de "Design Pattern" (ou "Patron de Conception") lesquels sont utilisés conjointement afin de réaliser l'architecture de notre framework.

Un Design Pattern/Patron de Conception est un arrangement caractéristique de modules, reconnu comme bonne pratique en réponse à un problème de conception de logiciel.
Le Design Pattern/Patron de Conception décrit une solution standard, utilisable dans la conception de différents logiciels. C'est un formalisme conçu pour répondre à des problèmes particuliers avec une méthode prédéfinie.

La structure de Symfony est donc basée sur un paradigme Model-View-Controller, ou MVC. La structure MVC est une structure très utilisée dans le cadre de développement de logiciels possédant une interface graphique.

## Comment installer Symfony?

Pour préparer notre projet Symfony, nous allons avoir besoin de deux applications:
* **Symfony CLI (Command Line Interface)** est une application qui nous permettra de pouvoir effectuer différentes opérations nécessaires pour notre application Symfony mais qui ne sera PAS notre projet. Symfony CLI nous permettra de générer de nouveaux projets mais également de lancer un serveur pour notre application.
* **Composer** est une application qui nous permettra de télécharger des modules utiles à notre application Symfony. Etant donné que notre projet de base est une collection de modules téléchargés via Composer, il nous faudra installer Composer avant d'installer Symfony CLI.

Dans l'ordre:
1. **Installer Composer** => http://getcomposer.org (ne PAS faire developer mode)
2. **Installer Symfony CLI** => http://symfony.com/download (cliquer sur amd64 pour Windows)

Nous créons un nouveau projet via la commande:

```bash
symfony new --full --webapp MonProjet # dans notre cas "SymfonyBlog"
```

A la suite de cette commande, un nouveau projet Symfony sera téléchargé et configuré à l'intérieur du dossier actif dans le terminal.

## Structure

Notre projet Symfony comporte différents fichiers et dossiers générés au cours de l'installation.
A la racine, le fichier **.env** a pour objectif de déterminer nos variables d'environnement. Nous allons surtout l'utiliser pour déterminer l'état de notre projet, via la variable APP_ENV, dont la valeur sera "dev" pour Développement, ou "prod" pour Production. Dans notre cas, notre projet est en cours de dévelopement, et nous n'avons donc pas besoin de toucher à cette variable.
Le fichier .env permet également de configurer notre connexion à notre base de données, en spécifiant notre système de gestion choisi (dans notre cas, MySQL) et les différents éléments nécessaires à la connexion tels que l'adresse et le port de connexion, le nom d'utilisateur, etc.

Le dossier **bin** contient deux fichiers, console et phpunit. Il s'agit d'un dossier que nous n'allons pas modifier mais qui est d'une grande importance car il contient les différentes commandes utilisées par le terminal pour notre projet.

Le dossier **config** contient plusieurs fichiers de configurations, souvent sous le format .yaml, lesquels sont nécessaires à la personnalisation et aux différents réglages de Symfony. La majorité des modifications de nos fichiers de configuration étant automatisée, nous n'aurons à changer le contenu de ces fichiers que de manière occasionnelle. On peut donc ici configurer la manière dont sont administrées nos routes, les bibliothèques Twig et Doctrine, etc.

Le dossier **migrations** est lié à la bibliothèque Doctrine, et se composent de fichiers classe PHP servant d'intermédiaires dans le cadre de la structuration et modification de notre base de données (nombre tables et leur structure). Les migrations étant automatiquement gérées par Doctrine, nous aurons rarement à directement manipuler ces fichiers.

Le dossier **public** est le seul dossier accessible aux requêtes client. Cela signifie que c'est le seul dossier que l'utilisateur sera capable d'accéder du côté Client. Le dossier public contient le fichier index.php, lequel est le point d'entrée vers notre application et lance toute l'application Symfony lorsque appelé (à chaque connexion).
Ce sera aussi dans le dossier public que devront être placés les différents fichiers que nous désirons rendre disponibles à l'utilisateur, tels que les images ou vidéos, mais également les fichiers CSS et JavaScript.

Le dossier **src** contient trois sous dossiers, contenant deux des éléments coeur de notre application: les aspect Controller (via le dossier controller) et Model (via les dossiers entity et repository).

Le dossier **Controller** possède les classes dites de type Controller. Dans le cadre de notre premier projet, la grande majorité de la logique PHP de notre application que nous allons écrire sera placée dans les fichiers constituant ce dossier controller.

Les dossiers **Entity** et **Repository** sont liés et sont deux dossiers accessoires à l'ORM Doctrine, qui est une bibliothèque chargée de la gestion de notre base de données. Le dossier Entity possède plusieurs classes PHP nommées Entities (ou Entités), lequels seront utilisées par Doctrine pour générer des tables du même nom au sein de notre base de données. Les classes Repository seront ensuite utilisées par Doctrine afin de mener des recherches automatiques dans notre base de données.

Le dossier **templates** contient les différents fichiers écrits selon le langage de template Twig, dont le but et de servir de base pour générer des pages web à envoyer à l'utilisateur, côté Client.

Les dossier **tests** et **translations**, comme leur nom l'indique, sont liés à des procédures de test et de traduction pour notre application. Dans le cadre de ce cours, nous n'allons pas y toucher.

Le dossier **var** contient deux sous-dossiers, cache, et log. Le dossier cache contient les différents fichiers temporaires générés par notre application afin d'accélerer son fonctionnement. Le dossier Log contient différentes infromations générées automatiquement qui pourraient être utiles en cas de test ou déboggage. En cas de mise à jour de notre site, il est recommandé de vider le cache afin de générer de nouveaux fichiers temporaires à jour des changements récents, via la commande suivante:
  php bin/console cache:clear

Le dossier **Vendor** contient toutes les bibliothèques extérieures à notre application, dont Doctrine, Twig, et Symfony. Par convention, un dossier Vendor est un dossier qui contient toutes les bibliothèques extérieures à une application. Ce dossier étant automatiquement géré et mis à jour, nous n'avons pas à y toucher.

A présent que nous avons vu la structure de notre projet, nous allons nous intéresser à l'architecture dite MVC de Symfony. MVC signifie donc Model, View, Controller (Modèle, Vue, Contrôleur), mais que signifie chaque élément:

**Controller:**

Le Controller/Contrôleur regroupe tous les programmes PHP nécessaires au bon fonctionnement de notre application. Le Controller n'est pas seulement la classe PHP Controller appelée ou la fonction correspondant à la route que nous avons requise, mais désigne le processus entier d'appel aux différentes bibliothèques et aux différents services nécessaires au bon traitement des données. Le Controller est donc le coeur de notre application.

**Model:**

Le Model/Modèles regroupe toutes les données employées par notre application. Il s'agit des données persistantes, appelées comme telles car leur existence persiste au-delà d'une simple session utilisateur. Dans notre cas, nous allons surtout nous intéresser aux données persistantes stockées dans notre base de données. Tout ce qui est information propre à être traitée par notre application et à être envoyée au Client est Model: il s'agit de la maitère de l'application, que le Controller a la tâche de distribuer selon les requêtes clients.

**View**

La View/Vue regroupe tous les fichiers nécessaires à la génération de notre page web à retourner en réponse à la requête client. Si le Model contient la matière de notre application Symfony et les données susceptibles d'intéresser l'Utilisateur, le but de la vue est de présenter cette information de manière efficace et intelligible, via une interface graphique.
La View est idéalement composée de plusieurs fichiers regroupés dans le dossier Templates, offrant les templates/gabarits nécessaires pour expliquer et contextualiser les informations du Model récupéré sur décision du Controller. Le code PHP est laissé aussi minimal que possible dans ces fichiers de vue, dans l'idéal de séparation des rôles.
Nous allons utiliser un langage dit de Template, Twig, qui a pour but de simplifier la maigre part de PHP et de rendre les templates/gabarits aussi faciles à lire et flexibles que possible.

# Semaine 1

## Notre IndexController

Si index.php est le point de départ de la requête client vers le lancement de notre application, les classes .php de Controller sont où nous, en tant que développeurs, commençons le développement de notre application.

Dans le cadre de ce premier projet, nous n'allons utiliser qu'une classe classe Controller, IndexController. Cependant, nous pouvons avoir autant de classes Controller que nous le désirons. Chaque méthode annotée de nos Controllers sera prise en compte peu importe le nom de la classe dans laquelle elle se trouve. Quelle devrait être la taille (en terme de nombre de méthodes) de chaque Controller? Il n'y a pas de réponse, mais il veut créer un controller par thème et/ou fonction.
Par thème, imaginons un pan du site dédié à un aspect particulier du service proposé à l'utilisateur. Par exemple, la gestion d'un catalogue proposé à l'utilisateur peut être un thème, et la personnalisation de son profil un autre.
Par fonctionnalité, il faut entendre un Controller entièrement dédié à la gestion correct de certains types de données. Par exemple, dans le cadre d'un site e-commerce, on peut imaginer un ProductController entièrement dédié à la gestion, l'achat, et la livraison de produits proposés à la vente.

Mais dans le cadre de notre projet Blog, seul un IndexController sera nécessaire, qui prendra en compte à la fois la gestion des articles ainsi que leur affichage.

Chaque méthode de notre Controller peut être divisée en quatre segments d'importance comparable, chacun gérant un aspect de la préparation d'une réponse à la requête client :

* **Les annotations**, qui prennent en charge le routage (entre autres).
* **La déclaration de la méthode**, donc son nom, son statut (public), et le nombre de paramètres/arguments pris en charge.
* **Le coeur de la méthode**, à savoir la liste des instructions effectuées.
* **Le retour/return de la méthode**, qui dans notre Controller rendra une instance de la classe Response sous la forme d'une chaine de caractères si nous créons l'objet Response nous-même, mais plus souvent sous la forme d'un résultat plus complexe obtenu à partir d'un template Twig.

La commande de création de Controller est :

```bash
php bin/console make:controller # optionnel: NomDuController
```

Les annotations de notre Controller sont placées en amont de la fonction, avec les caractères suivants :

```
/**
 * @Route("/route", name="nom_route")
 */
```

OU

```
#['/route', name: 'nom_route']
```

La seconde syntaxe est la plus récente et est utilisée automatiquement depuis PHP 8. Il s'agit donc de celle que nous sommes le plus susceptible d'utiliser, mais il n'existe pas de différences entre les deux types d'annotations.
A noter que le premier type utilise des guillemets doubles "" tandis que le second utilise des guillemets simples ''. On ne peut pas choisir un autre type de guillemet que celui utilisé par la syntaxe sous peine d'erreur.

Les annotations permettent de placer des informations relatives à la fonction/méthode qu'elles précèdent, mais dans la cas de notre controller, ils permettent de déterminer la route qui mène à notre fonction. La route est un moyen, par la réception d'une requête client via URL, de déterminer à quel méthode de Controller l'application va faire appel.

Notre route est spécifiée par @Route (ou un # suivi de crochets), puis par l'entrée de deux paramètres:

```
#['/general', name:'route_generale']
```

Le premier paramètre nous permettra d'indiquer quelle est l'URL à entrer pour parvenir à cette fonction, tandis que le second paramètre est le nom de la route attribuée à cette fonction. Si le premier paramètre est nécessaire pour les requêtes client, le nom attribué à la route sera celui qui sera plus le utile côté développeur, étant donné que la génération automatique d'URL nécessitera l'entrée du nom de la route correspondante.

Il existe une commande spéciale pour obtenir la liste de toutes les routes actives sur notre application via le terminal:

```
php bin/console debug:router
```

## Les Paramètres de Route

Nous pouvons définir des paramètres de route, lesquels seront transmis à notre fonction et seront donc susceptibles d'être intégrés à la logique algorithmique.
Le paramètre est indiqué via des accolades (par exemple, {squareValue}). La valeur indiquée par la requête client à la place de ce segment sera récupérée et placée à l'intérieur d'une variable du même nom (par exemple ici, $squareValue). Nous pouvons alors reprendre cette valeur et l'utiliser comme bon nous semble.
Nous pouvons également attribuer une valeur par défaut à notre paramètre de route dans les paramètres de notre fonction. Ainsi, nous pourrons laisser notre paramètre non renseigné sans avoir d'erreur de type 404.

```php
squareColor()
```

1- Créer la route
2- Trois résultats sont possible pour le paramètre de route:
* Les résultats: Rouge, Vert, Bleu, Jaune
* Le résultat par défaut: (un carré noir)
* Le résultat en cas d'un sqColor non renseigné (carré gris)

## Twig

### Découverte de Twig

Lorsque nous terminons notre méthode de Controller, nous lui indiquons quel type d'objet de type Response rendre. En tuilisant $this->render, nous indiquons à Symfony que nous allons transiter par Twig afin de générer la Response que nous désirons envoyer à la requête client.

Twig est avant un langage de template. C'est un langage visant à alléger le code de notre page web en remplaçant tout le PHP par une série de balises à l'apparence plus légère (donc les balise à {{ double accolade }} ou les balises à {% accolade-pourcentage %}).
A noter qu'il est possible d'installer Symfony sans utiliser Twig, et il est également possible d'utiliser Twig sur un projet qui n'emploie pas Symfony. Cependant, Twig est optimisé pour Symfony et dans sa configuration de base, Symfony installe Twig par défaut.

Twig est un langage qui se greffe au HTML. Chacun des instructions du langage de template sera effectué dans trois types de balise:

* *Les doubles accolades sont utilisées pour afficher le contenu d'une variable ou le résultat d'une expression*

```twig
{{ *** }}
```

* *L'accolade-pourcentage est utilisée dans le cadre des structures de contrôle (if, for, include, ou encore des déclarations d'héritage)*


```twig
{% *** %}
```

* *L'accolade-dièse sert à écrire des commentaires. Contrairement aux commentaires HTML, les commentaires Twig ne sont pas visibles depuis le code-source de la page.*

```twig
{# *** #}

<!--
  Ceci est un commentaire HTML (visible depuis le code source)
-->
```

Twig s'apprend rapidement et la documentation indique toutes les expressions nécessaires à la création d'un template. Le code étant très proche du PHP, passer de PHP à Twig s'effectue instantanément.

> Lien: https://twig.symfony.com/doc/3.x/


Twig adopte un système de blocs pour se structurer. En observant une page Twig, nous constatons rapidement qu'à l'exception de certaines pages telles que base.html.twig, le code de nos pages Twig est inséré à l'intérieur de blocs à la syntaxe suivante:

```twig
{% block A %}
  ...contenu du bloc...
{% endblock %}
```

```twig
{% block B %}
  ...contenu du bloc...
{% endblock %}
```

Comme nous pouvons le constater, la structure des accolades de ces blocs suit un schéma {% %} qui indique un type de structure de contrôle. Ces blocs sont conçus pour préparer un type d'héritage particulier de Twig.
Par héritage, il faut entendre la récupération du code d'une page-mère au profit d'une page-fille. La page Twig héritante récupère toute la structure de la page-mère avant de modifier le contenu des blocs présents sur cett page. C'est pourquoi il est impossible pour une page-fille de posséder un contenu qui ne serait pas situé à l'intérieur d'un bloc.

Ainsi, il est possible et recommandé de rédiger toute la structure de base de notre application web dans une page twig dédiée, telle que base.html.twig, et de réserver un bloc à l'intérieur de notre page, pour la page Twig héritante. Ainsi, il suffira pour la page Twig (que nous allons appeler via notre Controller) d'hériter de base.html.twig pour que le layout de base soit automatiquement repris tandis que notre page template héritante sera aussi simple que possible.

Attention, le contenu d'un bloc hérité n'affiche pas les valeurs saisies à l'intérieur du fichier parent à moins que le développeur n'en exprime le désir. Afin de pouvoir récupérer le contenu d'un bloc twig du fichier parent, il est nécessaire de faire appel à la fonction
  {{ parent() }}
Cette fonction récupère les instructions présentes à l'intérieur du bloc concerné du fichier parent et le recopie à l'emplacement de la fonction.

### Les Fonctionnalités Twig

**Les Filtres**

Les filtres sont placés après une barre/pipe (AltGt+6 -> |), dans une balise Twig à double accolade. Leur fonction est de transformer la valeur inscrite dans la double accolade en question.
{{ value|upper }} -> Transforme la chaine de caractères présente dans value en mettant chaque lettre en majuscule.
{{ text|nl2br }} -> "/n/l to <br>" remplace les newline (/nl) utilisées dans la base de données en retour à la ligne de type HTML (<br>)
Les filtres sont très utiles pour pouvoir modifier une valeur transmise via le Controller et l'adapter à l'interface présentée à l'utilisateur.

**Les Fonctions**

Les fonctions possèdent une logique semblable aux filtres mais possèdent une syntaxe plus classique. La fonction, en comparaison au filtre, a une action plus radicale en ce qu'elle transformela valeur ou le tableau présenté à une fin autre qu'un affichage plus aisé.

* **max()** retourne la valeur la plus élevée (les autres valeurs sont donc perdues)

```twig
{{ max([1, 2, 3]) }}
```

* **dump()** affiche le contenu d'une valeur avec quantité de détails et est très importance dans le processus de déboggage.

```twig
{{ dump(value) }}
```

**Les Tests**

Les tests sont des entrées spéciales, dans des balises à double accolades, ayant pour but de vérifier l'exactitude d'une information. Ils sont reconnus par l'usage du mot-clef "is"
{% if var is empty %} -> empty: vérifie si la variable concernée est vide, rend true dans ce cas (la balise {%%} est utilisée ici en raison de la structure de contrôle if).
{{ var is even }} -> even: rend True si "var" est un nombre pair.

### Trois Fonctions Twig particulières

**L'Inclusion de Vue**

L'Inclusion est une fonction à la fois inverse et complémentaire à l'héritage. Inversement à Extends, qui publie la page Twig à l'intérieur de la page héritée, Include() inclut une page Twig à l'intérieur de la page faisant appel à la fonction. Ainsi, Include fonctionne comme la fonction PHP require() (pas vraiment comment include() car si la page indiquée n'est pas trouvé, utiliser la fonction Twig résultera en une erreur).

```twig
{% include 'layout/header.html.twig' %}
```

Le contenu de la page header.html.twig sera automatiquement ajouté à l'emplacement de l'instruction.

**L'Incorporation des éléments du dossier Public, avec la fonction asset()**

Afin de pouvoir ajouter un élément nécessaire au bon fonctionnement du site et qui doit être directement accessible via une requête client, Twig possède une fonction **asset()** qui est un lien direct vers le contenu du dossier public

```twig
{{ asset('assets/css/bootstrap.min.css') }}
```

Rédigé ainsi, le bloc génèrera automatiquement un lien en partant du dossier public et qui pointera vers la ressource indiquée, ici, le fichier stylesheet bootstrap.min.css, rangé dans le dossier css, dans le dossier assets. Ceci permet d'assurer un chargement correct de la ressource, sans avoir à anticiper son emplacement sur le serveur.

**La fonction path()**

```twig
{{ path('app_index') }}
```

La fonction path() est une fonction Twig vitale au bon fonctionnement de notre application: son rôle est de générer un lien hypertexte vers une autre route de notre application web, selon le nom de la route qui a été rédigée dans les annotations de la méthode concernée.
La fonction path peut également prendre un second paramètre lequel est un tableau qui permet de transmettre des paramètres pour le bon fonctionnement des méthodes utilisant des segments optionnels de route (des paramètres de route tels que {sqColor}). A noter que la syntaxe Twig pour un tableau est légèrement différente d'une syntaxe PHP classique. En PHP, nous créons un tableau de la manière suivante:

```php
['name' => 'value']
```

Tandis que le tableau que nous allons utiliser avec la fonction path() fonctionne ainsi:

```twig
{{ path('app_index', {'parametre' : 'valeur'}) }}
```

Notre fonction path() ainsi rédigée récupèrera la valeur indiquée par la clef "parametre" et remplacera le segment correspondant par ladite valeur.

## Les Entities (Entités)

Une Entity (Entité) est une classe PHP au sein de Symfony qui définit ce que l'on nomme une Entity, dotée de plusieurs caractéristiques (à travers ses attributs), et qui sera une unité de base dans la gestion des Models de notre application. En pratique, une classe Entity sera convertie en table par Doctrine, et chaque élément conçu à partir de cette Entity sera une entrée de cette table.

En d'autres termes, une Entity est une classe PHP convertie en table par Doctrine, tandis que ses instances de classe (les objets) seront converties en entrées de cette nouvelle table SQL. Les Entities sont utilisées pour pouvoir conserver certains types d'informations dans un format standardisé, mais leur nature d'objet nous permet, contrairement à un simple tableau associatif, de pouvoir également utiliser ses méthodes pour modifier automatiquement ses attributs ou les formatter de manière à en faciliter la lecture.

Les Entities peuvent être générées à partir d'une commande via notre terminal. Cette méthode est recommandée afin de ne pas provoquer d'erreurs de syntaxe ou d'oubli:

```bash
php bin/console make:entity
```

Plusieurs questions seront alors posées via le terminal afin de pouvoir définir de manière complète notre Entity, avant de la générer automatiquement, par la création de deux classes PHP, une classe Entity et une classe Repository.

## L'Element Model, Doctrine, et les Migrations

L'Element Model d'une application comporte toutes les données sur laquelle notre application va travaille. La base de données est quant à elle une structure où nous allons classer des données prêtes à être rapidement extraites et utilisées de manière sécurisée pour le fonctionnement de notre application. Les données elles-mêmes sont classées dans des tables, qui réunies composent notre base de données.

De manière classique, l'échange entre notre application et notre base de données s'effectue via le langage SQL. C'est le cas en PHP classique, où nous utilisons le module PDO pour envoyer des instructions à notre Systeme de Gestion de Base de Données (SGBDD). Cependant, nous n'allons pas utiliser de SQL classique avec Symfony. Symfony utilise une bibliothèque particulière nommée Doctrine.

Doctrine est, comme Twig, indépendant de Symfony. C'est ce qu'on appelle un ORM (Object-Relational Mapping).

Les ORM, comme leur nom l'indique, sont des applications qui servent d'intermédiaire entre les structures de base de données relationnelles et les structures orientées objet. Ici, Doctrine fera en sorte que nous n'ayons pas à écrire nous-même notre code SQL et à réfléchir à traduire le fonctionnement classique de Symfony en une structure relationnelle classique. Doctrine interprétera le code de notre application et génèrera une séries d'instructions SQL adaptées pour le traduire de manière efficace. Ce processus étant automatisé, nous n'avons pas à nous en soucier. Ce qui nous intéresse est l'utilisation efficace de Doctrine et l'établissement correct d'une structure Model selon les règles de Symfony, via les Entités/Entities.

La première étape dans la configuration de Doctrine, si la bibliothèque est déjà installée (elle l'est par défaut dans l'installation classique de Symfony), est la modification du fichier .env à la racine de notre projet afin d'incorporer notre type de SGBDD, notre adresse et port de connexion, identifiant, et mot de passe. Une fois que cette étape est conclue, nous créons notre nouvelle base de données comptaible Doctrine avec la commande suivante:

```bash
php bin/console doctrine:database:create
```

Si tout se passe sans erreur, nous avons donc ici notre première base de données, à laquelle Doctrine peut accéder et interagir à loisir.

Ensuite, il faudra demander à Doctrine d'analyser nos classes et de générer une série de commandes SQL pour les traduire en tables relationnelles classiques. Pour cela, nous devons utiliser une procédure que nous appelons la migration.

Comme son nom l'indique, la migration est la transmission de notre classe Entity vers la base de données: il s'agit d'un déplacement d'un concept (Orienté Objet) vers un autre (Données Relationnelles). La classe Migration est une classe PHP ayant pour fonction de gérer ce processus. Elle est automatiquement préparée par Doctrine via la commande suivante:

```bash
php bin/console make:migration
```

Créer la classe ne suffit cependant pas à appliquer son contenu. Il faudra ensuite signifier à Doctrine d'appliquer les instructions générées par la classe via une seconde commande:

```bash
php bin/console doctrine:migrations:migrate
```

Le fichier de migration généré obtient un numéro de version basé sur l'heure et la date de sa création. Il possède trois méthodes, une description, **up()** et **down()**. Le but des méthodes **up()** et **down()** est de faire exécuter une série d'instructions par Doctrine, soit pour effectuer des changements, ou revenir à l'état antérieur.

Etant donné qu'à chaque nouvelle migration, une nouvelle classe est créée possédant les nouvelles instructions à transmettre, cela signifie qu'il est possible, à travers les méthodes **up()** et **down()** de mettre à jour comme de revenir à des versions antérieures de notre base de données.

La gestion de notre table est entièrement prise en charge par Doctrine: ce n'est pas à nous de nous en occuper. Ce qui nous intéresse est la gestion des Entity. Nous faisons appel à Doctrine pour récupérer des informations dont nous avons besoin pour récupérer des Entity que nous avons ajouté à notre base de données, et nous laissons à Doctrine le travail de manipuler ces données pour générer un objet Entity qui nous sera rendu. Il ne faut donc pas essayer d'effectuer des changements manuels à nos tables de notre BDD.

## Les Fixtures

Dans le cas d'une application Symfony dont le développement vient de commencer, les outils mis en place peuvent être limités pour le développeur: s'il désire tester ses bases de données sans avoir conçu de fonctions ou de formulaires permettant la mise en ligne rapide d'Entities, il peut cependant employer un autre moyen qui lui permettra de remplir sa base de données avec des informations temporaires: il s'agit des Fixtures.

Les Fixtures sont des entrées temporaires visant à tester le fonctionnement de notre application et de notre base de données. Ces "fausses informations" qui n'ont pas été créées pour être conservées mais qui ont pour but de tester les capacités d'affichage et de gestion des informations par notre application, rempliront rapidement la base de données de notre application en période de développement.

Nous devons installer le module de fixtures avant de pouvoir l'utiliser, via la commande suivante:

```bash
composer require --dev orm-fixtures
```

Le module de Fixtures crée un nouveau dossier nommé DataFixtures ainsi que des classes de type Fixture, dotées d'une méthode load(). Il va s'agir ici d'utiliser cette méthode afin de faire appel à l'Entity Manager, déjà injecté par défaut et ayant pour tâche de gérer les Entities et leur envoi vers la Base de Données, et placer des demandes de persistance pour les instance d'Entity que nous allons créer au sein de la méthode.

Pour reprendre les quatre étapes de la persistance de données en Symfony:
* Nous créons la nouvelle instance d'Entity via le mot-clef new
* Nous définissons notre instance d'Entity via le Constructeur et/ou les Setters
* Nous indiquons à Doctrine que nous souhaitons que cette instance soit conservée (persiste dans son existence) via la méthode persist()
* Nous demandons à Doctrine d'appliquer toutes les opérations que nous avons requises via la fonction flush()

Afin d'appliquer le contenu de nos méthodes load(), nous utilisons la commande suivante:

```bash
php bin/console doctrine:fixtures:load
```

Toutes les classes Fixture dans le dossier DataFixtures se terminant par Fixtures.php verront leur méthode load() appelée juste après que le contenu de la base de données soit purgée.

## Gestion de base de données via Doctrine:

A présent que notre base de données possède des entrées susceptibles d'être récupérées et utilisées par notre application, nous devons être capables de lancer des requêtes à notre base de données en ce sens. En PHP classique, nous utiliserions des requêtes SQL transmises via le module PDO. Dans le cadre de Symfony, nous devrons utiliser Doctrine.

Doctrine possède des fonctions prédéfinies pour récupérer des informations à partir d'une table. Etant donné que nous approchons nos données persistantes du point de vue des Entity et non des tables, nous devons transmettre à Doctrine une  requête de récupération des données d'une Entity désignée. Cette opération est effectuée grâce à une classe spécialisée en ce sens, qui est l'Entity Manager.

L'Entity Mananger est une classe qui nous permet de gérer le fonctionnement des Entity. Grâce à l'Entity Manager, nous pouvons atteindre la classe Repository, qui sera la classe qui nous permettra d'effectuer des requêtes de récupération des données. Le Repository est une classe spéciale contenant plusieurs méthodes détaillant divers critères de récupération de données persistantes (c'est-à-dire stockées dans notre Model, dont fait partie la base de données).

Ainsi, nous récupérons deux outils via deux variables: L'Entity Manager, qui nous permettra de récupérer les Repository et d'effectuer les requêtes de persistance et de suppression des Entities, et le Repository, qui possède les méthodes nécessaires pour retrouver les instances d'Entity correspondant à nos critères de recherche.

## Les Formulaires sous Symfony

Dans le cadre de Symfony, les formulaires sont un aspect très pris en charge et grandhement automatisé. Si, même en PHP/HTML classique, la rédaction et la prise en charge de formulaire sont un aspect essentiel des requêtes client et donc du fonctionnement général du site, en Symfony, cet aspect est encore davantage poussé grâce à la flexibilité du générateur de formulaire avec lequel il est possible de les spécialiser. Ce générateur est le Form Builder.

Le Form Builder est une classique Symfony dont l'instant prend plusieurs options afin de créer un formulaire corresponsant aux critères que se donne le développeur. Le Formulaire est ensuite automatiquement traité par Symfony et mis en relations avec l'instance de l'Entity concernée via l'utilisateion de simples fonctions telles que les handleRequest().
Etant donné que dans le cadre d'un site web en production, la majorité des informations vouées à remplir notre base de données viendra à nous via des requêtes client, le formulaire est un aspect essential de la construction du Model de notre application. L'usage du Form Builder est donc de première importance.

Il existe deux types de formulaires créés par le Form Builder, ceux créés par le développeur dans le cadre du Controller, et ceux générés de l'extérieur par une classe spécialisée (dont le nom s'achève par "Type"). Le second est ce qu'on appelle un formulaire externalisé et sera le principal type de formulaire employé par notre application.

La caractéristique des formulaire externalisés est non seulement qu'ils sont indépendants des Controllers mais également liés à des Entity (Il est possible de créer un formulaire externalisé non lié à une Entity mais nous ne en servirons pas). Le formulaire externalisé a donc pour avantage d'être facile à requérir à partir de la classe Form et d'être conçu directement sur mesure pour notre Entity. Mais il a pour défaut de ne pas être aussi flexible qu'un formulaire construit depuis notre Controller, qui par l'action du développeur est capable de s'adapter à une plus grande multitude de cas.

Le formulaire externalisé est généré à partir d'une commande de notre console:

```bash
php bin/console make:form
```

Il faudra renseigner le nom de notre classe Form ainsi que le nom de l'Entity à laquelle ce formulaire est lié (optionnel). La génération du fichier classe est immédiatement entreprise ensuite.

Le formulaire généré possède automatiquement autant de champs qu'il existe d'attributs dans notre Entity, id excepté. Le formulaire ainsi généré est cependant inutilisable du fait qu'il manque un champ/bouton de validation afin de soumettre le formulaire. C'est pourquoi il est nécessaire d'ajouter à la fin de la méthode de création du formulaire, via la méthode add(), un nouveau champ de type SubmitTYpe, qui fait référence à un bouton de validation.

> Il ne faudra pas oublié le use associé, qui est donc:

```php
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
```

Une fois ceci effectué, le formualire, si basique, reste utilisable et génèrera bien une requête complète et utilisable une fois rempli et validé. Il nous faudra ensuite créer une méthode au sein de notre Controller afin de prendre avantage de cette Request.

Une fonction du Controller gérant un formulaire doit donc implémenter l'objet Request dans ses paramètres, et utiliser les fonction du formulaire Symfony. La première fonction nécessaire à la génération du formulaire est $this->createForm(), lequel prend en paramètre le nom de la classe type associée (par exemple, BulletinType::class), ainsi qu'une instance de l'Entity liée.

Il suffit alors de générer la vue de notre formulaire via la méthode :

```php
$dataForm->createView()
```

et de passer le formulaire en paramètre vers le fichier Twig où la fonction Twig :

```twig
{{ form(dataForm) }}
```

Lorsque le formulaire est généré et rempli, nous allons devoir récupérer son contenu et le placer dans notre base de données. Les fonctions de formulaire de Symfony automatisent tout le processus. La fonction handleRequest($request) prend le contenu de la requête si celle-ci est valide avec le formulaire de l'Entity et applique les nouvelles valeurs à l'instance associée.

Une fois cette requête gérée, il est possible de vérifier si le formulaire est en l'état valide grâce à la méthode

```php
$form->isValid()
```

qui, conjointement à la vérification de la passation de la requête via la méthode POST, nous servira de condition 'if' pour préparer la passation des informations vers la base de données via l'Entity Manager.

```php
if ($request->isMethod('post')) { ... }
```

La persistence est donc assurée par l'Entity Manager. Il s'agit ici de deux processus absolument différents faisant appel à différents modules de Symfony, qui, bien qu'associés ensemble dans la même fonction de notre Controller, opèrent des rôles complémentaires et séparés.

Ceci signifie que nous sommes parfaitement capables d'opérer des contrôles et des modifications sur les données du formulaire avant leur mise en ligne sur des sujets qui ne concernent pas forcément la validité du formulaire, tel que par exemple, la vérification de l'absence de valeur déjà présente dans notre BDD en ce qui concerne la valeur de tel ou tel champ de notre formulaire.