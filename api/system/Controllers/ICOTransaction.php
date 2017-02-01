<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Helpers\BaseAPIController;
use \PHP_REST_API\Data\AuthUserData;
use \PHP_REST_API\Data\ICOTransactionsData;
use \PHP_REST_API\Models\BlockchainModel;
use \PHP_REST_API\Models\BitcoinChartsModel;

class ICOTransaction extends BaseAPIController {
    function get_xhr() {
        if ($this->checkAuth()) {
            $headers = getallheaders();
            echo json_encode(StatusReturn::S200(ICOTransactionsData::getUserTransactions(AuthUserData::getUserIDByUserName($headers['Auth-User']))), JSON_NUMERIC_CHECK);
        }
    }
    function post_xhr() {
        if ($this->checkAuth()) {
            $headers = getallheaders();
            if (AuthUserData::userExist($headers['Auth-User'])) {

                $numberEQB = $_POST['numberEQB'];

                $bitcoinCharts = new BitcoinChartsModel();
                $bitcoinPrice = $bitcoinCharts->getBitCoinPricePerDollar();
                $totalEQBSold = ICOTransactionsData::getTotalEQBSold();
                $remainingArr = Array(100000, 100000, 100000, 100000, 100000, 100000, 100000, 100000, 100000, 100000);

                $totalLeft = 1000000 - $totalEQBSold;

                foreach ($remainingArr AS &$amount) {
                    if ($totalLeft > 100000) $totalLeft -= 100000;
                    else if ($totalLeft > 0) {
                        $amount = $totalLeft;
                        $totalLeft = 0;
                    } else {
                        $amount = 0;
                    }
                }
                $remainingArr = array_reverse($remainingArr);

                $btcPrices = Array(
                    number_format(2*$bitcoinPrice,6),
                    number_format(2.50*$bitcoinPrice,6),
                    number_format(3.13*$bitcoinPrice,6),
                    number_format(3.91*$bitcoinPrice,6),
                    number_format(4.89*$bitcoinPrice,6),
                    number_format(6.11*$bitcoinPrice,6),
                    number_format(7.64*$bitcoinPrice,6),
                    number_format(9.55*$bitcoinPrice,6),
                    number_format(11.94*$bitcoinPrice,6),
                    number_format(14.93*$bitcoinPrice,6)
                );

                $fundingLevel = 0;
                $expectedAmount = 0;
                $newNumberEQB = $numberEQB;
                $breakdown = Array();
                if ($numberEQB > array_sum($remainingArr)) {
                    $numberEQB = array_sum($remainingArr);
                }
                if ($numberEQB > 0) {
                    foreach ($remainingArr AS &$amountLeft) {
                        $fundingLevel++;
                        if ($amountLeft > 0) {
                            if ($amountLeft > $newNumberEQB) {
                                $expectedAmount += $btcPrices[$fundingLevel - 1] * $newNumberEQB;
                                $breakdown[] = Array("level" => $fundingLevel, "pricePer" => $btcPrices[$fundingLevel - 1], "numberEQB" => $newNumberEQB);
                                break;
                            } else if ($newNumberEQB > 0) {
                                $expectedAmount += $btcPrices[$fundingLevel - 1] * $amountLeft;
                                $breakdown[] = Array("level" => $fundingLevel, "pricePer" => $btcPrices[$fundingLevel - 1], "numberEQB" => $amountLeft);
                                $newNumberEQB -= $amountLeft;
                                $amountLeft = 0;
                            } else {
                                break;
                            }
                        }
                    }

                    $userID = AuthUserData::getUserIDByUserName($headers['Auth-User']);
                    $id = ICOTransactionsData::insertNewTransaction($userID, $fundingLevel, $numberEQB, $expectedAmount, null, 0, 0);

                    $expectedAmount *= 100000000;

                    $blockchain = New BlockchainModel(_BLOCKCHAIN_API_KEY_);
                    $address = $blockchain->getNewAddress($expectedAmount, $userID, $id);

                    echo json_encode(StatusReturn::S200(Array("id" => $id, "address" => $address, "expectedPayment" => ($expectedAmount / 100000000), "paidBTC" => ($expectedAmount / 100000000), "breakdown" => $breakdown)), JSON_NUMERIC_CHECK);
                } else {

                }
            } else {
                echo json_encode(StatusReturn::E400("Missing Data"));
            }

        }
    }
}