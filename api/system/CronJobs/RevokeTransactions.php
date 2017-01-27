<?php
namespace PHP_REST_API\CronJobs;

use \PHP_REST_API\Data\ICOTransactionsData;

class RevokeTransactions {
    public function __construct() {
        $transactions = ICOTransactionsData::getRevokingBitcoinTransactions();

        foreach ($transactions AS $salesID) {
            ICOTransactionsData::revokeTransaction($salesID['tokenSaleID']);
        }
    }
}