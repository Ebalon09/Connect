<?php
/**
 * Created by PhpStorm.
 * User: fstein
 * Date: 13.11.18
 * Time: 08:18
 */

class UserRepository extends BaseRepository
{
    /**
     * @param $username
     * @return User
     */
    public function findOneByUsername($username)
    {
        $data = $this->database->query("SELECT * FROM Users WHERE username = :username",[
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
        $data = $this->database->query("SELECT * FROM Users WHERE email = :email",[
            'email' => $email
        ])[0];
        $user = $this->arrayToObject($data);

        return $user;
    }

    /**
     * @param $data
     * @return User
     */
    protected function arrayToObject($data)
    {
        $user = new User();
        $user->setId($data['id']);
        $user->setUsername($data['username']);
        $user->setPassword($data['password']);
        $user->setEmail($data['email']);
        $user->setPicture($data['picture']);
        return $user;
    }

    /**
     * @param User $model
     * @return array
     */
    protected function objectToArray($model)
    {
        $data = [
            'id' => $model->getId(),
            'username' => $model->getUsername(),
            'password' => $model->getPassword(),
            'Email' => $model->getEmail(),
            'picture' => $model->getPicture()
        ];


        return $data;
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function remove(User $user)
    {
        $data = $this->objectToArray($user);
        $data2['id'] = $data['id'];

        $query = "DELETE FROM Users ";
        $query .= "WHERE id = :id";
        return $this->database->insert($query, $data2);
    }

    /**
     * @return mixed
     */
    protected function getTableName()
    {
        return 'Users';
    }

    /**
     * @param mixed $model
     * @return bool
     */
    protected function isSupported($model)
    {
        return $model instanceof User;
    }


}