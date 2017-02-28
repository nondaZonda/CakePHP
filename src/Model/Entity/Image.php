<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;


class Image extends Entity
{
    const TYPE_IMAGE = 1;
    const TYPE_AVATAR = 2;

    public function getTypes()
    {
        return [
            self::TYPE_IMAGE,
            self::TYPE_AVATAR
        ];
    }

}

