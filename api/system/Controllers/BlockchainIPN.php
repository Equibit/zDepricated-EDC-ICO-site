<?php
namespace PHP_REST_API\Controllers;

use PHP_REST_API\Data\BlockchainData;
use PHP_REST_API\Data\ICOTransactionsData;
use \PHP_REST_API\Helpers\BaseAPIController;
use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Data\AvailablePaymentMethodsData;

class BlockchainIPN extends BaseAPIController {
    function post() {
        if ($this->checkAuth()) {
            if (AvailablePaymentMethodsData::hasBlockchain()) {
                $secret = $_GET['secret'];
                $tsid = $_GET['tsid'];
                $address = $_GET['address'];

                if (BlockchainData::findTransaction($tsid, $secret, $address)) {
                    $expected_payment = BlockchainData::getTransactionExpectedAmount($tsid, $secret, $address);
                    $value_in_satoshi = $_GET['value'];
                    $transaction_hash = $_GET['transaction_hash'];
                    $confirmation = $_GET['confirmations'];

                    BlockchainData::updateTransaction($tsid, $value_in_satoshi, $transaction_hash, $confirmation);

                    if ($value_in_satoshi < $expected_payment) {
                        $transaction = ICOTransactionsData::getTransactions($tsid);
                        $perBTC = ($expected_payment / $transaction['numberEQB']);
                        $newNumber = $value_in_satoshi / $perBTC;
                        ICOTransactionsData::updateEQBNumber($tsid, $newNumber);
                    }

                    if ($_GET['confirmations'] >= 4) {
                        ICOTransactionsData::confirmTransaction($tsid);
                        echo "*ok*";
                    } else {
                        echo "Waiting for confirmations";
                    }
                } else {
                    echo 'Invalid Data, transaction not found!';
                }
            } else {
                echo json_encode(StatusReturn::E404('404 Not Found!'));
            }
        }
    }
}