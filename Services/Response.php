<?php

/**
 * Created by PhpStorm.
 * User: jgeiger
 * Date: 12.10.18
 * Time: 13:50
 */

require_once 'ResponseInterface.php';

class Response implements ResponseInterface
{
    /**
     * @var string
     */
    private $content;       //String returns the contents as text/html

    /**
     * Response constructor.
     * @param $content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     *
     */
    public function send()
    {
        echo $this->content;
    }

    /**
     * @param $content
     */
    public function setContent($content)            //Response Content gets parsed into $content
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent()                //Response Content output
    {
        return $this->content;
    }

}