<?php

namespace App\Controller;

use Cake\Controller\Component\AuthComponent;
use Cake\Controller\Controller;
use Cake\Core\Exception\Exception;
use Cake\Event\Event;
use Cake\Routing\Router;
use Firebase\JWT\JWT;
use Cake\Utility\Security;

class AppController extends Controller
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Auth', [
            'storage' => 'Memory',
            'authorize' => 'Controller',
            'authenticate' => [
                AuthComponent::ALL => [
                    'userModel' => 'Users',
                    'finder' => 'auth',
                ],
                'FormApi' => [],
                'ADmad/JwtAuth.Jwt' => [
                    'parameter' => 'token',
                    'fields' => [
                        'username' => 'id',
                    ],
                    'queryDatasource' => true,
                ],
            ],
            'unauthorizedRedirect' => false,
            'checkAuthIn' => 'Controller.initialize',
        ]);

    }
    
    /**
     * Dekoduje przesłany token i sprawdza czy user ma wpisany admin = true, w token'ie
     * @param null $user
     * @return bool
     */
    public function isAuthorized($user = null)
    {
        $isAdmin = JWT::decode((str_replace('Bearer ', '', $this->request->header('Authorization'))),
            Security::salt(), ['HS256'])->admin;
        if ($isAdmin) {
            return true;
        }
        return false;
    }

    public function beforeRender(Event $event)
    {
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
        $this->response->header('Access-Control-Allow-Origin: *');
    }

    public function isSent()
    {
        return $this->request->is('post');
    }

    public function parse($array)
    {
        echo json_encode($array);
    }

    public function getRequestData($name = null)
    {
        if ($name == null) {
            return $this->request->data;
        }

        return $this->request->data[$name];
    }

}
