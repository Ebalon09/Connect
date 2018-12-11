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

    /**
     * @param $template
     * @param array $parameters
     * @return string
     */
    public function render($template, array $parameters = array())
    {
        ob_start();
        extract($parameters);
        include $template;
        $content = ob_get_clean();

        return $content;
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
