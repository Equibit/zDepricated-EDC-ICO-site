<?php
namespace PHP_REST_API\Models;

use \PHP_REST_API\Data\BitcoinPricesData;

class BitcoinChartsModel {
    private $lastPrice;
    private $loc = "http://api.bitcoincharts.com/v1/weighted_prices.json";

    public function __construct() {
        $data = BitcoinPricesData::getPriceFromSource('BitcoinCharts');
        $this->lastPrice = $data['lastPrice'];

        if (count($data) == 0 || $data['lastUpdate'] < time() - 900) {
            $curl = curl_init($this->loc);
            curl_setopt($curl, CURLOPT_NOBODY, true);
            $result = curl_exec($curl);

            if ($result !== false) {
                $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                if ($statusCode == 200) {
                    $jsonData = json_decode(file_get_contents($this->loc), true);

                    if (json_last_error() == JSON_ERROR_NONE) {
                        $this->lastPrice = $jsonData['USD']['24h'];
                        if (!is_null($this->lastPrice)) {
                            BitcoinPricesData::updatePriceForSource($this->lastPrice, 'BitcoinCharts');
                        }
                    }
                }
            }

            curl_close($curl);
        }
    }

    public function getBitCoinPrice() {
        return $this->lastPrice;
    }

    public function getBitCoinPricePerDollar() {
        if (is_null($this->lastPrice)) return 0;
        else return 1/$this->lastPrice;
    }

}