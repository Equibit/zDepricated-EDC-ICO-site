<?php
namespace PHP_REST_API\Models;

use \PHP_REST_API\Data\BitcoinPricesData;

class BitcoinChartsModel {
    private $lastPrice;

    public function __construct() {
        $data = BitcoinPricesData::getPriceFromSource('BitcoinCharts');

        if (count($data) == 0 || $data['lastUpdate'] < time() - 900) {
            $jsonData = json_decode(file_get_contents("http://api.bitcoincharts.com/v1/weighted_prices.json"), true);
            $this->lastPrice = $jsonData['USD']['24h'];
            if (!is_null($this->lastPrice)) {
                BitcoinPricesData::updatePriceForSource($this->lastPrice, 'BitcoinCharts');
            }
        } else {
            $this->lastPrice = $data['lastPrice'];
        }
    }

    public function getBitCoinPrice() {
        return $this->lastPrice;
    }

    public function getBitCoinPricePerDollar() {
        return 1/$this->lastPrice;
    }

}