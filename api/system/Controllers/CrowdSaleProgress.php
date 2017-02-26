<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Helpers\BaseAPIController;
use \PHP_REST_API\Models\BitcoinChartsModel;
use \PHP_REST_API\Data\ICOTransactionsData;

class CrowdSaleProgress extends BaseAPIController {
    function get_xhr() {
        if ($this->checkAuth()) {

            $bitcoinCharts = new BitcoinChartsModel();
            $bitcoinPrice = $bitcoinCharts->getBitCoinPricePerDollar();
            $totalEQBSold = ICOTransactionsData::getTotalEQBSold();
            $totalEQBConfirmed = ICOTransactionsData::getTotalEQBConfirmed();
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

            $data = Array(
                "btcPrices" => Array(
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
                ),
                "eqbRemaining" => array_reverse($remainingArr),
                "eqbConfirmed" => $totalEQBConfirmed,
                "soldUSD" => ICOTransactionsData::getUSDTotal(),
                "soldBTC" => ((ICOTransactionsData::getManualBTCTotal() * 100000000) + ICOTransactionsData::getBTCReceivedTotal()),
                "btc2usd" => $bitcoinPrice
            );

            echo json_encode(StatusReturn::S200($data), JSON_NUMERIC_CHECK);
        }
    }
}