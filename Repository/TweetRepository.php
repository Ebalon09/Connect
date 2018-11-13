<?php

/**
 * Created by PhpStorm.
 * User: fstein
 * Date: 12.11.18
 * Time: 11:43
 */
class TweetRepository
{
    /**
     * @var Database
     */
    private $database;

    /**
     * TweetRepository constructor.
     */
    public function __construct()
    {
        $this->database = Database::getInstance();
    }


    /**
     * @return array
     */
    public function findAll()
    {
        $result = $this->database->query("SELECT * FROM Entries ORDER BY datum DESC;");

        $tweets = [];
        foreach($result as $data) {
            $tweet = $this->arrayToObject($data);

            $tweets[] = $tweet;
        }

        return $tweets;
    }

    /**
     * @param $id
     * @return Tweet
     */
    public function findOneById($id)
    {
        $data = $this->database->query("SELECT * FROM Entries WHERE id = :id;", [
            'id' => $id
        ])[0];

        $tweet = $this->arrayToObject($data);

        return $tweet;
    }

    /**
     * @param Tweet $tweet
     * @return mixed
     */
    public function add(Tweet $tweet)
    {
        $data = $this->objectToArray($tweet);
        $properties = [];
        foreach($data as $key => $value)
        {
            if($key !== 'id')
            {
                $properties[$key] = $key  . ' = :' .$key;
            }
        }

        if($tweet->getId() > 0){

            $query = "UPDATE Entries SET ";
            $query .= \join(', ', $properties);
            $query .= ' WHERE id = :id';

            return $this->database->update($query, $data);
        }

        $query = "INSERT INTO Entries SET ";
        $query .= \join(', ', $properties);

        unset($data['id']);
        return $this->database->insert($query, $data);
    }


    /**
     * @param $data
     * @return Tweet
     */
    private function arrayToObject($data)
    {
        $tweet = new Tweet();
        $tweet->setId($data['id']);
        $tweet->setText($data['text']);
        $tweet->setDatum(new \DateTime($data['datum']));
        $tweet->setPostid($data['postid']);
        $tweet->setDestination($data['Destination']);
        return $tweet;
    }

    /**
     * @param Tweet $tweet
     * @return array
     */
    private function objectToArray(Tweet $tweet)
    {
        $data = [
            'id' => $tweet->getId(),
            'text' => $tweet->getText(),
            'datum' => $tweet->getDatum()->format('Y-m-d H:i:s'),
            'postid' => $tweet->getPostid(),
            'Destination' => $tweet->getDestination(),
        ];

        return $data;
    }

}