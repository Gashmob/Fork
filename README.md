Fork
===

Fork est un petit framework basique développé par Kevin Traini. Il vous permettra de développer des sites simples.

___

Tutoriel
===
### Sommaire
- [Nouveau projet](#nouveau-projet)
- [Les routes](#les-routes)
- [Les templates](#les-templates)
- [Erreur 404](#erreur-404)


### Nouveau projet

Pour créer un projet, vous avez 2 options :
1. Télécharger l'archive du dépôt directement [(Releases)](https://github.com/Gashmob/Fork/releases)
2. Ou télécharger un exécutable shell qui créera le projet pour vous (à télécharger aussi dans la release).

Si vous choisissez l'option 1, il vous suffira d'extraire les fichiers dans le dossier de votre choix et c'est tout.
Si vous choisissez l'option 2, après avoir téléchargé l'exécutable, il faudra le remettre en exécutable via la commande :
```shell script
chmod +x fork
```
Vous pourrez ensuite le copier dans votre `/bin` ou `/usr/bin` :
```shell script
cp fork /bin
```
Une fois tout cela fait vous pourrez enfin créer votre projet via la commande :
```shell script
fork mon_projet
```


### Les routes

Fork est un framework qui comporte un routeur. Ce routeur sert juste à récupérer la route demandée (URL) et à renvoyer une réponse.
Mais vous n'avez pas besoin de vous en soucier. Tout ce que vous avez besoin de faire c'est de créer un contrôleur avec des méthodes.

Un contrôleur est une classe php qui hérite de la classe `AbstractController`. Vos contrôleurs doivent être situés dans le dossier `src/Controller/` avec le namespace `Controller`.
Vous avez déjà fait la moitié du boulot, il ne reste plus qu'à créer la méthode, en voici une :
```php
/**
 * @Route(route="/", name="home")
 */
public function homepage()
{
    return $this->render('home/homepage.html.twig');
}
```
Expliquons un peu. Cette fonction permet de dire quoi renvoyer pour une certaine route, vous renseigner quelle route via l'annotation `@Route()` qui prend 2 paramètres :
- La route
- Le nom de la route (pour les redirections)

Votre fonction doit ensuite renvoyer une valeur de type `Response` ou `RedirectResponse`, le plus simple est d'utiliser les méthodes qui sont déjà implémentées dans `AbstractController` :
- `render(string, array = [])` qui permet de renvoyer un [template](#les-templates)
- `text(string)` qui renvoie du texte brut
- `yaml(YamlArray)` qui renvoie du yaml, voir [YamlEditor](https://github.com/Gashmob/Yaml-editor) pour savoir utiliser `YamlArray`
- `redirectToRoute(string, array = [])` qui redirige vers la page demandée avec les arguments nécessaires

Car oui, vous pouvez passer des paramètres à vos fonctions, par exemple :
```php
/**
 * @Route(route="/redirect/{route}", name="redirect")
 * @param string $route
 * @return \Fork\Response\RedirectResponse
 */
public function redirect(string $route)
{
    return $this->redirectToRoute($route);
}
```
Ce qui permet de faire des routes avec des variables. Vous pouvez également récupérer les variables de sessions, les cookies ou encore la requête en les passant dans les paramètres :
```php
public function exempleWithParameters(\Fork\Request\Session $session, \Fork\Request\Cookie $cookie, \Fork\Request\Request $request) {}
```


### Les templates
Pour la vue, le moteur de templates twig est utilisé. Vous devrez mettre tout vos templates dans le dossier `view/`. Pour plus d'infos sur comment utiliser twig, allez sur le site officiel https://twig.symfony.com/.


### Erreur 404
Via le routeur, toutes les erreurs et exceptions sont récupérées et afficher via un templates. Il y a 2 templates :
- `errors/error404.html.twig` pour les erreurs 404
- `errors/exception.html.twig` pour les exceptions.

Vous ne devez pas les bouger de cet endroit sinon le framework ne pourras plus les afficher


-----

Autre
===
Si vous avez besoin de plus de précisions sur un détail du framework ou de son utilisation, vous pouvez me contacter via l'adresse kevin@ktraini.com
