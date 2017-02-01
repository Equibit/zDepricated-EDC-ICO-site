<?php
namespace PHP_REST_API\Controllers;

use PHP_REST_API\Data\BlockchainData;
use PHP_REST_API\Data\ICOTransactionsData;
use \PHP_REST_API\Helpers\BaseAPIController;
use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Data\AvailablePaymentMethodsData;

class BlockchainIPN extends BaseAPIController {
    function get() {
        if ($this->checkAuth()) {
            if (AvailablePaymentMethodsData::hasBlockchain()) {

                $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                $vari = Array();

                parse_str(parse_url($url, PHP_URL_QUERY), $vari);

                $secret = (isset($vari['secret']) ? $vari['secret'] : '');
                $tsid = (isset($vari['tsid']) ? $vari['tsid'] : '');
                $address = (isset($vari['address']) ? $vari['address'] : '');

                if ($secret != '' && $tsid != '' && $address != '' && BlockchainData::findTransaction($tsid, $secret, $address)) {
                    $expected_payment = BlockchainData::getTransactionExpectedAmount($tsid, $secret, $address);
                    $value_in_satoshi = $vari['value'];
                    $transaction_hash = $vari['transaction_hash'];
                    $confirmation = $vari['confirmations'];

                    BlockchainData::updateTransaction($tsid, $value_in_satoshi, $transaction_hash, $confirmation);

                    if ($value_in_satoshi < $expected_payment) {
                        $transaction = ICOTransactionsData::getTransactions($tsid);
                        $perBTC = ($expected_payment / $transaction['numberEQB']);
                        $newNumber = $value_in_satoshi / $perBTC;
                        ICOTransactionsData::updateEQBNumber($tsid, $newNumber);
                    }

                    if ($vari['confirmations'] >= 4) {
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