<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Models\AuthUserModel;
use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Helpers\BaseAPIController;
use \PHP_REST_API\Helpers\TwoFactor;

class AccountSettings extends BaseAPIController {
    function get_xhr() {
        if ($this->checkAuth()) {
            $headers = getallheaders();
            $newUser = new AuthUserModel();
            $newUser->loadUser(mb_strtolower($headers['Auth-User']));
            
            echo json_encode(StatusReturn::S200($newUser->getSettings()), JSON_NUMERIC_CHECK);
        }
    }
    function post_xhr() {
        if ($this->checkAuth()) {
            if (isset($_POST['baseLang'], $_POST['twoFactorType'], $_POST['emailNotifications'], $_POST['phoneNotifications'])
                    && !empty($_POST['baseLang']) && TwoFactor::isValidValue($_POST['twoFactorType'], false)) {
                
                $headers = getallheaders();
                $newUser = new AuthUserModel();
                $newUser->loadUser(mb_strtolower($headers['Auth-User']));
                
                if ($newUser->setSettings($_POST['baseLang'], $_POST['twoFactorType'], ($_POST['emailNotifications'] == "true" || $_POST['emailNotifications'] == 1 ? 1:0), ($_POST['phoneNotifications'] == "true" || $_POST['phoneNotifications'] == 1 ? 1:0))) {
                    echo json_encode(StatusReturn::S200());
                } else {
                    echo json_encode(StatusReturn::E400('Failed to save settings!'));
                }
            } else {
                echo json_encode(StatusReturn::E400('Missing or bad data!'));
            }
        }
    }
}