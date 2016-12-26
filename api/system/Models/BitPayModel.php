<?php
namespace PHP_REST_API\Models;

use \Bitpay;

class BitPayModel {

    public function __construct() {}

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