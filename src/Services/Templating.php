<?php
/**
 * Created by PhpStorm.
 * User: jgeiger
 * Date: 12.10.18
 * Time: 12:01
 */
namespace Test\Services;

require_once 'SingletonTrait.php';

class Templating
{
    use SingletonTrait;

    private $twig;

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
        if(strpos($template, '.php') !== false) {
            ob_start();
            extract($parameters);
            include $template;
            $content = ob_get_clean();

            return $content;
        }

        $this->twig->addGlobal("alerts",Session::getInstance()->readMessage());

        return $this->twig->render($template, $parameters);
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
