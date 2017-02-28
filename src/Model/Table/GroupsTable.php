<?php

namespace App\Model\Table;

use Cake\Core\Exception\Exception;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class GroupsTable extends Table
{

    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->table('groups');
        $this->displayField('name');
        $this->primaryKey('id');
        $this->belongsToMany('Users', [
            'joinTable' => 'user_groups'
        ]);
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->notEmpty('name');
        
        $validator
            ->integer('active')
            ->notEmpty('active');
    
        $validator
            ->dateTime('created_at');
    
        $validator
            ->dateTime('modified_at')
            ->allowEmpty('modified_at');

        return $validator;
    }

    public function addUser($user_id, $group_id)
    {
        $userGroups = TableRegistry::get('UserGroups');
        $userGroup = $userGroups->newEntity(['user_id' => $user_id, 'group_id' => $group_id]);

        if (!$userGroups->isExist($userGroup)) {
            if ($userGroups->save($userGroup)) {
                $users = TableRegistry::get('Users');
                return [
                    'group' => $this->get($userGroup->group_id),
                    'user' => $users->get($userGroup->user_id)
                ];
            }
        } else {
            return 'użytkownik z id = ' . $user_id . ' obecnie istnieje w grupie o id = ' . $group_id;
        }
    }

    public function deleteUser($user_id, $group_id)
    {
        $userGroups = TableRegistry::get('UserGroups');
        $query = 'user_id = ' . $user_id . ' AND group_id = ' . $group_id;

        if($userGroups->delete($userGroups->findByQuery($query))) {
            return 'użytkownik został usunięty';
        }
        return 'użtkownik nie został usunięty z grupy';
    }
    
    public function findActive(Query $query, Array $options)
    {
        return $query->contain(['Jobs'])
            ->where(['is_active' => 1]);
    }
    
}
