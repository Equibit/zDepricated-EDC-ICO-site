<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Models\AuthUserModel;
use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Helpers\BaseAPIController;

class AdminSendEmail extends BaseAPIController {
    function post_xhr($templateName) {
        if ($this->checkAuth()) {
            $headers = getallheaders();
            $newUser = new AuthUserModel();
            $newUser->loadUser(mb_strtolower($headers['Auth-User']));

            echo json_encode(StatusReturn::S200($newUser->sendEmailNotification($templateName, null, $_POST['useLanguages'])), JSON_NUMERIC_CHECK);
        }
    }
}