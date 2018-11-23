<?php

/**
 * Created by PhpStorm.
 * User: fstein
 * Date: 20.11.18
 * Time: 12:26
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
    public function __construct()
    {
        $this->database = Database::getInstance();
    }

    /**
     * @param mixed $model
     * @return mixed
     * @throws Exception
     */
    public function add($model)
    {
        if(!$this->isSupported($model)) {
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
        foreach($data as $key => $value)
        {
            if($key !== 'id')
            {
                $properties[$key] = $key . ' = :' .$key;
            }
        }
        if($model->getId() > 0){

            $query = "UPDATE ".$this->getTableName()." SET ";
            $query .= \join(', ', $properties);
            $query .= ' WHERE id = :id';

            return $this->database->update($query, $data);
        }

        $query = "INSERT INTO ".$this->getTableName()." SET ";
        $query .= \join(', ', $properties);

        unset($data['id']);
        return $this->database->insert($query, $data);
    }

    /**
     * @param array $parameters
     * @return mixed
     */
    public function findOneBy(array $parameters)
    {
        $properties = [];
        foreach($parameters as $key => $value)
        {
            $properties[$key] = $key . ' = :' .$key;
        }

        $query = "SELECT * FROM ".$this->getTableName()." WHERE ";
        $query .= \join(',',$properties);

        $data = $this->database->query($query, $parameters);


        $data2 = $data[0];

        $object = $this->arrayToObject($data2);

        return $object;
    }

    /**
     * @param array $parameters
     * @return mixed
     */
    public function findBy(array $parameters){

        $properties = [];
        foreach($parameters as $key => $value)
        {
            $properties[$key] = $key . ' = :' .$key;
        }

        $query = "SELECT * FROM ".$this->getTableName()." WHERE ";
        $query .= \join(',',$properties);

        $data = $this->database->query($query, $parameters);


        $objects = [];
        foreach($data as $result){
            $object = $this->arrayToObject($result);
            $objects[] = $object;
        }
        return $objects;
    }



    /**
     * @return string
     */
    abstract protected function getTableName();

    /**
     * @param $model
     *
     * @return array
     */
    abstract protected function objectToArray($model);

    /**
     * @param mixed $model
     * @return boolean
     */
    abstract protected function isSupported($model);

    /**
     * @param $data
     * @return mixed
     */
    abstract protected function ArrayToObject($data);
}
