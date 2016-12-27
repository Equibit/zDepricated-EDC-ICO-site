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
                "btcPrices" => Array(
                    number_format(2*$bitcoinPrice,5),
                    number_format(2.50*$bitcoinPrice,5),
                    number_format(3.13*$bitcoinPrice,5),
                    number_format(3.91*$bitcoinPrice,5),
                    number_format(4.89*$bitcoinPrice,5),
                    number_format(6.11*$bitcoinPrice,5),
                    number_format(7.64*$bitcoinPrice,5),
                    number_format(9.55*$bitcoinPrice,5),
                    number_format(11.94*$bitcoinPrice,5),
                    number_format(14.93*$bitcoinPrice,5)
                ),
                "eqbRemaining" => Array(50000, 100000, 100000, 100000, 100000, 100000, 100000, 100000, 100000, 100000)
            );

            echo json_encode(StatusReturn::S200($data));
        }
    }
}