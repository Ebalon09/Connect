<?php
namespace Test\Services;

/**
 * Created by PhpStorm.
 * User: jgeiger
 * Date: 12.10.18
 * Time: 12:51
 */


class Request
{
    protected $query;
    protected $post;


    /**
     * Request constructor.
     * @param array $queryData
     * @param array $postData
     */
    public function __construct(array $queryData = array(), array $postData = array())
    {
        $this->query = new ParameterBag($queryData);
        $this->post = new ParameterBag($postData);
    }

    /**
     * @return ParameterBag
     */
    public function getQuery()
    {
        return $this->query;                                    //returns the content of the array $queryData in $query
    }

    /**
     * @return ParameterBag
     */
    public function getPost()
    {
        return $this->post;                                     //returns the content of the array $postData in $post
    }

    /**
     * @return bool
     */
    public function isPostRequest(){
        return !$this->post->isEmpty();
    }

}