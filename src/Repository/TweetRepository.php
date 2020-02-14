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