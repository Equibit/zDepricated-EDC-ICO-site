<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Models\AuthUserModel;
use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Helpers\BaseAPIController;

class ChangeSecurityQuestion extends BaseAPIController {
    function post_xhr() {
        if ($this->checkAuth()) {
            if (!empty($_POST['question']) && isset($_POST['answer']) && mb_strlen($_POST['answer']) >= _SECURITY_ANSWER_MIN_LENGTH_) {
                $headers = getallheaders();
                $newUser = new AuthUserModel();
                $newUser->loadUser(mb_strtolower($headers['Auth-User']));
                if ($newUser->setQuestion($_POST['question'], mb_strtolower($_POST['answer']))) {
                    echo json_encode(StatusReturn::S200());
                } else {
                    echo json_encode(StatusReturn::E400('Unknown Error: csq 18'));
                }
            } else {
                echo json_encode(StatusReturn::E400('Unknown Error: csq 21'));
            }
        }
    }
}