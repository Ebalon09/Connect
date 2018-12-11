<?php
/**
 * Created by PhpStorm.
 * User: jgeiger
 * Date: 12.10.18
 * Time: 13:54
 */
namespace Test\Services;

class ResponseRedirect implements ResponseInterface
{

    private $location;

    /**
     * ResponseRedirect constructor.
     * @param $location
     */
    public function __construct($location)
    {
        $this->location = $location;
    }

    public function send()
    {
        header('Location: '. $this->location);
    }
}
