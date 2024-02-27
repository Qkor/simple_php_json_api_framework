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
        if($this->userService->addUser($username, $password))
            return ['success' => true];
        return $this->errorResponse(1, 'could not create a user');
    }

}
