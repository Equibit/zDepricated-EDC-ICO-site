<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Models\AuthUserModel;
use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Helpers\BaseAPIController;

class SignUp extends BaseAPIController {
    function post_xhr() {
        if ($this->checkAuth()) {
            if (isset($_POST['user'], $_POST['answer'])
                && mb_strlen(trim($_POST['user'])) >= _USERNAME_MIN_LENGTH_ && preg_match('/^[a-zA-Z0-9_\-]+$/', $_POST['user'])
                && ((_EMAIL_CONFIRMATION_ && !empty($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) || !_EMAIL_CONFIRMATION_)
                && ((_PHONE_CONFIRMATION_ && !empty($_POST['phone'])) || (!_PHONE_CONFIRMATION_))
                && (!empty($_POST['question'] || !_REQUIRE_SECURITY_QUESTION_))
                && (mb_strlen(trim($_POST['answer'])) >= _SECURITY_ANSWER_MIN_LENGTH_ || !_REQUIRE_SECURITY_QUESTION_)
                && !empty($_POST['pass']) && !empty($_POST['retype']) && $_POST['pass'] == $_POST['retype']
                && (isset($_POST['factor']))) {

                $newUser = new AuthUserModel();

                $email = null;
                if (_EMAIL_CONFIRMATION_) $email = mb_strtolower(trim($_POST['email']));

                $phone = null;
                if (_PHONE_CONFIRMATION_) $phone = trim(preg_replace("/[^0-9]/","",$_POST['phone']));

                $lang = _DEFAULT_LANGUAGE_;
                if (isset($_POST['lang']) && mb_strlen($_POST['lang']) == 2 && ctype_alpha($_POST['lang'])) $lang = $_POST['lang'];

                $confirm = null;
                if (_EMAIL_CONFIRMATION_ && _PHONE_CONFIRMATION_ && isset($_POST['confirm']) && ($_POST['confirm'] == 'phone' || $_POST['confirm'] == 'email')) $confirm = $_POST['confirm'];

                if ($newUser->createUser(mb_strtolower($_POST['user']), $email, $phone, $_POST['pass'], $_POST['question'], mb_strtolower(trim($_POST['answer'])), trim($_POST['factor']), $lang, $confirm)) {
                    echo json_encode(StatusReturn::S200());
                } else {
                    echo json_encode(StatusReturn::E400('Unknown Error: su 37'));
                }
            } else {
                echo json_encode(StatusReturn::E400('Unknown Error: su 40'));
            }
        }
    }
}
