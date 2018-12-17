<?php

namespace Test\Repository;

use Test\Services\Database;

/**
 * Class BaseRepository
 *
 * @author Florian Stein <fstein@databay.de>
 */
abstract class BaseRepository
{
    /**
     * @var Database
     */
    protected $database;

    /**
     * BaseRepository constructor.
     */
    public function __construct ()
    {
        $this->database = Database::getInstance();
    }

    /**
     * @return mixed
     */
    public function currentUser()
    {
        return $this->findOneBy(['id' => $_SESSION['userid']]);
    }

    /**
     * @param $model
     *
     * @return mixed
     * @throws \Exception
     */
    public function add ($model)
    {
        if (!$this->isSupported($model)) {
            throw new \Exception(
                \sprintf(
                    "Cannot save an model of instance %s, in Repository %s",
                    get_class($model),
                    get_class($this)
                )
            );
        }
        $data = $this->objectToArray($model);

        $properties = [];
        foreach ($data as $key => $value) {
            if ($key !== 'id') {
                $properties[$key] = $key.' = :'.$key;
            }
        }
        if ($model->getId() > 0) {
            $query = "UPDATE ".$this->getTableName()." SET ";
            $query .= \join(', ', $properties);
            $query .= ' WHERE id = :id';

            return $this->database->update($query, $data);
        }
        $query = "INSERT INTO ".$this->getTableName()." SET ";
        $query .= \join(', ', $properties);

        $reTweet = $data['reTweet'];

        if ($reTweet !== null) {
            $data['reTweet'] = $reTweet->getId();
        }

        unset($data['id']);

        return $this->database->insert($query, $data);
    }


    /**
     * @return array
     */
    public function findAll ()
    {
        $result = $this->database->query("SELECT * FROM " . $this->getTableName() . " ORDER BY createDate DESC");

        $found = [];
        foreach ($result as $data) {
            $model = $this->arrayToObject($data);

            $found[] = $model;
        }
        return $found;
    }

    /**
     * @param array $parameters
     *
     * @return mixed
     */
    public function findOneBy (array $parameters)
    {
        $properties = [];
        foreach ($parameters as $key => $value) {
            $properties[$key] = $key.' = :'.$key;
        }
        $query = "SELECT * FROM ".$this->getTableName()." WHERE ";
        $query .= \join(',', $properties);

        $data = $this->database->query($query, $parameters);
        $data2 = $data[0];
        $object = $this->arrayToObject($data2);

        return $object;
    }

    /**
     * @param array $parameters
     *
     * @return array
     */
    public function findBy (array $parameters)
    {
        $properties = [];
        foreach ($parameters as $key => $value) {
            $properties[$key] = $key.' = :'.$key;
        }
        $query = "SELECT * FROM ".$this->getTableName()." WHERE ";
        $query .= \join(',', $properties);

        $data = $this->database->query($query, $parameters);

        $objects = [];
        foreach ($data as $result) {
            $object = $this->arrayToObject($result);
            $objects[] = $object;
        }

        return $objects;
    }

    /**
     * @param $model
     *
     * @return mixed
     */
    public function remove ($model)
    {
        $data = $this->objectToArray($model);
        $data2['id'] = $data['id'];

        $query = "DELETE FROM ".$this->getTableName();
        $query .= " WHERE id = :id";

        return $this->database->insert($query, $data2);
    }


    /**
     * @param array $parameters
     *
     * @return mixed
     */
    public function removeAllBy (array $parameters)
    {
        $properties = [];
        foreach ($parameters as $key => $value) {
            $properties[$key] = $key.' = :'.$key;
        }
        $query = "DELETE FROM ".$this->getTableName()." WHERE ";
        $query .= \join(',', $properties);

        return $this->database->insert($query, $parameters);
    }

    /**
     * @return string
     */
    abstract protected function getTableName ();

    /**
     * @param $model
     *
     * @return array
     */
    abstract protected function objectToArray ($model);

    /**
     * @param $model
     *
     * @return boolean
     */
    abstract protected function isSupported ($model);

    /**
     * @param $data
     *
     * @return mixed
     */
    abstract protected function arrayToObject ($data);
}
