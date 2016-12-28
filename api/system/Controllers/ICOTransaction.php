<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Helpers\BaseAPIController;
use \PHP_REST_API\Data\AuthUserData;
use \PHP_REST_API\Data\ICOTransactionsData;

class ICOTransaction extends BaseAPIController {
    function get_xhr() {
        if ($this->checkAuth()) {
            $headers = getallheaders();
            echo json_encode(StatusReturn::S200(ICOTransactionsData::getUserTransactions(AuthUserData::getUserIDByUserName($headers['Auth-User']))));
        }
    }
}