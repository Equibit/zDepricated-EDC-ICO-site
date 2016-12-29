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
    function post_xhr($id = null) {
        if ($this->checkAuth()) {
            if (is_null($id)) {
                echo json_encode(StatusReturn::S200(Array("id" => ICOTransactionsData::insertNewTransaction($_POST['userID'], $_POST['fundingLevel'], $_POST['numberEQB'], $_POST['paidBTC'], $_POST['paidUSD'], $_POST['completed'], $_POST['manualTransaction']))), JSON_NUMERIC_CHECK);
            } else {
                ICOTransactionsData::revokeTransaction($id);
                echo json_encode(StatusReturn::S200(Array("id" => $id)));
            }
        }
    }
    function delete_xhr($id) {
        if ($this->checkAuth()) {
            if (!is_null($id) && ICOTransactionsData::revokeTransaction($id)) {
                echo json_encode(StatusReturn::S200(Array("id" => $id)), JSON_NUMERIC_CHECK);
            } else {
                echo json_encode(StatusReturn::E400('Missing ID!'));
            }
        }
    }
}