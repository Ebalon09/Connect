<?php

namespace Test\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class BaseModel
 *
 * @author Florian Stein <fstein@databay.de>
 */
abstract class BaseModel
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
