<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Helpers\BaseAPIController;
use \PHP_REST_API\Data\AuthUserData;
use \PHP_REST_API\Data\ICOTransactionsData;

class ICOCheckSale extends BaseAPIController {
    function get_xhr($id) {
        if ($this->checkAuth()) {
            $headers = getallheaders();
            $userID = AuthUserData::getUserIDByUserName($headers['Auth-User']);
            $saleConfirmed = ICOTransactionsData::checkTransaction($id, $userID);

            if ($saleConfirmed) {
                echo json_encode(StatusReturn::S200("Confirmed"));
            } else {
                echo json_encode(StatusReturn::E400("Not confirmed!"));
            }
        }
    }
}