<?php
namespace PHP_REST_API\Models;

use \PHP_REST_API\Data\TextVariablesData;
use \PHP_REST_API\Data\CommTemplatesData;

class CommTemplatesModel {
    public $templateName;

    public $templateSMS;
    public $templateHTML;
    public $templateText;
    public $templateSubject;

    public $variables;
    public $dataVariables;
    public $replaceVariables;

    public function __construct($templateName) {
        $this->templateName = $templateName;

        $this->loadTemplates();
        $this->loadVariables();
        $this->getVariablesData();
        $this->applyVariables();
    }

    public function getMissing() {
        return $this->variables;
    }

    public function countMissing() {
        unset($this->variables);
        $this->variables = array();

        $this->loadVariables();

        return count($this->variables);
    }

    public function loadTemplates() {
        $templates = CommTemplatesData::getCommunicationTemplateByName($this->templateName);
        $this->templateSMS = $templates['smsTemplate'];
        $this->templateHTML = $templates['htmlTemplate'];
        $this->templateText = $templates['textTemplate'];
        $this->templateSubject = $templates['subject'];
    }

    public function loadVariables() {
        preg_match_all('/{{(.*?)}}/', $this->templateSMS, $matches);
        $this->variables = $matches[1];
        $this->replaceVariables = $matches[0];
        preg_match_all('/{{(.*?)}}/', $this->templateHTML, $matches);
        $this->variables = array_unique(array_merge($this->variables,$matches[1]));
        $this->replaceVariables = array_unique(array_merge($this->replaceVariables,$matches[0]));
        preg_match_all('/{{(.*?)}}/', $this->templateText, $matches);
        $this->variables = array_unique(array_merge($this->variables,$matches[1]));
        $this->replaceVariables = array_unique(array_merge($this->replaceVariables,$matches[0]));
        preg_match_all('/{{(.*?)}}/', $this->templateSubject, $matches);
        $this->variables = array_unique(array_merge($this->variables,$matches[1]));
        $this->replaceVariables = array_unique(array_merge($this->replaceVariables,$matches[0]));
    }

    public function getVariablesData() {
        foreach($this->variables AS $key => $value) {
            $textText = TextVariablesData::getTextVariable(mb_strtoupper($value), mb_strtolower(_DEFAULT_LANGUAGE_));
            if (!empty($textText)) {
                $this->dataVariables[$key] = $textText;
                unset($this->variables[$key]);
            } else {
                $this->dataVariables[$key] = '{{' . $value . '}}';
            }
        }
    }

    public function applyVariables() {
        $this->templateText = str_replace($this->replaceVariables, $this->dataVariables, $this->templateText);
        $this->templateHTML = str_replace($this->replaceVariables, $this->dataVariables, $this->templateHTML);
        $this->templateSMS = str_replace($this->replaceVariables, $this->dataVariables, $this->templateSMS);
        $this->templateSubject = str_replace($this->replaceVariables, $this->dataVariables, $this->templateSubject);
    }
}