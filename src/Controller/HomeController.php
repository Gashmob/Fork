<?php

namespace Controller;

use Fork\Annotations\Route;
use Fork\Controller\AbstractController;
use Fork\Request\Session;
use Fork\Response\RedirectResponse;
use YamlEditor\YamlArray;

class HomeController extends AbstractController
{
    /**
     * @var Session
     */
    private $session;

    /**
     * HomeController constructor.
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }


    /**
     * @Route(route="/", name="home")
     */
    public function homepage()
    {
        return $this->render('home/homepage.html.twig');
    }

    /**
     * @Route(route="/redirect/{route}", name="redirect")
     * @param string $route
     * @return RedirectResponse
     */
    public function redirect(string $route)
    {
        return $this->redirectToRoute($route);
    }

    /**
     * @Route(route="/some/text", name="text")
     */
    public function someText()
    {
        return $this->text(
            "<h2 style='text-align: center'>Texte généré grâce au site <a href='https://fr.lipsum.com/' target='_blank'>https://fr.lipsum.com/</a></h2>
            <p style='text-align: justify'>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce molestie massa id elementum venenatis. Duis rhoncus enim vel ipsum laoreet, sed posuere turpis gravida. Cras eget lobortis odio. Cras at pellentesque est. Nulla ultricies venenatis egestas. Suspendisse erat velit, feugiat sit amet lorem aliquam, aliquet tempus sem. Fusce tempor ligula in orci suscipit, id venenatis metus maximus. Aenean vitae finibus elit, in ultrices mi. Sed id facilisis augue. Cras arcu mi, accumsan ut imperdiet non, dapibus vitae ex. Curabitur at orci erat. Proin tincidunt pellentesque tellus, vel vehicula enim eleifend non.
            Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Praesent at leo at dolor finibus suscipit eget elementum ante. Nunc nulla justo, vestibulum id lectus vitae, elementum tempor odio. Donec molestie est ut risus laoreet suscipit. Sed convallis dolor purus, eu dapibus augue egestas sit amet. Donec congue convallis eros, id fringilla lorem sagittis eu. Nam aliquet imperdiet metus non fermentum. Proin at efficitur libero. Nulla id leo rutrum ipsum mattis tempor.
            Nulla nulla odio, interdum sed euismod sit amet, rutrum vel erat. Quisque ornare massa risus, nec dapibus lorem volutpat sit amet. Duis in congue lorem. Vivamus rhoncus nunc a luctus sodales. Duis quis accumsan orci. Vestibulum ullamcorper quam posuere suscipit bibendum. Mauris hendrerit eleifend urna, vel pharetra dolor volutpat sed. Aliquam facilisis lorem sit amet vulputate rhoncus. Suspendisse potenti. In hac habitasse platea dictumst. Cras erat enim, blandit sit amet ornare in, placerat nec felis. Nunc pretium, lacus quis egestas semper, enim felis vehicula elit, eu efficitur tortor arcu ut ante.
            Interdum et malesuada fames ac ante ipsum primis in faucibus. Praesent tempus, nibh a bibendum volutpat, nisi magna hendrerit quam, sit amet placerat tortor purus pellentesque libero. Cras eget velit eget tortor fermentum efficitur non vel neque. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Donec finibus sagittis lacus, id varius lacus viverra quis. Vestibulum rhoncus vulputate urna, ac interdum odio scelerisque quis. Aliquam in ornare lectus. Sed venenatis arcu nunc, ac rutrum sapien blandit quis. Maecenas aliquam bibendum vehicula. Vivamus dapibus sapien congue, mollis magna at, mollis lorem. Aenean rhoncus egestas magna non condimentum. Donec a porta neque, eget consectetur eros.
            Integer sagittis, risus et malesuada dictum, justo dui commodo dui, non luctus augue metus ac nisl. Suspendisse feugiat scelerisque posuere. Vivamus tellus arcu, luctus nec rhoncus at, ornare porttitor urna. Nulla ac arcu sodales, dictum mi ac, vulputate lorem. Donec non cursus diam. Sed mattis urna nisi. Proin a nulla gravida risus imperdiet dignissim. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Cras faucibus ultrices turpis, eget maximus nisi vulputate vel. Nulla facilisi.</p>
        ");
    }

    /**
     * @Route(route="/some/yaml", name="yaml")
     */
    public function someYaml()
    {
        $y = new YamlArray('');
        $y->set('bonjour.test', 'Hello World !');
        $y->set('answer', 42);

        return $this->yaml($y);
    }
}