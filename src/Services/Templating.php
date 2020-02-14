<?php

namespace Test\Services;

use Symfony\Bridge\Twig\Extension\RoutingExtension;

require_once 'SingletonTrait.php';

/**
 * Class Templating
 *
 * @author Florian Stein <fstein@databay.de>
 */
class Templating
{
    use SingletonTrait;

    /**
     * @var \Twig\Environment
     */
    private $twig;

    /**
     * Templating constructor.
     */
    public function __construct() {
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../templates');
        $this->twig = new \Twig\Environment($loader);
    }

    /**
     * @param $template
     * @param array $parameters
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function render($template, array $parameters = array())
    {
        $this->twig->addGlobal("alerts",Session::getInstance()->readMessage());

        return $this->twig->render($template, $parameters);
    }

    /**
     * @param \Twig\Environment $extension
     */
    public function addExtension(RoutingExtension $extension) {
        $this->twig->addExtension($extension);
    }

    /**
     *sends response header from server to client
     */
    public function backToIndex()
    {
        header('Location:');
        exit;
    }
}