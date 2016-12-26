<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Data\AuthUserData;
use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Helpers\BaseAPIController;

class CheckUsername extends BaseAPIController {
    function get_xhr($authUser) {
        if ($this->checkAuth()) {
            if (!AuthUserData::userExist(mb_strtolower($authUser))) echo json_encode(StatusReturn::S200());
            else echo json_encode(StatusReturn::E400('Username Exists Already!'));
        }
    }
}
