<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Helpers\BaseAPIController;
use \PHP_REST_API\Data\ICOTransactionsData;

class AdminXPubs extends BaseAPIController {
    function get_xhr() {
        if ($this->checkAuth()) {
            echo json_encode(StatusReturn::S200(ICOTransactionsData::getXPubs()), JSON_NUMERIC_CHECK);
        }
    }

    function post_xhr() {
        if ($this->checkAuth()) {
            echo json_encode(StatusReturn::S200(Array("id" => ICOTransactionsData::insertXPub($_POST['xPub']))), JSON_NUMERIC_CHECK);
        }
    }
}