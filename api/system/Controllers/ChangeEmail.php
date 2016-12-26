<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Models\AuthUserModel;
use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Helpers\BaseAPIController;
use \PHP_REST_API\Data\AuthUserData;

class ChangeEmail extends BaseAPIController {
    function post_xhr() {
        if ($this->checkAuth()) {
            if (!empty($_POST['newEmail']) && !AuthUserData::emailExist($_POST['newEmail'])) {
                $headers = getallheaders();
                $newUser = new AuthUserModel();
                $newUser->loadUser(mb_strtolower($headers['Auth-User']));

                $emailCode = '';
                if (!empty($_POST['emailedCode'])) $emailCode = $_POST['emailedCode'];

                if ($newUser->changeEmail(mb_strtolower($_POST['newEmail']), $emailCode)) {
                    echo json_encode(StatusReturn::S200());
                } else {
                    echo json_encode(StatusReturn::E400('Unknown Error: ce 18'));
                }
            } else {
                echo json_encode(StatusReturn::E400('Unknown Error: ce 21'));
            }
        }
    }
}