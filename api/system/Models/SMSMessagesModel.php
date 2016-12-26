<?php
namespace PHP_REST_API\Models;

use \PHP_REST_API\Data\TextVariablesData;
use \PHP_REST_API\Data\CommTemplatesData;
use \Twilio\Rest\Client;

class SMSMessagesModel {
    public $templateName;
    public $templateSMS;
    public $toNumber;
    public $lang;
    public $variables;
    public $dataVariables;
    public $replaceVariables;

    public function __construct($toNumber, $templateName, $lang = _DEFAULT_LANGUAGE_) {
        $this->templateName = $templateName;
        $this->toNumber = $toNumber;
        $this->lang = $lang;

        $this->loadTemplates();
        $this->loadVariables();
    }

    public function loadTemplates() {
        $templates = CommTemplatesData::getCommunicationTemplateByName($this->templateName);
        $this->templateSMS = $templates['smsTemplate'];
    }

    public function loadVariables() {
        preg_match_all('/{{(.*?)}}/', $this->templateSMS, $matches);
        $this->variables = $matches[1];
        $this->replaceVariables = $matches[0];
    }

    public function addVariables($additionalVariables = null) {
        $this->getVariablesData();

        if (!is_null($additionalVariables)) {
            foreach ($additionalVariables[0] AS $key => $value) {
                if (is_array($found = array_keys($this->replaceVariables, $value))) $this->dataVariables[$found[0]] = $additionalVariables[1][$key];
            }
        }

        if (empty($this->variables)) {
            $this->applyVariables();
            return true;
        }
        return false;
    }

    public function getVariablesData() {
        foreach($this->variables AS $key => $value) {
            $textText = TextVariablesData::getTextVariable(mb_strtoupper($value), mb_strtolower($this->lang));
            if (!empty($textText)) {
                $this->dataVariables[$key] = $textText;
                unset($this->variables[$key]);
            } else {
                $textText = TextVariablesData::getTextVariable(mb_strtoupper($value), mb_strtolower(_DEFAULT_LANGUAGE_));
                if (!empty($textText)) {
                    $this->dataVariables[$key] = $textText;
                    unset($this->variables[$key]);
                }
            }
        }
    }

    public function applyVariables() {
        $this->templateSMS = str_replace($this->replaceVariables, $this->dataVariables, $this->templateSMS);
    }

    public function ready() {
        return (empty($this->variables) && !empty($this->templateText) && !empty($this->toNumber));
    }

    public function send() {
        if ($this->ready()) {
            $sid = _TWILIO_SID_;
            $token = _TWILIO_AUTH_TOKEN_;

            $client = new Client($sid, $token);
            $message = $client->messages->create(
                "+" . preg_replace("/[^0-9]/", "", $this->toNumber[0]),
                array(
                    'from' => _TWILIO_PHONE_,
                    'body' => $this->templateSMS
                )
            );

            return true;
        }
        return false;
    }
}