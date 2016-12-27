<?php
namespace PHP_REST_API\Models;

use \PHP_REST_API\Data\BitcoinPricesData;

class BitcoinChartsModel {
    private $lastUpdated;
    private $lastPrice;

    public function __construct() {
        $data = BitcoinPricesData::getPriceFromSource('BitcoinCharts');

        // get data
        // if too old
            // $jsonData = json_decode(file_get_contents("http://api.bitcoincharts.com/v1/weighted_prices.json"))
            // update
    }

    public function getBitCoinPrice() {
        return $this->lastPrice;
    }

    public function getBitCoinPricePerDollar() {
        return 1/$this->lastPrice;
    }

}