<?php
namespace PHP_REST_API\Data;

use \PHP_REST_API\Helpers\MySQL;
use \PDO;

class BlockchainData {

    public static function getAllXPubs() {
        $query = MySQL::getInstance()->prepare("SELECT id, xPub, gap FROM BlockchainxPubs");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getNextXPubs() {
        $query = MySQL::getInstance()->prepare("SELECT xPub FROM BlockchainxPubs ORDER BY gap LIMIT 1");
        $query->execute();
        $temp = $query->fetch(PDO::FETCH_ASSOC);
        return $temp['xPubs'];
    }

    public static function updateGap($id, $gap) {
        $query = MySQL::getInstance()->prepare("UPDATE BlockchainxPubs SET gap=:gap WHERE id=:id");
        $query->bindValue(':id', $id);
        $query->bindValue(':gap', $gap);
        return $query->execute();
    }

    public static function insertAddress($address, $indexReturned, $transactionSecret, $expectedPayment, $userID, $tokenSaleID) {
        $dataInstance = MySQL::getInstance();
        $query = $dataInstance->prepare("INSERT INTO BlockchainAddresses (address, indexReturned, transactionSecret, expectedPayment, userID, tokenSaleID) VALUES (:address, :indexReturned, :transactionSecret, :expectedPayment, :userID, :tokenSaleID)");
        $query->bindValue(':address', $address);
        $query->bindValue(':indexReturned', $indexReturned);
        $query->bindValue(':transactionSecret', $transactionSecret);
        $query->bindValue(':expectedPayment', $expectedPayment);
        $query->bindValue(':userID', $userID);
        $query->bindValue(':tokenSaleID', $tokenSaleID);
        $query->execute();
        return $dataInstance->lastInsertId();
    }

    public static function findTransaction($tokenSaleID, $secret, $address) {
        $query = MySQL::getInstance()->prepare("SELECT COUNT(*) AS countRows FROM BlockchainAddresses WHERE secret=:secret AND tokenSaleID=:tokenSaleID AND address=:address");
        $query->bindValue(':tokenSaleID', $tokenSaleID);
        $query->bindValue(':secret', $secret);
        $query->bindValue(':address', $address);
        $query->execute();
        $temp = $query->fetch(PDO::FETCH_ASSOC);
        return ($temp['countRows'] == 1);
    }

    public static function getTransactionExpectedAmount($tokenSaleID, $secret, $address) {
        $query = MySQL::getInstance()->prepare("SELECT expectedPayment FROM BlockchainAddresses WHERE secret=:secret AND tokenSaleID=:tokenSaleID AND address=:address");
        $query->bindValue(':tokenSaleID', $tokenSaleID);
        $query->bindValue(':secret', $secret);
        $query->bindValue(':address', $address);
        $query->execute();
        $temp = $query->fetch(PDO::FETCH_ASSOC);
        return $temp['expectedPayment'];
    }

    public static function updateTransaction($tokenSaleID, $receivedPayment, $transactionHash, $blocksConfirmed) {
        $query = MySQL::getInstance()->prepare("UPDATE BlockchainAddresses SET receivedPayment=:receivedPayment, transactionHash=:transactionHash, blocksConfirmed=:blocksConfirmed WHERE tokenSaleID=:tokenSaleID");
        $query->bindValue(':tokenSaleID', $tokenSaleID);
        $query->bindValue(':receivedPayment', $receivedPayment);
        $query->bindValue(':transactionHash', $transactionHash);
        $query->bindValue(':blocksConfirmed', $blocksConfirmed);
        return $query->execute();
    }

}