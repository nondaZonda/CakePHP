<?php

namespace App\Model\Entity;

use Cake\Core\Exception\Exception;
use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;

class User extends Entity
{
    const ID_OF_ADMIN_GROUP = 1;

    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];

    protected $_hidden = [
        'password',
    ];

    protected function _setPassword($pass)
    {
        $hasher = new DefaultPasswordHasher();
        return $hasher->hash($pass);
    }
    
    public function isActive()
    {
        return ($this->active) ? true : false;
    }

    public function isExist()
    {
        return ($this->id) ? true : false;
    }
}
