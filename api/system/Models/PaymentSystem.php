<?php
namespace PHP_REST_API\Models;

use \PHP_REST_API\Data\AuthUserPaymentProfilesData;
use \PHP_REST_API\Data\AuthUserData;
use \PHP_REST_API\Data\AuthUserInvoicesData;
use \PHP_REST_API\Data\AvailablePaymentMethodsData;
use \PHP_REST_API\Helpers\PaymentMethod;
use \Beanstream\Gateway;
use \Beanstream\Exception;
use \Bitpay;

class PaymentSystem {
    private $userID;
    private $paymentProfiles;
    private $currentInvoices;
    private $billingHistory;

    public function __construct() {}

    public function loadUser($authUser) {
        if (AuthUserData::userExistConfirmed($authUser)) {
            $this->userID = AuthUserData::getUserIDByUserName($authUser);
            return true;
        }

        return false;
    }

    public function getAllBillingInfo() {
        return Array("profiles" => $this->getPaymentProfiles(), "history" => $this->getPaymentHistory(), "invoices" => $this->getCurrentInvoices());
    }

    public function loadPaymentProfiles() {
        $this->paymentProfiles = AuthUserPaymentProfilesData::getPaymentProfiles($this->userID);
    }

    public function loadPaymentHistory() {
        $this->billingHistory = AuthUserInvoicesData::getPaymentHistory($this->userID);
    }

    public function loadCurrentInvoices() {
        $this->currentInvoices = AuthUserInvoicesData::getCurrentInvoices($this->userID);
    }
    
    /*
     *      Actions
     */
    public function makePayment($invoiceIDs, $paymentMethodID) {
        $amount = 0;
        $invoiceList = '';
        $beanStreamProfileID = null;

        if (is_null($this->currentInvoices)) $this->loadCurrentInvoices();
        foreach ($this->currentInvoices AS $invoiceValue) {
            $currentCount = 0;
            foreach ($invoiceIDs AS $invoiceID) {
                $currentCount++;
                if ($invoiceValue['invoiceID'] == $invoiceID) {
                    $amount += $invoiceValue['amount'];
                    $invoiceList .= $invoiceID;
                    if (count($invoiceIDs) > $currentCount) $invoiceList .=',';
                }
            }
        }

        if ($paymentMethodID == PaymentMethod::CreditCard && AvailablePaymentMethodsData::hasCreditCard()) {
            return $this->makePaymentCC($invoiceIDs, $invoiceList, $amount);
        } else if ($paymentMethodID == PaymentMethod::BitPay && AvailablePaymentMethodsData::hasBitPay()) {
            return $this->createBitPayInvoice($invoiceList, $amount);
        }

        return false;
    }

    public function updateInvoicesPending($invoiceIDs, $userID = null) {
        if (is_null($userID)) $userID = $this->userID;
        $return = true;
        foreach ($invoiceIDs as $invoiceID) {
            $return = AuthUserInvoicesData::updateInvoicePending($userID, $invoiceID) && $return;
        }
        return $return;
    }

    public function updateInvoicesPaid($invoiceIDs, $transactionID, $paymentMethodID, $userID = null) {
        if (is_null($userID)) $userID = $this->userID;
        $return = true;
        foreach ($invoiceIDs as $invoiceID) {
            $return = AuthUserInvoicesData::updateInvoicePaid($userID, $invoiceID, $paymentMethodID, $transactionID) && $return;
        }
        return $return;
    }

    /*
     *      Getters
     */
    public function getPaymentProfiles() {
        if (is_null($this->paymentProfiles)) $this->loadPaymentProfiles();
        return $this->paymentProfiles;
    }

    public function getPaymentHistory() {
        if (is_null($this->billingHistory)) $this->loadPaymentHistory();
        return $this->billingHistory;
    }

    public function getCurrentInvoices() {
        if (is_null($this->currentInvoices)) $this->loadCurrentInvoices();
        return $this->currentInvoices;
    }



    /*
     *
     *      BeanStream Stuff
     *      -----------------
     */
    public function delPaymentProfile($paymentProfileID) {
        if (AvailablePaymentMethodsData::hasCreditCard() && AuthUserPaymentProfilesData::delPaymentProfile($this->userID, $paymentProfileID) === 1) {
            $beanStream = new Gateway(_PAYMENT_MERCHANT_NUMBER_, _PAYMENT_PROFILE_API_KEY_, 'www', 'v1');

            if (is_null($this->paymentProfiles)) $this->loadPaymentProfiles();
            foreach ($this->paymentProfiles AS $value) {
                if ($value['paymentProfileID'] == $paymentProfileID) {
                    if (_BEAN_STREAM_PRODUCTION_) $beanStream->profiles()->deleteProfile($value['beanStreamProfileID']);
                }
            }
            return true;
        }
        return false;
    }
    
    public function makePaymentCC($invoiceIDs, $invoiceList, $amount) {
        if (!isset($_POST['paymentProfileID'])) return false;

        $beanStream = new Gateway(_PAYMENT_MERCHANT_NUMBER_, _PAYMENT_API_KEY_, 'www', 'v1');
        $beanStreamProfileID = null;

        if (is_null($this->paymentProfiles)) $this->loadPaymentProfiles();
        foreach ($this->paymentProfiles AS $value) {
            if ($value['paymentProfileID'] == $_POST['paymentProfileID']) {
                $beanStreamProfileID = $value['beanStreamProfileID'];
            }
        }

        $profile_payment_data = array(
            'order_number' => $invoiceList,
            'amount' => $amount
        );

        if (_BEAN_STREAM_PRODUCTION_) {
            try {
                $result = $beanStream->payments()->makeProfilePayment($beanStreamProfileID, 1, $profile_payment_data, TRUE);
                $transaction_id = $result['id'];
                if ($result['approved']) {
                    // todo: foreach over invoice list?
                    return $this->updateInvoicesPaid($invoiceIDs, $transaction_id, PaymentMethod::CreditCard);
                } else {
                    // todo: record failure somewhere?
                    return false;
                }
            } catch (Exception $e) {
                return false;
            }
        } else {
            return $this->updateInvoicesPaid($invoiceIDs, uniqid(), PaymentMethod::CreditCard);
        }
    }
    
    public function addPaymentProfile($cardType, $cardEnding, $cardName, $legatoCode) {
    if (AvailablePaymentMethodsData::hasCreditCard()) {
        $beanStream = new Gateway(_PAYMENT_MERCHANT_NUMBER_, _PAYMENT_PROFILE_API_KEY_, 'www', 'v1');

        $profile_create_token = array(
            'token' => array(
                'name' => $cardName,
                'code' => $legatoCode
            ),
            'billing' => array(
                'name' => ''
            )
        );

        if (_BEAN_STREAM_PRODUCTION_) {
            try {
                $beanStreamProfileID = $beanStream->profiles()->createProfile($profile_create_token);
                return AuthUserPaymentProfilesData::insertPaymentProfile($this->userID, $cardType, $cardEnding, $cardName, $beanStreamProfileID);
            } catch (Exception $e) {
                
                // todo: record failure somewhere

                file_put_contents('BeanStreamError.txt', print_r($e, true));

                return false;
            }
        } else {
            return AuthUserPaymentProfilesData::insertPaymentProfile($this->userID, $cardType, $cardEnding, $cardName, uniqid());
        }
    } else {
        return false;
    }
}


    /*
     *
     *      BitPay Stuff
     *      ------------
     */
    public function createBitPayKeys() {

        if (_BIT_PAY_PRODUCTION_) {
            $privateKey = new Bitpay\PrivateKey(_KEY_STORAGE_LOCATION_ . 'bitpay.pri');
        } else {
            $privateKey = new Bitpay\PrivateKey(_KEY_STORAGE_LOCATION_ . 'bitpaydev.pri');
        }

        $privateKey->generate();

        if (_BIT_PAY_PRODUCTION_) {
            $publicKey = new Bitpay\PublicKey(_KEY_STORAGE_LOCATION_ . 'bitpay.pub');
        } else {
            $publicKey = new Bitpay\PublicKey(_KEY_STORAGE_LOCATION_ . 'bitpaydev.pub');
        }
        
        $publicKey->setPrivateKey($privateKey);
        $publicKey->generate();

        $storageEngine = new Bitpay\Storage\FilesystemStorage();

        $storageEngine->persist($privateKey);
        $storageEngine->persist($publicKey);
    }

    public function pairTokens($pairingCode) {
        $storageEngine = new Bitpay\Storage\FilesystemStorage();
        
        if (_BIT_PAY_PRODUCTION_) {
            $privateKey = $storageEngine->load(_KEY_STORAGE_LOCATION_ . 'bitpay.pri');
            $publicKey = $storageEngine->load(_KEY_STORAGE_LOCATION_ . 'bitpay.pub');
        } else {
            $privateKey = $storageEngine->load(_KEY_STORAGE_LOCATION_ . 'bitpaydev.pri');
            $publicKey = $storageEngine->load(_KEY_STORAGE_LOCATION_ . 'bitpaydev.pub');
        }
        
        $sin = Bitpay\SinKey::create()->setPublicKey($publicKey)->generate();

        $client = new Bitpay\Client\Client();

        if (_BIT_PAY_PRODUCTION_) {
            $network = new Bitpay\Network\Livenet();
        } else {
            $network = new Bitpay\Network\Testnet();
        }

        $adapter = new Bitpay\Client\Adapter\CurlAdapter();

        $client->setPrivateKey($privateKey);
        $client->setPublicKey($publicKey);
        $client->setNetwork($network);
        $client->setAdapter($adapter);

        $token = $client->createToken(
            array(
                'pairingCode' => $pairingCode,
                'label'       => 'Auto-CMS',
                'id'          => (string) $sin,
            )
        );

        $persistThisValue = $token->getToken();

        return Array('Token' => $persistThisValue);
    }

    public function createBitPayInvoice($invoiceList, $amount) {
        if ($invoiceList != '' && !empty($amount)) {
            $storageEngine = new Bitpay\Storage\FilesystemStorage();

            if (_BIT_PAY_PRODUCTION_) {
                $privateKey = $storageEngine->load(_KEY_STORAGE_LOCATION_ . 'bitpay.pri');
                $publicKey = $storageEngine->load(_KEY_STORAGE_LOCATION_ . 'bitpay.pub');
            } else {
                $privateKey = $storageEngine->load(_KEY_STORAGE_LOCATION_ . 'bitpaydev.pri');
                $publicKey = $storageEngine->load(_KEY_STORAGE_LOCATION_ . 'bitpaydev.pub');
            }

            $client = new Bitpay\Client\Client();

            if (_BIT_PAY_PRODUCTION_) {
                $network = new Bitpay\Network\Livenet();
            } else {
                $network = new Bitpay\Network\Testnet();
            }

            $adapter = new Bitpay\Client\Adapter\CurlAdapter();

            $client->setPrivateKey($privateKey);
            $client->setPublicKey($publicKey);
            $client->setNetwork($network);
            $client->setAdapter($adapter);

            $token = new Bitpay\Token();

            if (_BIT_PAY_PRODUCTION_) {
                $token->setToken(_BIT_PAY_TOKEN_LIVE_NET_);
            } else {
                $token->setToken(_BIT_PAY_TOKEN_TEST_NET_);
            }

            $client->setToken($token);

            $item = new Bitpay\Item();

            $item->setCode($invoiceList);
            $item->setDescription('');
            $item->setPrice($amount);

            $invoice = new Bitpay\Invoice();

            $invoice->setItem($item);
            $invoice->setPosData('{"invoiceList": "' . $invoiceList . '", "amount": "' . $amount . '", "userID": ' . $this->userID . '}');
            $invoice->setNotificationUrl(_DOMAIN_API_HOST_ . '/bit-pay-ipn/');
            $invoice->setCurrency(new Bitpay\Currency('USD'));
            try {
                $client->createInvoice($invoice);
            } catch (\Exception $e) {
                // todo: record failure somewhere? and turn off bitPay until problem is fixed
                //PaymentSystemData::turnOffBitPay(); cant turn it off unless we open up the IPN Need to decide.

                $error = Array('Request' => $client->getRequest(), 'Response' => $client->getResponse());
                file_put_contents('bitPayError.txt', print_r($error, true));
            }
            return $invoice->getUrl();
        }
        return false;
    }
    public function confirmBitPayInvoice($bitPayInvoice) {
        $storageEngine = new Bitpay\Storage\FilesystemStorage();

        if (_BIT_PAY_PRODUCTION_) {
            $privateKey = $storageEngine->load(_KEY_STORAGE_LOCATION_ . 'bitpay.pri');
            $publicKey = $storageEngine->load(_KEY_STORAGE_LOCATION_ . 'bitpay.pub');
        } else {
            $privateKey = $storageEngine->load(_KEY_STORAGE_LOCATION_ . 'bitpaydev.pri');
            $publicKey = $storageEngine->load(_KEY_STORAGE_LOCATION_ . 'bitpaydev.pub');
        }

        $client = new Bitpay\Client\Client();

        if (_BIT_PAY_PRODUCTION_) {
            $network = new Bitpay\Network\Livenet();
        } else {
            $network = new Bitpay\Network\Testnet();
        }

        $adapter = new Bitpay\Client\Adapter\CurlAdapter();

        $client->setPrivateKey($privateKey);
        $client->setPublicKey($publicKey);
        $client->setNetwork($network);
        $client->setAdapter($adapter);

        $token = new Bitpay\Token();

        if (_BIT_PAY_PRODUCTION_) {
            $token->setToken(_BIT_PAY_TOKEN_LIVE_NET_);
        } else {
            $token->setToken(_BIT_PAY_TOKEN_TEST_NET_);
        }

        $client->setToken($token);

        $invoice = $client->getInvoice($bitPayInvoice);
        
        return $invoice->getStatus();
    }
    public function confirmBitPayPaidComplete($bitPayInvoice) {
        $var = $this->confirmBitPayInvoice($bitPayInvoice);
        return ($var == 'complete' || $var == 'confirmed');
    }
    public function confirmBitPayPending($bitPayInvoice) {
        $var = $this->confirmBitPayInvoice($bitPayInvoice);
        return ($var == 'new' || $var == 'paid');
    }
}