<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Helpers\BaseAPIController;
use \PHP_REST_API\Data\ICOTransactionsData;
use \PHP_REST_API\Data\BlockchainData;
use \PHP_REST_API\Models\BlockchainModel;

class AdminBitcoinTransaction extends BaseAPIController {
    function get_xhr() {
        if ($this->checkAuth()) {
            echo json_encode(StatusReturn::S200(ICOTransactionsData::getBitcoinTransactions()), JSON_NUMERIC_CHECK);
        }
    }
    function post_xhr() {
        if ($this->checkAuth()) {
            if (isset($_POST['tokenSaleID']) && is_numeric($_POST['tokenSaleID'])) {
                $transaction = ICOTransactionsData::getTransactions($_POST['tokenSaleID']);
                $expectedAmount = $transaction['paidBTC'] * 100000000;

                $blockchain = New BlockchainModel(_BLOCKCHAIN_API_KEY_);
                $address = $blockchain->getNewAddress($expectedAmount, $transaction['userID'], $_POST['tokenSaleID']);

                $id = BlockchainData::getAddressID($address);

                echo json_encode(StatusReturn::S200(Array("id" => $id, "address" => $address, "expectedPayment" => ($expectedAmount/100000000), "receivedPayment" => 0, "blocksConfirmed" => 0, "transactionHash" => "None")), JSON_NUMERIC_CHECK);
            } else {
                echo json_encode(StatusReturn::E400("Missing Data"));
            }

        }
    }
}