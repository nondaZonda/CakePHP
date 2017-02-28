<?php

namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class UserGroupsTable extends Table
{

    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->table('user_groups');
        $this->displayField('id');
        $this->primaryKey('id');
        $this->belongsTo('Groups', [
            'foreignKey' => 'group_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->integer('user_id')
            ->notEmpty('user_id');

        $validator
            ->integer('group_id')
            ->notEmpty('group_id');
    
        $validator
            ->dateTime('created_at');
    
        $validator
            ->dateTime('modified_at')
            ->allowEmpty('modified_at');

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['group_id'], 'Groups'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    public function findUsersFromGroup($group_id)
    {
        return $this->find()
            ->select('user_id')
            ->where(['group_id' => $group_id])
            ->toArray();
    }

    public function findByQuery($whereQuery)
    {
        return $this->find()
            ->where($whereQuery)
            ->first();
    }

    public function isExist($entity)
    {
        if ($this->findByQuery('user_id = ' . $entity['user_id'] . ' AND group_id = ' . $entity['group_id']) != null)
        {
            return true;
        }
    }
}
