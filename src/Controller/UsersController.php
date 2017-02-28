<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\Image;
use Cake\Core\Exception\Exception;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Security;
use App\Model\Entity;


class UsersController extends AppController
{
    protected $_adminActions = ['add', 'delete', 'edit', 'activate'];
    
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['token']);
    }
    
    public function isAuthorized($user = null)
    {
        $isAdmin = parent::isAuthorized($user);
        if (!$isAdmin && in_array(($this->request->param('action')), $this->_adminActions)){
            return false;
        }
        return true;
    }


    public function token()
    {
        $user = $this->Auth->identify();
        if (!$user) {
            throw new UnauthorizedException('Nieprawidłowy login lub hasło');
        }
        $this->set([
            'success' => true,
            'data' => [
                /*
                 * 
                 */
            ],
        ]);
        $this->response->statusCode(201);
    }

    public function getUsersFromGroup($group_id = null)
    {
        $userGroups = TableRegistry::get('UserGroups');

        $this->paginate = [
            'fields' => ['user_id'],
            'limit' => 15,
            'order' => ['user_id' => 'asc'],
        ];
        $query = $userGroups->find()->select('user_id')->where(['group_id' => $group_id]);
        $users_ids = $this->paginate($query);
        $this->set('users', $this->Users->convertUsersIdsIntoUsers($users_ids));
    }

    /**
     * Routing: (POST): /users/:user_id/avatar
     * Załadowanie avatara dla użytkownika.
     * Paramery zapytania:
     *  fileName - nazwa parametru z plikiem avatar'a
     *  user_id - id użytkownika
     */
    public function avatar($user_id = null)
    {
        $this->loadComponent('Image');
        $path = $this->Image->uploadImage('fileName', Image::TYPE_AVATAR);
        $user = $this->Users->get($user_id);
        $user = $this->Users->patchEntity($user, ['avatar' => $path]);
        if (!empty($user->errors())) {
            throw new Exception('Błąd walidacji', 400);
        }
        if (!$this->Users->save($user)) {
            throw new Exception('Błąd zapisu na serwerze', 500);
        }
        $this->response->statusCode(204);
    }

    /**
     * Pobranie adresu URL avatara użytkownika
     * @param null $user_id  - id użytkownika
     */
    public function getAvatar($user_id = null)
    {
        $this->set('URL', $this->Users->get($user_id)->avatar);
    }
    
    public function deleteAvatar($user_id = null)
    {
        $avatarPath = $this->Users->get($user_id)->avatar;
        $this->loadComponent('Image');
        $this->Image->simpleDeleteImg($avatarPath);

        $user = $this->Users->get($user_id);
        $user->avatar = null;
        if (!$this->Users->save($user)) {
            throw new Exception('Błąd serwera.', 500);
        }
        $this->response->statusCode(204);
    }


    /**
     * Routing: (POST): /users/
     * Tworzy nowego użytkownika
     */
    public function add()
    {
        $entity = $this->Users->newEntity($this->request->data);
        if ($entity->errors()) {
            $errors = $entity->errors();
            $reset = reset($errors);
            throw new Exception('Błąd walidacji danych. ' . reset($reset) . ' : ' . key($errors), 400);
        }
        if (!$this->Users->save($entity)) {
            throw new Exception('Błąd serwera! Nie dodano użytkownika, '.$this->parseValidatorErrors($entity), 500);
        }
        if (!isset($this->request->data['group_id'])) {
            throw new Exception('Musisz podać group_id', 400);
        }
        $groupTable = TableRegistry::get('Groups');
        $groupTable->addUser($entity['id'], $this->request->data['group_id']);
        
        $this->response->statusCode(201);
    }

    /**
     * Routing: (PUT): /users/:user_id
     * Edycja użytkownika
     */
    public function edit($userId = null)
    {
        $entity = $this->Users->find('User', ['user_id' => $userId, 'active' => 1])->first();
        if (!$entity) {
            throw new Exception('Nie znaleziono użytkownika !', 404);
        }
        if (empty($this->request->data)) {
            throw new Exception('Brak przesłanych danych do modyfikacji.', 400);
        }
        $this->Users->patchEntity($entity, $this->request->data);
        if(!$this->Users->save($entity)) {
            throw new Exception('Błąd serwera, nie zapisano zmian!', 500);
        }
        
        $this->response->statusCode(204);
    }

    /**
     * Routing: (DELETE): /users/:user_id
     * @param $userId
     */
    public function delete($userId = null)
    {
        $entity = $this->Users->get($userId);
        $this->Users->patchEntity($entity, (['active' => 0]));
        if(!$this->Users->save($entity)) {
            throw new Exception('Błąd serwera, nie zapisano zmian!', 500);
        }
        
        $this->response->statusCode(204);
    }

    /**
     * Routing: (POST): /users/:user_id/activate
     * @param $userId
     */
    public function activate($userId = null)
    {
        $entity = $this->Users->get($userId);
        $this->Users->patchEntity($entity, (['active' => 1]));
        if(!$this->Users->save($entity)) {
            throw new Exception('Błąd serwera, nie zapisano zmian!', 500);
        }
    
        $this->response->statusCode(204);
    }
    
    /**
     * Routing: (GET): /users/:user_id
     */
    public function view($userId = null)
    {
        $this->set('user', $this->Users->getUser($userId));
    }

    /**
     * Routing: (GET): /users/
     */
    public function viewAll()
    {
        $this->set('users', ($this->Users->findAllUsers()));
    }

}