<?php

namespace Test\Repository;

use Doctrine\ORM\EntityManager;
use Test\Model\Tweet;

/**
 * Class TweetRepository
 *
 * @author Florian Stein <fstein@databay.de>
 */
class TweetRepository extends BaseRepository
{
    ///**
    // * @var UserRepository
    // */
    //protected $userRepository;
    //
    ///**
    // * TweetRepository constructor.
    // *
    // * @param UserRepository    $userRepository
    // */
    //public function __construct (UserRepository $userRepository)
    //{
    //    parent::__construct();
    //    $this->userRepository = $userRepository;
    //}

    /**
     * @return mixed
     */
    protected function getTableName ()
    {
        return 'tweets';
    }

    /**
     * @param mixed $model
     *
     * @return bool
     */
    protected function isSupported ($model)
    {
        return $model instanceof Tweet;
    }
}