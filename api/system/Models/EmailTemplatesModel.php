<?php
namespace PHP_REST_API\Models;

use \PHP_REST_API\Data\TextVariablesData;
use \PHP_REST_API\Data\CommTemplatesData;
use \PHPMailer;

class EmailTemplatesModel {
    public $templateName;
    public $templateHTML;
    public $templateText;
    public $templateSubject;
    public $lang;
    public $variables;
    public $dataVariables;
    public $replaceVariables;
    public $missingVariables;
    public $toEmailAddresses;
    public $mail;

    public function __construct($toEmailAddresses, $templateName, $lang = _DEFAULT_LANGUAGE_) {

        // todo: check that the template exists
        $this->templateName = $templateName;
        $this->toEmailAddresses = $toEmailAddresses;
        $this->lang = $lang;

        $this->loadTemplates();
        $this->loadVariables();
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

    public function loadTemplates() {
        $templates = CommTemplatesData::getCommunicationTemplateByName($this->templateName);
        $this->templateHTML = $templates['htmlTemplate'];
        $this->templateText = $templates['textTemplate'];
        $this->templateSubject = $templates['subject'];
    }

    public function loadVariables() {
        preg_match_all('/{{(.*?)}}/', $this->templateHTML, $matches);
        $this->variables = $matches[1];
        $this->replaceVariables = $matches[0];
        preg_match_all('/{{(.*?)}}/', $this->templateText, $matches);
        $this->variables = array_unique(array_merge($this->variables,$matches[1]));
        $this->replaceVariables = array_unique(array_merge($this->replaceVariables,$matches[0]));
        preg_match_all('/{{(.*?)}}/', $this->templateSubject, $matches);
        $this->variables = array_unique(array_merge($this->variables,$matches[1]));
        $this->replaceVariables = array_unique(array_merge($this->replaceVariables,$matches[0]));
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
        $this->templateHTML = str_replace($this->replaceVariables, $this->dataVariables, $this->templateHTML);
        $this->templateText = str_replace($this->replaceVariables, $this->dataVariables, $this->templateText);
        $this->templateSubject = str_replace($this->replaceVariables, $this->dataVariables, $this->templateSubject);
    }

    public function ready() {
        return (empty($this->variables) && !empty($this->templateHTML) && !empty($this->templateText) && !empty($this->templateSubject) && !empty($this->toEmailAddresses));
    }

    public function send() {
        if (count($this->toEmailAddresses) == 1 && $this->ready()) {
            return $this->sendEmail();
        } else if (count($this->toEmailAddresses) > 1 && $this->ready()) {
            // todo: add multi sender? Possibly to send to all accounts
        }

        return false;
    }

    public function sendEmail() {
        $mail = new PHPMailer;

        if (_IN_DEVELOPMENT_ && _FULL_DEBUG_) {
            $mail->SMTPDebug = 3;
            ob_start();
        }

        $mail->isSMTP();
        $mail->Host = _EMAIL_HOSTS_;
        $mail->SMTPAuth = true;
        $mail->Username = _EMAIL_ADDRESS_;
        $mail->Password = _EMAIL_PASSWORD_;
        //if (!_IN_DEVELOPMENT_) $mail->SMTPSecure = 'tls';
        //if (!_IN_DEVELOPMENT_) $mail->Port = 587; else
        $mail->Port = 25;
        if (file_exists(_EMAIL_TEMPLATES_LOGO_)) $mail->addEmbeddedImage(_EMAIL_TEMPLATES_LOGO_, 'logo');

        //if (_IN_DEVELOPMENT_) {
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
        //}

        $mail->setFrom(_EMAIL_ADDRESS_, _COMPANY_NAME_);
        $mail->addReplyTo(_EMAIL_ADDRESS_, _COMPANY_NAME_);
        $mail->addAddress($this->toEmailAddresses[0]);

        $mail->isHTML(true);

        $mail->Subject = $this->templateSubject;
        $mail->Body = $this->templateHTML;
        $mail->AltBody = $this->templateText;

        $results = $mail->send();

        if (_IN_DEVELOPMENT_ && _FULL_DEBUG_) {
            $debugOutput = ob_get_contents();
            ob_end_clean();

            file_put_contents('SendError.txt', $mail->ErrorInfo . "\n\r" . $debugOutput, FILE_APPEND);
        }

        return $results;
    }
}