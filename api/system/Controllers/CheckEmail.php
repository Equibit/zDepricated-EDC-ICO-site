<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Data\AuthUserData;
use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Helpers\BaseAPIController;

class CheckEmail extends BaseAPIController {
    function get_xhr($email) {
        if ($this->checkAuth()) {
            if (!AuthUserData::emailExist(mb_strtolower($email))) echo json_encode(StatusReturn::S200());
            else echo json_encode(StatusReturn::E400('Email Already Being Used!'));
        }
    }
}