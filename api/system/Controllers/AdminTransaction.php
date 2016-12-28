<?php
namespace PHP_REST_API\Controllers;

use PHP_REST_API\Data\AuthUserData;
use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Helpers\BaseAPIController;
use \PHP_REST_API\Data\ICOTransactionsData;

class AdminTransaction extends BaseAPIController {
    function get_xhr() {
        if ($this->checkAuth()) {

            $data = ICOTransactionsData::getAllTransactions();

            foreach ($data AS &$datum) {
                $datum['username'] = AuthUserData::getUserNameByID($datum['userID']);
            }
            unset($datum);

            echo json_encode(StatusReturn::S200($data), JSON_NUMERIC_CHECK);
        }
    }
}