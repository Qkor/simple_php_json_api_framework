<?php

namespace Qkor\Controller;
use Qkor\Service\UserService;

class UserController extends ControllerBase {

    protected array $routes = [
        'registration' => 'register'
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

}
