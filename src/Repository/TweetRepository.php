<?php

namespace Test\Repository;

use Test\Model\Tweet;

/**
 * Class TweetRepository
 *
 * @author Florian Stein <fstein@databay.de>
 */
class TweetRepository extends BaseRepository
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * TweetRepository constructor.
     */
    public function __construct ()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }

    /**
     * @return mixed
     */
    protected function getTableName ()
    {
        return 'Tweet';
    }

    /**
     * @param $data
     *
     * @return Tweet
     */
    protected function arrayToObject ($data)
    {
        $tweet = new Tweet();
        $tweet->setId($data['id']);
        $tweet->setText($data['text']);
        $tweet->setDatum(new \DateTime($data['createDate']));
        $tweet->setUser($this->userRepository->findOneBy(['id' => $data['userid']]));
        $tweet->setDestination($data['destination']);
        $tweet->setLinkID($data['LinkID']);

        if ($data['reTweet'] !== null) {

            $tweet->setReTweet($this->findOneBy(['id' => $data['reTweet']]));
        }

        return $tweet;
    }

    /**
     * @param Tweet $tweet
     *
     * @return array
     */
    protected function objectToArray ($tweet)
    {
        $data = [
            'id'          => $tweet->getId(),
            'text'        => $tweet->getText(),
            'createDate'  => $tweet->getDatum()->format('Y-m-d H:i:s'),
            'userid'      => $tweet->getUser()->getId(),
            'destination' => $tweet->getDestination(),
            'LinkID'      => $tweet->getLinkID(),
            'reTweet'     => $tweet->getReTweet(),
        ];

        return $data;
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