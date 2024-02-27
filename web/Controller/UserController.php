<?php

namespace Qkor\Controller;
use Qkor\Service\UserService;

class UserController extends ControllerBase {

    protected array $routes = [
        'registration' => 'register',
        'login' => 'login'
    ];
    protected UserService $userService;
    public function __construct(){
        parent::__construct();
        $this->userService = new UserService($this->db);
    }
    public function register() : array {
        $this->validateInput(['username' => 'string', 'password' => 'string']);
        $username = $this->input['username'];
        $password = password_hash($this->input['password'], PASSWORD_DEFAULT);
        if($this->userService->getUserByUsername($username))
            return $this->errorResponse(1,'user with this username already exists');
        $this->db->beginTransaction();
        if($uid = $this->userService->addUser($username, $password)){
            if($token = $this->userService->createUserSession($uid)){
                $this->db->commit();
                return [
                    'uid' => $uid,
                    'username' => $username,
                    'token' => $token
                ];
            }
        }
        $this->db->rollBack();
        return $this->errorResponse(1, 'could not create a user');
    }

    public function login() : array {
        $this->validateInput(['username' => 'string', 'password' => 'string']);
        if($user = $this->userService->validateUser($this->input['username'],$this->input['password'])){
            if($token = $this->userService->createUserSession($user->id())){
                return [
                    'uid' => $user->id(),
                    'username' => $user->getUsername(),
                    'token' => $token
                ];
            }
            return $this->errorResponse(0);
        }
        return $this->errorResponse(1, 'wrong username or password');
    }

}
