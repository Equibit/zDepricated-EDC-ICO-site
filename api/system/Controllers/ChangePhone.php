<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Models\AuthUserModel;
use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Helpers\BaseAPIController;
use \PHP_REST_API\Data\AuthUserData;

class ChangePhone extends BaseAPIController {
    function post_xhr() {
        if ($this->checkAuth()) {
            if (!empty($_POST['newPhone']) && !AuthUserData::phoneExist($_POST['newPhone'])) {
                $headers = getallheaders();
                $newUser = new AuthUserModel();
                $newUser->loadUser(mb_strtolower($headers['Auth-User']));

                $phoneCode = '';
                if (!empty($_POST['phoneCode'])) $phoneCode = $_POST['phoneCode'];

                if ($newUser->changePhone(trim(preg_replace("/[^0-9]/","",$_POST['newPhone'])), $phoneCode)) {
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