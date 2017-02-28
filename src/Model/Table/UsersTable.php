<?php
namespace App\Model\Table;

use Cake\Core\Exception\Exception;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class UsersTable extends Table
{

    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->table('users');
        $this->displayField('username');
        $this->primaryKey('id');
        
        $this->belongsToMany('Groups', [
            'joinTable' => 'user_groups',
        ]);
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');
    
        $validator
            ->integer('active');
        
        $validator
            ->requirePresence('firstname', 'create')
            ->notEmpty('firstname')
            ->lengthBetween('firstname', [3, 50]);

        $validator
            ->requirePresence('lastname', 'create')
            ->notEmpty('lastname')
            ->lengthBetween('lastname', [3, 50]);

        $validator
            ->requirePresence('username', 'create')
            ->notEmpty('username')
            ->add('username', 'unique', ['rule' => 'validateUnique', 'provider' => 'table'])
            ->lengthBetween('username', [3, 20]);

        $validator
            ->requirePresence('password', 'create')
            ->notEmpty('password')
            ->lengthBetween('password', [8, 255]);

        $validator
            ->allowEmpty('avatar')
            ->add('avatar', 'custom', ['rule' => ['custom', '/[\w,\\,\.]+$/']]);

        $validator
            ->dateTime('created_at');

        $validator
            ->dateTime('modified_at')
            ->allowEmpty('modified_at');

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['username']));

        return $rules;
    }

    public function findAuth(Query $query, Array $options)
    {
        return $query->select(['id', 'firstname', 'lastname', 'username', 'password'])
            ->where(['active' => 1])
            ->contain(
                ['Groups' => function ($q) {
                    return $q->select(['name']);
                }]);
    }

    public function findUser(Query $query, Array $options)
    {
        $query->contain(['UserGroups']);
        if (isset($options['user_id'])) {
            $query->where(['id' => $options['user_id']]);
        }
        if (isset($options['active'])) {
            $query->where(['active' => $options['active']]);
        }
        return $query;
    }

    public function convertUsersIdsIntoUsers($usersIds)
    {
        $usersArr = [];
        $i = 0;
        foreach ($usersIds as $userId) {
            $usersArr[$i] = $this->get($userId['user_id']);
            $i++;
        }

        return $usersArr;
    }

    /**
     * Zwraca wszystkich użytkowników wraz z grupami
     * @return array
     */
    public function findAllUsers()
    {
        return $this->find()
            ->select(['id', 'active', 'firstname', 'lastname', 'username'])
            ->contain(['UserGroups.Groups'])
            ->toArray();
    }

    /**
     * Zwraca użytkownika z nazwą grupy
     * @param $userId
     * @return array
     */
    public function getUser($userId)
    {
        $user = $this->find()
            ->where(['id' => $userId])
            ->contain(['UserGroups' => function ($q) {
                return $q->contain(['Groups']);
            }])
            ->toArray();
        if (empty($user)) {
            throw new Exception('Nie ma takiego użytkownika', 404);
        }
        return $user;
    }

    /**
     * Zwraca grupy użytkownika
     * @param $userId
     * @return mixed
     */
    public function findUserGroups($userId)
    {
        return $this->find()
            ->where(['id' => $userId])
            ->contain(['UserGroups' => function ($q) {
                return $q->select(['id', 'user_id']);
            }])
            ->toArray()[0]['user_groups'];
    }

    /**
     * Zwraca aktywnych użytkowników
     * @return array
     */
    public function findActiveUsers()
    {
        return $this->find()
            ->where(['is_active' => 1])
            ->select(['id'])
            ->toArray();
    }

}
