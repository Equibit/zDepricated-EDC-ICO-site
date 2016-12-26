<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Data\AuthUserData;
use \PHP_REST_API\Data\ManageAPIKeysData;
use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Helpers\BaseAPIController;

class ManageAPIKeys extends BaseAPIController {
    function get_xhr($keyID = null) {
        if ($this->checkAuth()) {
            $headers = getallheaders();

            if (is_null($keyID)) {
                echo json_encode(StatusReturn::S200(ManageAPIKeysData::getManageAPIKeysData(AuthUserData::getUserIDByUserName($headers['Auth-User']))));
            } else {
                echo json_encode(StatusReturn::S200(ManageAPIKeysData::getManageAPIKeyData(AuthUserData::getUserIDByUserName($headers['Auth-User']), $keyID)));
            }
        }
    }
    function post_xhr($keyID = null) {
        if ($this->checkAuth()) {
            $headers = getallheaders();

            if (is_null($keyID)) {
                // todo: make sure the same public key isn't repeated!
                $characters = str_shuffle(_CHARS_FOR_SECOND_FACTOR_KEYS_);
                $charLen = strlen($characters) - 1;
                $string = '';
                for ($i = 0; $i < 21; $i++) $string .= $characters[mt_rand(0, $charLen)];

                $newPublic = "EDC" . mb_strtoupper($string);
                $newSecret = bin2hex(mcrypt_create_iv(_PASSWORD_SALT_IV_SIZE_, MCRYPT_DEV_URANDOM));
                echo json_encode(StatusReturn::S200(Array("id" =>
                    ManageAPIKeysData::insertAPIKey(AuthUserData::getUserIDByUserName($headers['Auth-User']), trim($_POST['keyDesc']), $newPublic, $newSecret)
                    , "keyPublic" => $newPublic
                    , "keySecret" => $newSecret)));
            } else {
                echo json_encode(StatusReturn::E400());
            }
        }
    }
    function delete_xhr($keyID) {
        if ($this->checkAuth()) {
            $headers = getallheaders();
            ManageAPIKeysData::delAPIKey(AuthUserData::getUserIDByUserName($headers['Auth-User']), $keyID);
            echo json_encode(StatusReturn::S200("Key Deleted"));
        }
    }
}