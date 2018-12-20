<?php

namespace Test\Services;

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
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * Templating constructor.
     */
    public function __construct() {
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../../templates');
        $this->twig = new \Twig_Environment($loader);
    }

    /**
     * @param $template
     * @param array $parameters
     * @return string
     */
    public function render($template, array $parameters = array())
    {
        $this->twig->addGlobal("alerts",Session::getInstance()->readMessage());

        return $this->twig->render($template, $parameters);
    }

    /**
     * @param \Twig_Extension $extension
     */
    public function addExtension(\Twig_Extension $extension) {
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