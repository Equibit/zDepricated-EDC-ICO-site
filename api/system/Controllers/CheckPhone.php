<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Data\AuthUserData;
use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Helpers\BaseAPIController;

class CheckPhone extends BaseAPIController {
    function get_xhr($phone) {
        if ($this->checkAuth()) {
            if (!AuthUserData::phoneExist(trim(preg_replace("/[^0-9]/","",$phone)))) echo json_encode(StatusReturn::S200());
            else echo json_encode(StatusReturn::E400('Email Already Being Used!'));
        }
    }
}