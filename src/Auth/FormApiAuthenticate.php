<?php

namespace App\Auth;

use Cake\Auth\FormAuthenticate;
use Cake\Network\Request;
use Cake\Network\Response;

class FormApiAuthenticate extends FormAuthenticate
{
    public function authenticate(Request $request, Response $response)
    {
        $user = parent::authenticate($request, $response);
        if (!$user){
            return null;
        }
        foreach ($user['groups'] as $group) {
            $user['groups_id'][] = $group['_joinData']['group_id'];
        }
        return $user;
    }

}