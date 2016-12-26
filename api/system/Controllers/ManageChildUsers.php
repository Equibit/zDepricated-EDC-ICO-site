<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Models\AuthUserModel;
use \PHP_REST_API\Models\AuthChildUserModel;
use \PHP_REST_API\Data\AuthUserData;
use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Helpers\BaseAPIController;
use \PHP_REST_API\Helpers\TwoFactor;

class ManageChildUsers extends BaseAPIController {
    function get_xhr($userID = null) {
        if ($this->checkAuth()) {

            $headers = getallheaders();
            $newUser = new AuthUserModel();
            $newUser->loadUser(mb_strtolower($headers['Auth-User']));

            if (is_null($userID)) {
                echo json_encode(StatusReturn::S200($newUser->getManageUsersData()));
            } else {
                $singleUser = $newUser->getManageUserData(mb_strtolower($userID));
                if (!is_null($singleUser)) echo json_encode(StatusReturn::S200($singleUser));
                else echo json_encode(StatusReturn::E400('User Name is not a child of this account!'));
            }
        }
    }
    function post_xhr($userID = null) {
        if ($this->checkAuth()) {
            if (is_null($userID)) {
                $userExists = AuthUserData::userExist(mb_strtolower($_POST['userName']));
                $emailExists = AuthUserData::emailExist(mb_strtolower($_POST['email']));

                if (mb_strlen($_POST['userName']) >= _USERNAME_MIN_LENGTH_
                    && !$userExists
                    && !empty($_POST['email'])
                    && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
                    && !$emailExists
                    && !empty($_POST['password'])
                    && isset($_POST['twoFactorType'])
                    && is_numeric($_POST['twoFactorType'])) {

                    $headers = getallheaders();
                    $subUser = new AuthChildUserModel(mb_strtolower($headers['Auth-User']));

                    $roles = Array();
                    if (isset($_POST['roles']) && is_array($_POST['roles'])) $roles = $_POST['roles'];

                    if ($newUserId = $subUser->createSubUser(mb_strtolower($_POST['userName']), mb_strtolower($_POST['email']), $_POST['password'], $_POST['twoFactorType'], $roles)) {
                        echo json_encode(StatusReturn::S200(Array('id' => $newUserId)), JSON_NUMERIC_CHECK);
                    } else {
                        echo json_encode(StatusReturn::E400('Unknown Error: mu 47'));
                    }
                } else if ($userExists) {
                    echo json_encode(StatusReturn::E400('User Exists!'));
                } else if ($emailExists) {
                    echo json_encode(StatusReturn::E400('Email Exists!'));
                } else {
                    echo json_encode(StatusReturn::E400('Missing roles or twoFactorType'));
                }
            } else {
                if (AuthUserData::userExistByID($userID)) {
                    $headers = getallheaders();
                    $subUser = new AuthChildUserModel(mb_strtolower($headers['Auth-User']), (int) $userID);

                    $allSuccess = true;
                    if (isset($_POST['newPassword'])) {
                        $allSuccess = $allSuccess && $subUser->updateSubUserPassword($_POST['newPassword']);
                    }

                    if (isset($_POST['twoFactorType']) && TwoFactor::isValidValue((int)$_POST['twoFactorType'])) {
                        $allSuccess = $allSuccess && $subUser->updateSubUserFactor($_POST['twoFactorType']);
                    }

                    if (isset($_POST['roles']) && is_array($_POST['roles'])) {
                        $allSuccess = $allSuccess && $subUser->updateSubUserRoles($_POST['roles']);
                    } else {
                        $allSuccess = $allSuccess && $subUser->updateSubUserRoles(Array());
                    }

                    if ($allSuccess) {
                        echo json_encode(StatusReturn::S200(Array('id' => $userID)), JSON_NUMERIC_CHECK);
                    } else {
                        echo json_encode(StatusReturn::E400('Some or All Changes Failed to Save!'));
                    }
                } else {
                    echo json_encode(StatusReturn::E400('User Name is not a child of this account!'));
                }
            }
        }
    }
    function delete_xhr($userID) {
        if ($this->checkAuth()) {
            if (AuthUserData::userExistByID($userID)) {
                $headers = getallheaders();
                $subUser = new AuthChildUserModel(mb_strtolower($headers['Auth-User']), (int) $userID);
                if ($subUser->delUser()) {
                    echo json_encode(StatusReturn::S200(Array("id" => $userID)));
                } else {
                    echo json_encode(StatusReturn::E400('Error'));
                }
            } else {
                echo json_encode(StatusReturn::E400('Unknown Error: mcu 96!'));
            }
        }
    }
}