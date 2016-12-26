<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Models\AuthUserModel;
use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Helpers\BaseAPIController;

class ChangePassword extends BaseAPIController {
    function post_xhr() {
        if ($this->checkAuth()) {
            if (!empty($_POST['oldPassword']) && !empty($_POST['newPassword'])) {
                $headers = getallheaders();
                $newUser = new AuthUserModel();
                $newUser->loadUser(mb_strtolower($headers['Auth-User']));
                if ($newUser->setPassword($_POST['oldPassword'], $_POST['newPassword'])) {
                    echo json_encode(StatusReturn::S200());
                } else {
                    echo json_encode(StatusReturn::E400('Unknown Error: cp 18'));
                }
            } else {
                echo json_encode(StatusReturn::E400('Unknown Error: cp 21'));
            }
        }
    }
}