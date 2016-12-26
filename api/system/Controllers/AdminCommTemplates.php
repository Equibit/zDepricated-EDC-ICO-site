<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Helpers\BaseAPIController;
use \PHP_REST_API\Models\CommTemplatesModel;
use \PHP_REST_API\Data\CommTemplatesData;

class AdminCommTemplates extends BaseAPIController {
    function get_xhr($id = null) {
        if ($this->checkAuth()) {
            if (is_null($id)) {
                $allTemplates = CommTemplatesData::getCommunicationTemplates();

                foreach ($allTemplates as &$template) {
                    $templates = new CommTemplatesModel($template['templateName']);
                    $countMissingKeys = $templates->countMissing();
                    $template['keysFound'] = ($countMissingKeys == 0);
                    $template['missingKeyCount'] = $countMissingKeys;
                    $template['missingKeys'] = $templates->getMissing();
                }
                unset($template);

                echo json_encode(StatusReturn::S200($allTemplates), JSON_NUMERIC_CHECK);
            } else {
                echo json_encode(StatusReturn::S200(CommTemplatesData::getCommunicationTemplateByID($id)), JSON_NUMERIC_CHECK);
            }
        }
    }
    function post_xhr($id = null) {
        if ($this->checkAuth()) {

            $subject = (isset($_POST['subject']) ? trim($_POST['subject']) : '');
            $htmlTemplate = (isset($_POST['htmlTemplate']) ? trim($_POST['htmlTemplate']) : '');
            $textTemplate = (isset($_POST['textTemplate']) ? trim($_POST['textTemplate']) : '');
            $smsTemplate = (isset($_POST['smsTemplate']) ? trim($_POST['smsTemplate']) : '');

            if (is_null($id) && !empty($_POST['templateName'])) {
                echo json_encode(StatusReturn::S200(Array("id" => CommTemplatesData::insertCommunicationTemplate(trim($_POST['templateName']), $subject, $htmlTemplate, $textTemplate, $smsTemplate))), JSON_NUMERIC_CHECK);
            } else if (!is_null($id) && !empty($_POST['templateName']) && CommTemplatesData::updateCommunicationTemplate($id, trim($_POST['templateName']), $subject, $htmlTemplate, $textTemplate, $smsTemplate)) {
                echo json_encode(StatusReturn::S200(Array("id" => $id)));
            } else {
                echo json_encode(StatusReturn::E400("Missing Data!"));
            }
        }
    }
    function delete_xhr($id) {
        if ($this->checkAuth()) {
            if (!is_null($id) && CommTemplatesData::delCommunicationTemplate($id)) {
                echo json_encode(StatusReturn::S200(Array("id" => $id)), JSON_NUMERIC_CHECK);
            } else {
                echo json_encode(StatusReturn::E400('Missing ID!'));
            }
        }
    }
}