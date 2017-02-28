<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

class UserGroup extends Entity
{
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
}
