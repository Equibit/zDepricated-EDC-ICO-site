<?php
namespace PHP_REST_API\Data;

use \PHP_REST_API\Helpers\MySQL;
use \PDO;

class ICOTransactionsData {

    public static function getBitcoinTransactions() {
        $query = MySQL::getInstance()->prepare("SELECT AuthUser.userName, address, indexReturned, expectedPayment, receivedPayment, blocksConfirmed, transactionHash, UNIX_TIMESTAMP(timeDate) AS timeDate FROM BlockchainAddresses LEFT JOIN AuthUser ON AuthUser.userID=BlockchainAddresses.userID ORDER BY timeDate DESC");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getRevokingBitcoinTransactions() {
        $query = MySQL::getInstance()->prepare("SELECT tokenSaleID FROM BlockchainAddresses WHERE blocksConfirmed=-1 AND NOW() >= DATE_ADD(timeDate, INTERVAL 300 SECOND)");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function checkTransaction($tokenSaleID, $userID) {
        $query = MySQL::getInstance()->prepare("SELECT blocksConfirmed FROM BlockchainAddresses WHERE tokenSaleID=:tokenSaleID AND userID=:userID");
        $query->bindValue(':tokenSaleID', $tokenSaleID);
        $query->bindValue(':userID', $userID);
        $query->execute();
        $temp = $query->fetch(PDO::FETCH_ASSOC);
        return ($temp['blocksConfirmed'] >= 0);
    }

    public static function getUSDTotal() {
        $query = MySQL::getInstance()->prepare("SELECT SUM(paidUSD) AS totalUSD FROM tokenSales WHERE manualTransaction=1 AND completed=1");
        $query->execute();
        $temp = $query->fetch(PDO::FETCH_ASSOC);
        return $temp['totalUSD'];
    }

    public static function getManualBTCTotal() {
        $query = MySQL::getInstance()->prepare("SELECT SUM(paidBTC) AS totalBTC FROM tokenSales WHERE manualTransaction=1 AND completed=1");
        $query->execute();
        $temp = $query->fetch(PDO::FETCH_ASSOC);
        return $temp['totalBTC'];
    }

    public static function getBTCReceivedTotal() {
        $query = MySQL::getInstance()->prepare("SELECT SUM(receivedPayment) AS totalBTC FROM BlockchainAddresses WHERE blocksConfirmed >= 4");
        $query->execute();
        $temp = $query->fetch(PDO::FETCH_ASSOC);
        return $temp['totalBTC'];
    }

    public static function getXPubs() {
        $query = MySQL::getInstance()->prepare("SELECT id, xPub, gap FROM BlockchainxPubs");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function insertXPub($xPub) {
        $dataInstance = MySQL::getInstance();
        $query = $dataInstance->prepare("INSERT INTO BlockchainxPubs (xPub) VALUES (:xPub)");
        $query->bindValue(':xPub', $xPub);
        $query->execute();
        return $dataInstance->lastInsertId();
    }

    public static function getTotalEQBSold() {
        $query = MySQL::getInstance()->prepare("SELECT SUM(numberEQB) AS numberEQB FROM tokenSales WHERE rejected=0");
        $query->execute();
        $temp = $query->fetch(PDO::FETCH_ASSOC);
        return $temp['numberEQB'];
    }

    public static function getTotalEQBConfirmed() {
        $query = MySQL::getInstance()->prepare("SELECT SUM(numberEQB) AS numberEQB FROM tokenSales WHERE rejected=0 AND completed=1");
        $query->execute();
        $temp = $query->fetch(PDO::FETCH_ASSOC);
        return $temp['numberEQB'];
    }

    public static function getUserTransactions($userID) {
        $query = MySQL::getInstance()->prepare("SELECT fundingLevel, numberEQB, paidUSD, paidBTC, UNIX_TIMESTAMP(timeDate) AS timeDate, completed, rejected FROM tokenSales WHERE userID=:userID ORDER BY timeDate");
        $query->bindValue(':userID', $userID);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getTransactions($id) {
        $query = MySQL::getInstance()->prepare("SELECT userID, fundingLevel, numberEQB, paidUSD, paidBTC, UNIX_TIMESTAMP(timeDate) AS timeDate, completed FROM tokenSales WHERE id=:id");
        $query->bindValue(':id', $id);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public static function getTotalUserTransactions($userID) {
        $query = MySQL::getInstance()->prepare("SELECT SUM(numberEQB) as numberEQB FROM tokenSales WHERE userID=:userID AND rejected=0 AND completed=1");
        $query->bindValue(':userID', $userID);
        $query->execute();
        $temp = $query->fetch(PDO::FETCH_ASSOC);
        return $temp['numberEQB'];
    }

    public static function getAllTransactions() {
        $query = MySQL::getInstance()->prepare("SELECT id, userID, fundingLevel, numberEQB, paidUSD, paidBTC, UNIX_TIMESTAMP(timeDate) AS timeDate, completed, rejected, manualTransaction FROM tokenSales ORDER BY timeDate DESC");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function insertNewTransaction($userID, $fundingLevel, $numberEQB, $paidBTC, $paidUSD, $completed, $manualTransaction) {
        $dataInstance = MySQL::getInstance();
        $query = $dataInstance->prepare("INSERT INTO tokenSales (userID, fundingLevel, numberEQB, paidBTC, paidUSD, completed, timeDate, manualTransaction) VALUES (:userID, :fundingLevel, :numberEQB, :paidBTC, :paidUSD, :completed, FROM_UNIXTIME(:timeDate), :manualTransaction)");
        $query->bindValue(':userID', $userID);
        $query->bindValue(':fundingLevel', $fundingLevel);
        $query->bindValue(':numberEQB', $numberEQB);
        $query->bindValue(':paidBTC', $paidBTC);
        $query->bindValue(':paidUSD', $paidUSD);
        $query->bindValue(':completed', $completed);
        $query->bindValue(':timeDate', time());
        $query->bindValue(':manualTransaction', $manualTransaction);
        $query->execute();
        return $dataInstance->lastInsertId();
    }

    public static function revokeTransaction($id) {
        $query = MySQL::getInstance()->prepare("UPDATE tokenSales SET rejected=1 WHERE id=:id");
        $query->bindValue(':id', $id);
        return $query->execute();
    }

    public static function confirmTransaction($id) {
        $query = MySQL::getInstance()->prepare("UPDATE tokenSales SET completed=1, rejected=0 WHERE id=:id");
        $query->bindValue(':id', $id);
        return $query->execute();
    }

    public static function updateEQBNumber($id, $numberEQB) {
        $query = MySQL::getInstance()->prepare("UPDATE tokenSales SET numberEQB=:numberEQB WHERE id=:id");
        $query->bindValue(':id', $id);
        $query->bindValue(':numberEQB', $numberEQB);
        return $query->execute();
    }

}