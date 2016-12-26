<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Helpers\BaseAPIController;
use \PHP_REST_API\Data\FAQsData;

class FAQs extends BaseAPIController {
    function get_xhr($lang) {
        if ($this->checkAuth()) {
            echo json_encode(StatusReturn::S200(FAQsData::getAllFAQsByLang($lang)), JSON_NUMERIC_CHECK);
        }
    }
}