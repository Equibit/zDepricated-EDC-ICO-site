<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Helpers\BaseAPIController;
use \PHP_REST_API\Models\BitcoinChartsModel;

class CrowdSaleProgress extends BaseAPIController {
    function get_xhr() {
        if ($this->checkAuth()) {

            $bitcoinCharts = new BitcoinChartsModel();
            $bitcoinPrice = $bitcoinCharts->getBitCoinPricePerDollar();

            $data = Array(
                "btcPrices" => Array(2*$bitcoinPrice, 2.50*$bitcoinPrice, 3.13*$bitcoinPrice, 3.91*$bitcoinPrice, 4.89*$bitcoinPrice, 6.11*$bitcoinPrice, 7.64*$bitcoinPrice, 9.55*$bitcoinPrice, 11.94*$bitcoinPrice, 14.93*$bitcoinPrice),
                "eqbRemaining" => Array(0, 0, 50000, 100000, 100000, 100000, 100000, 100000, 100000, 100000)
            );

            echo json_encode(StatusReturn::S200($data));
        }
    }
}