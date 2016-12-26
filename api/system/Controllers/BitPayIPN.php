<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Helpers\BaseAPIController;
use \PHP_REST_API\Helpers\PaymentMethods;
use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Models\PaymentSystem;
use \PHP_REST_API\Data\AvailablePaymentMethodsData;

class BitPayIPN extends BaseAPIController {
    function post() {
        if ($this->checkAuth()) {
            if (AvailablePaymentMethodsData::hasBitPay()) {
                $jsonObj = json_decode(file_get_contents("php://input"));
                
                if (json_last_error() == JSON_ERROR_NONE) {
                    //file_put_contents('IPNData.txt', print_r($jsonObj, true));
                    $payment = new PaymentSystem();
                    $posDataObj = json_decode($jsonObj->posData);
                    $invoiceArr = explode(',', $posDataObj->invoiceList);
                    
                    if (json_last_error() == JSON_ERROR_NONE) {
                        //file_put_contents('IPNPosData.txt', print_r($invoiceArr, true));
                        // todo: maybe confirm posData for extra Security?
                        
                        if ($payment->confirmBitPayPaidComplete($jsonObj->id)) {
                            // todo: compare amounts paid vs invoice amount
                            //file_put_contents('here1.txt', $posDataObj->userID);
                            $payment->updateInvoicesPaid($invoiceArr, $jsonObj->id, PaymentMethods::BitPay, $posDataObj->userID);
                        } else if ($payment->confirmBitPayPending($jsonObj->id)) {
                            //file_put_contents('here2.txt', $posDataObj->userID);
                            $payment->updateInvoicesPending($invoiceArr, $posDataObj->userID);
                        }
                        
                    } else {
                        // todo: record error somewhere
                    }

                    echo json_encode(StatusReturn::S200());
                    
                } else {
                    echo json_encode(StatusReturn::E400('Bad JSON!'));
                }
            } else {
                echo json_encode(StatusReturn::E404('404 Not Found!'));
            }
        }
    }
}