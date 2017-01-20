<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Helpers\BaseAPIController;
use \PHP_REST_API\Data\ICOTransactionsData;

class AdminGetBitcoinAddress extends BaseAPIController {
    function post_xhr() {
        if ($this->checkAuth()) {
            
            echo json_encode(StatusReturn::S200(), JSON_NUMERIC_CHECK);
        }
    }
}