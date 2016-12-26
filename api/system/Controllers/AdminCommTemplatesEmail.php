<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Helpers\BaseAPIController;
use \PHP_REST_API\Data\CommTemplatesData;

class AdminCommTemplatesEmail extends BaseAPIController {
    function get_xhr() {
        if ($this->checkAuth()) {
            echo json_encode(StatusReturn::S200(CommTemplatesData::getCommunicationTemplatesEmails()), JSON_NUMERIC_CHECK);
        }
    }
}