<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Models\AuthUserModel;
use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Helpers\BaseAPIController;

class ForgotPassword extends BaseAPIController {
    function post_xhr() {
        if ($this->checkAuth()) {
            $usernameOrEmail = mb_strtolower($_POST['usernameOrEmail']);
            if ((mb_strlen($usernameOrEmail) >= 8 && preg_match('/^[a-zA-Z0-9_\-]+$/', $usernameOrEmail)) || filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL)) {
                $secondFactor = mb_strtolower($_POST['secondFactor']);
                if (ctype_alnum($secondFactor) || empty($secondFactor)) {
                    $answer = mb_strtolower($_POST['answer']);
                    if (mb_strlen($answer) >= 6 || empty($answer)) {
                        $newPassword = $_POST['passwordForgot'];
                        $newRetypedPassword = $_POST['passwordRetypedForgot'];
                        if ($newPassword == $newRetypedPassword) {
                            $userForgot = new AuthUserModel();
                            $responseArr = $userForgot->forgotPassword($usernameOrEmail, $secondFactor, $answer, $newPassword);

                            if ($responseArr['continue'] == true) {
                                echo json_encode(StatusReturn::S200($responseArr));
                            } else {
                                echo json_encode(StatusReturn::E400('Unknown Error: fp 26'));
                            }
                        } else {
                            echo json_encode(StatusReturn::E400('Unknown Error: fp 29'));
                        }
                    } else {
                        echo json_encode(StatusReturn::E400('Unknown Error: fp 32'));
                    }
                } else {
                    echo json_encode(StatusReturn::E400('Unknown Error: fp 35'));
                }
            } else {
                echo json_encode(StatusReturn::E400('Unknown Error: fp 38'));
            }
        }
    }
}