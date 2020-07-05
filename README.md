Fork
===

Fork est un petit framework basique développé par Kevin Traini. Il vous permettra de développer des sites simples.

___

Tutoriel
===

Le routeur
---
Fork utilise un routeur tout simple pour renvoyer les résultats des requêtes. Pour ajouter, enlever ou modifier des pages il suffit de modifier la variable `$routeur` qui se trouve dans le `index.php`.

Voici à quoi elle ressemble de base :
```php
$router = [
    '/' => [
        'name' => 'home',
        'controller' => (new HomeController())->homepage(),
    ],
];
```
Cette variable est donc un tableau qui associe des requêtes  à un second tableau. Dans ce deuxième tableau on a besoin de 2 lignes :

- `name` Le nom de la route
- `controller` La fonction associée

Le nom de la route n'est pas obligatoire mais est recommandé si vous voulez utiliser des redirections. Par contre la fonction est obligatoire, c'est elle que le routeur va utiliser pour afficher votre contenu.

Pour ajouter une nouvelle route, il suffit donc de rajouter une entrée dans ce tableau, et de créer la fonction.

Le controlleur
---
Les fonctions que vous devez renseigner dans le routeur sont contenues dans les controlleur. Un controlleur est une classe php avec des fonctions qui doivent retourner un objet de type `Response`, `TemplateResponse` ou `RedirectResponse`.

`Response` va juste écrire la chaine de caractères que vous lui aurez passé.

`TemplateResponse` prend en paramètre le chemin vers un fichier php ou html qu'il va ensuite afficher dans votre navigateur. C'est la cas pour la page de base :
```php
class HomeController
{
    public function homepage()
    {
        return new TemplateResponse('view/home/homepage.php');
    }
}
```
Enfin `RedirectResponse` prend en paramètre le nom d'une route et redirige vers cette route.

Les templates
---
Quand votre controlleur renvoie une `TemplateResponse`, vous utiliser un fichier php ou html qui se situe dans le dossier `view`.

Pour utiliser toute la puissance des templates, vous pouvez personnaliser le `base.php` :
```php
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title><?= $title ?></title>
    <link rel="shortcut icon" href="resources/img/fork.svg" />

    <link rel="stylesheet" href="resources/css/base.css" />
</head>

<body>
<?= $content ?>
</body>
</html>
```
Vous créez votre page comme à chaque fois. Mais pour les morceaux qui vont différer entre charque page utilisez `<?= $variable ?>`.

Ensuite dans le fichier que vous allez renseigner dans le controlleur vous donnez des valeurs à chacune des variables, et à la fin du fichier vous rajouter un `require` du template.
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

Les méthodes `ob_start()` et `ob_get_clean()` permettent de rentrer une grande chaine de caractères dans une variable, voir [Fonctions de bufferisation de sortie](https://www.php.net/manual/fr/ref.outcontrol.php).

La base de données
---
Bien évidemment vous pouvez utiliser une base de données sur votre site. Pour cela vous devez aller modifier la fonction `connect()` de la classe `Database`. Vous pourrez y renseigner les données pour se connecter à votre base de données.

Il vous suffira ensuite de créer dans le dossier `src` toutes vos fonctions pour la manipulation de vos données.
