Fork
===

Fork est un petit framework basique développé par Kevin Traini. Il vous permettra de développer des sites simples.

___

Tutoriel
===
### Sommaire
- [Chapitre 1 : Le routeur](#chapitre-1--le-routeur)
- [Chapitre 2 : Les contrôleurs](#chapitre-2--les-contrleurs)
- [Chapitre 3 : Les templates](#chapitre-3--les-templates)
- [Chapitre 4 : La base de données & autres](#chapitre-4--la-base-de-donnes--autres)

___

### Chapitre 1 : Le routeur

Fork comme la plupart des framework utilise un routeur pour renvoyer le résultat des requêtes. Dans notre cas il s'agit d'un routeur assez simple mais suffisant pour les petits sites.

Pour ajouter, modifier et enlever des pages il suffit de modifier la variable `$router` qui se trouve dans le `index.php`.
```php
$router = [
    '/' => [
        'name' => 'home',
        'controller' => (new HomeController())->homepage(),
    ],
];
```
Cette variable est donc un tableau qui associe des requêtes (`/` ici) à un second tableau. Dans ce deuxième tableau on a besoin de 2 lignes :

- `name` Le nom de la route. Nécessaire pour les redirections
- `controller` La fonction associée

La fonction associée est celle qui sera appelée quand cette route sera demandée.

Pour ajouter une route, il suffit donc de rajouter une entrée dans ce tableau et de créer la fonction associée.

### Chapitre 2 : Les contrôleurs

La fonction que vous allez renseigner dans le routeur, doit être situé dans un contrôleur. Un contrôleur est une classe php avec des fonctions qui doivent retourner une instance de `Response`, `TemplateResponse` ou `RedirectResponse`.

- `Response` est utilisé pour renvoyer de simples chaines de caractères, il peut s'agir d'html, xml, yaml, ...
- `TemplateResponse` est pour les cas ou vous utilisez des templates (voir [Chapitre 3](#chapitre-3--les-templates)). C'est le cas pour la page de base :
```php
namespace Controller;

use Fork\Response\TemplateResponse;

class HomeController
{
    public function homepage()
    {
        return new TemplateResponse('view/home/homepage.php');
    }
}
```
- `RedirectResponse` redirige vers la route dont on a spécifié le nom

Les contrôleurs se situent dans le dossier `src/Controller`

### Chapitre 3 : Les templates

Dans ce chapitre nous allons approfondir le système de templates.

Vous utilisez les templates dès que votre contrôleur renvoie une instance de `TemplateResponse`. Cette classe prend en paramètre dans son constructeur, le chemin vers votre template.
Comme les exemples valent plus que les longs textes, en voici un :

Dans le contrôleur :
```php
return new TemplateResponse('view/home/homepage.php');
```
Dans le fichier `homepage.php` :
```php
<?php $title = 'Fork'; ?>

<?php ob_start(); ?>

    <div>
        <img src="resources/img/fork.svg" alt="Fork"/>
        <h1>Fork</h1>
        <p>Page de base du framework</p>
    </div>

<?php $content = ob_get_clean(); ?>

<?php require_once 'view/base.php'; ?>
```
Dans le fichier `base.php` :
```html
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <title><?= $title ?></title>
    <link rel="shortcut icon" href="resources/img/fork.svg"/>

    <link rel="stylesheet" href="resources/css/base.css"/>
</head>

<body>
<?= $content ?>
</body>
</html>
```
Un peu d'explication quand même. Votre contrôleur appelle le template `homepage.php`. Dans ce fichier, il y a 3 (non 4) lignes qui nous intéressent :

1. `<?php $title= 'Fork'; ?>`
2. `<?php ob_start(); ?>`
3. `<?php $content = ob_get_clean(); ?>`
4. `<?php require_once 'view/base.php'; ?>`

La première ligne est une affectation de la variable `$title`, on en sera plus après. La seconde et la troisième ligne vont ensemble : les méthodes `ob_start()` et `ob_get_clean()` (voir [Fonctions de bufferisation de sortie](https://www.php.net/manual/fr/ref.outcontrol.php)) permettent de stocker une grande chaine de caractères dans une variable.
À quoi sert cette variable ? Nous le verrons après. Et enfin la quatrième ligne (la plus intéressante), elle inclue le fichier `base.php`.

Dans ce fichier il y a 2 lignes qui nous intéressent :

1. `<?= $title ?>`
2. `<?= $content ?>`

Ces 2 variables (`$title` et `$content`) sont celle dont on a modifié la valeur précédemment. Ce qui va se passer est tout simplement qu'à la place de ces 2 lignes, on va avoir tout ce qui a été mis dans le fichier `homepage.php`.
C'est ainsi que les templates fonctionnent.

Vous définissez une base qui sera commune à toutes vos pages avec des variables aux endroits ou le contenu va changer. Vous donnez une valeur à ces variables dans un autre fichier qui inclue le premier. Et le tour est joué.

### Chapitre 4 : La base de données & autres

Dans ce dernier chapitre nous allons voir comment interroger votre base de données et 2 autres classes qui vous aideront.

Fork implémente une série de classes qui vous permettront de facilement manipuler une base de données mysqli. Tout d'abord il faut donner les identifiants pour se connecter à la base de données. Cela se fait dans le fichier `config/config.yml` :
```yaml
database:
  credentials:
    host: "127.0.0.1"
    user: "root"
    password: ""
    dbName: "likaton"
    port: 3306
```
Une fois ces données rentrées, vous avez fait le plus grand, il ne vous reste plus qu'à faire toutes les fonctions dont vous avez besoin pour manipuler votre base de données.

C'est là qu'interviennent 2 autres classes : `Query` et `PreparedQuery`. La première, basique, vous permettra d'envoyer des requêtes et de récupérer le résultat si besoin.
```php
$result = (new Query('SELECT * FROM user'))->getResult();
(new Query('INSERT INTO user (login, password) VALUES (\'root\', \'rootpassword\')'))->execute();
```
La seconde vous permet de préparer des requêtes avec des paramètres.
```php
$query = new PreparedQuery('SELECT * FROM user WHERE login = ?');
$query->setString('root');
$result = $query->getOneOrNullResult();
```

Les 2 autres classes qui vont vous aider sont `Cookie` et `Session`. Elles vous permettront de manipuler plus facilement les variables de sessions et les cookies.