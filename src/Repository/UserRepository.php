<?php

namespace Test\Repository;

use Test\Model\User;

/**
 * Class UserRepository
 *
 * @author Florian Stein <fstein@databay.de>
 */
class UserRepository extends BaseRepository
{

    /**
     * @param $data
     *
     * @return User
     */
    protected function arrayToObject ($data)
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
     *
     * @return array
     */
    protected function objectToArray ($model)
    {
        $data = [
            'id'       => $model->getId(),
            'username' => $model->getUsername(),
            'password' => $model->getPassword(),
            'Email'    => $model->getEmail(),
            'picture'  => $model->getPicture(),
        ];

        return $data;
    }


    /**
     * @return mixed
     */
    protected function getTableName ()
    {
        return 'Users';
    }

    /**
     * @param mixed $model
     *
     * @return bool
     */
    protected function isSupported ($model)
    {
        return $model instanceof User;
    }


}