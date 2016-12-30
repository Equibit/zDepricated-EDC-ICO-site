<?php
namespace PHP_REST_API\Controllers;

use PHP_REST_API\Data\ICOTransactionsData;
use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Helpers\BaseAPIController;
use \PHP_REST_API\Data\AuthUserData;
use \PHP_REST_API\Data\AuthUserRolesData;

class AdminUsers extends BaseAPIController {
    function get_xhr() {
        if ($this->checkAuth()) {
            $data = AuthUserData::getAllUserData();
            foreach ($data AS &$datum) $datum['eqbPurchased'] = ICOTransactionsData::getTotalUserTransactions($datum['id']);
            unset($datum);
            echo json_encode(StatusReturn::S200($data), JSON_NUMERIC_CHECK);
        }
    }
    function post_xhr($userID = null) {
        if ($this->checkAuth()) {
            if (!is_null($userID)) {

                AuthUserData::updateUser($_POST['id'],
                    ($_POST['accountLocked'] == 'true' || $_POST['accountLocked'] == 1 ? 1 : 0),
                    ($_POST['emailNotifications'] == 'true' || $_POST['emailNotifications'] == 1 ? 1 : 0),
                    ($_POST['emailConfirmed'] == 'true' || $_POST['emailConfirmed'] == 1 ? 1 : 0),
                    ($_POST['phoneConfirmed'] == 'true' || $_POST['phoneConfirmed'] == 1 ? 1 : 0));

                echo json_encode(StatusReturn::S200(Array('id' => $userID)), JSON_NUMERIC_CHECK);
            } else {
                echo json_encode(StatusReturn::E400('Missing or bad data! au 28'));
            }
        }
    }
    function delete_xhr($userID) {
        if ($this->checkAuth()) {
            AuthUserData::delAuthUser($userID);
            AuthUserRolesData::delAuthUserRoles($userID);
            echo json_encode(StatusReturn::S200(Array('id' => $userID)));
        }
    }
}