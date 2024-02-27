<?php

namespace Qkor\Error;
class ErrorHandler{
    private static array $serverError = ['errorName' => 'serverError', 'errorMessage' => 'Internal server error'];
    private static array $routeError = ['errorName' => 'wrongRoute', 'errorMessage' => 'Wrong route'];
    private static array $errors = [
        0 => ['errorName' => 'unknownError', 'errorMessage' => 'Something went wrong'],
        1 => ['errorName' => 'wrongInput', 'errorMessage' => 'Wrong input'],
    ];

    /**
     * Sets http response code to 400 and returns error response as an associative array
     * @return string[]
     */
    public static function getErrorResponse($errorId = 0, $customMessage = null) : array {
        http_response_code(400);
        $response = self::$errors[$errorId] ?? self::$errors[0];
        if($customMessage && strlen($customMessage))
            $response['errorMessage'] = $customMessage;
        return $response;
    }

    /**
     * Sets http response code to 500 and returns error response as an associative array
     * @return string[]
     */
    public static function getServerErrorResponse() : array {
        http_response_code(500);
        return self::$serverError;
    }

    /**
     * Sets http response code to 404 and returns error response as an associative array
     * @return string[]
     */
    public static function getRouteErrorResponse() : array {
        http_response_code(404);
        return self::$routeError;
    }
}
