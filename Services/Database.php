<?php
/**
 * Created by PhpStorm.
 * User: fstein
 * Date: 12.10.18
 * Time: 10:44
 */

require_once 'SingletonTrait.php';

class Database
{
    use SingletonTrait;

    const DB_DRIVER = 'mysql';
    const DB_HOST = 'localhost';
    const DB_USER = 'root';
    const DB_PASSWORD = 'root';
    const DB_NAME = 'Twitter';

    private $connection;


    public function connect()
    {
        $this->connection = new PDO($this->getDSN(), self::DB_USER, self::DB_PASSWORD, array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ));
    }

    /**
     * @param $query
     * @param array $parameters
     * @return mixed
     */
    public function insert($query, array $parameters = array())
    {
        $statement = $this->connection->prepare($query);

        foreach($parameters as $key => $value)
        {
            $statement->bindValue(':'.$key, $value);
        }

        return $statement->execute();

    }

    /**
     * @param $query
     * @param array $parameters
     * @return mixed
     */
    public function query($query, array $parameters = array())
    {
        $statement = $this->connection->prepare($query);

        foreach($parameters as $key => $value)
        {
            $statement->bindValue(':'.$key, $value);
        }

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param $query
     * @param array $parameters
     * @return mixed
     */
    public function update($query, array $parameters = array())
    {

        $statement = $this->connection->prepare($query);

        foreach($parameters as $key => $value)
        {
            $statement->bindValue(':'.$key, $value);
        }

        return $statement->execute();
    }

    /**
     * Database constructor.
     */
    protected function __construct()
    {
        $this->connect();
    }


    /**
     * @return string
     */
    private function getDSN()
    {
        return sprintf("%s:host=%s;dbname=%s", self::DB_DRIVER, self::DB_HOST, self::DB_NAME);
    }

}