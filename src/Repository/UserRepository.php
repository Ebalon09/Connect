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
     * @return mixed
     */
    protected function getTableName ()
    {
        return 'users';
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