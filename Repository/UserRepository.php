<?php
/**
 * Created by PhpStorm.
 * User: fstein
 * Date: 13.11.18
 * Time: 08:18
 */

class UserRepository
{
    /**
     * @var Database
     */
    private $database;

    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
        $this->database = Database::getInstance();
    }


    /**
     * @param $username
     * @return User
     */
    public function findOneByUsername($username)
    {
        $data = $this->database->query("SELECT * FROM Login WHERE username = :username;",[
            'username' => $username
        ])[0];
        $user = $this->arrayToObject($data);

        return $user;
    }


    /**
     * @param $email
     * @return User
     */
    public function findOneByEmail($email)
    {
        $data = $this->database->query("SELECT * FROM Login WHERE email = :email;",[
            'email' => $email
        ])[0];

        $user = $this->arrayToObject($data);

        return $user;
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function add(User $user)
    {
        $data = $this->objectToArray($user);

        $properties = [];
        foreach($data as $key => $value)
        {
            if($key !== 'id')
            {
                $properties[$key] = $key . ' = :' .$key;
            }
        }

        if($user->getId() > 0){

            $query = "UPDATE Login SET";
            $query .= \join(', ', $properties);
            $query .= ' WHERE id = :id';

            return $this->database->insert($query, $data);
        }

        $query = "INSERT INTO Login SET ";
        $query .= \join(', ', $properties);



        unset($data['id']);
        return $this->database->insert($query, $data);
    }


    /**
     * @param $data
     * @return User
     */
    private function arrayToObject($data)
    {
        $user = new User();
        $user->setId($data['id']);
        $user->setUsername($data['Username']);
        $user->setPassword($data['Password']);
        $user->setEmail($data['Email']);

        return $user;
    }

    /**
     * @param User $user
     * @return array
     */
    private function objectToArray(User $user)
    {
        $data = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'password' => $user->getPassword(),
            'Email' => $user->getEmail(),
        ];

        return $data;
    }


}