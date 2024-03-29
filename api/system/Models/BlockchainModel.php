<?php
namespace PHP_REST_API\Models;

use \PHP_REST_API\Data\BlockchainData;

class BlockchainModel {
    private $apiRoot = "https://api.blockchain.info/v2/receive";
    private $apiKey;

    public function __construct($key) {
        $this->apiKey = $key;
    }

    public function scanXPubs() {

        $xPubs2Scan = BlockchainData::getAllXPubs();

        foreach ($xPubs2Scan AS $xPub) {
            $response = file_get_contents($this->apiRoot . "/checkgap?xpub=" . $xPub['xPub'] . "&key=" . $this->apiKey);
            if ($response !== false) {
                $object = json_decode($response);

                if (json_last_error() == JSON_ERROR_NONE) {
                    if (isset($object->error)) BlockchainData::updateGap($xPub['id'], 0);
                    else if (isset($object->gap)) BlockchainData::updateGap($xPub['id'], $object->gap);
                }
            }
        }

    }

    public function getNewAddress($paymentAmount, $userID, $tokenSaleID) {
        $this->scanXPubs();
        $useXPub = BlockchainData::getNextXPubs();
        $secret = $this->createSecret();
        $callback = "https://ico.equibit.org/blockchain-ipn/?tsid=" . $tokenSaleID . "&secret=" . $secret;

        $response = file_get_contents($this->apiRoot . "?xpub=" . $useXPub . "&callback=" . urlencode($callback) . "&key=" . $this->apiKey);
        if ($response !== false) {
            $object = json_decode($response);

            if (json_last_error() == JSON_ERROR_NONE) {
                BlockchainData::insertAddress($object->address, $object->index, $secret, $paymentAmount, $userID, $tokenSaleID, $callback);

                return $object->address;
            }
        }
        return "Error";
    }

    public static function createSecret($baseLen = 0) {
        $useChars = _CHARS_FOR_SECOND_FACTOR_KEYS_;
        $characters = str_shuffle($useChars);
        $charLen = strlen($characters) - 1;
        $len = mt_rand($baseLen+5, $baseLen+10);

        $string = '';
        for ($i = 0; $i < $len; $i++) $string .= $characters[mt_rand(0, $charLen)];
        return mb_strtolower($string);
    }
}