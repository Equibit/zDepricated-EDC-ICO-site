<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Helpers\BaseAPIController;
use \PHP_REST_API\Data\FAQsData;

class AdminFAQs extends BaseAPIController {
    function get_xhr($lang = null) {
        if ($this->checkAuth()) {
            if (is_null($lang)) {
                echo json_encode(StatusReturn::S200(FAQsData::getAllFAQsData()), JSON_NUMERIC_CHECK);
            } else {
                echo json_encode(StatusReturn::S200(FAQsData::getAllFAQsByLang($lang)), JSON_NUMERIC_CHECK);
            }
        }
    }
    function post_xhr($faqID = null) {
        if ($this->checkAuth()) {
            if (is_null($faqID) && !empty($_POST["question"]) && !empty($_POST["answer"]) && !empty($_POST["lang"])) {
                echo json_encode(StatusReturn::S200(Array("id" => FAQsData::addNewFAQ($_POST["question"], $_POST["answer"], $_POST["lang"]))), JSON_NUMERIC_CHECK);
            } else if (!is_null($faqID) && !empty($_POST["question"]) && !empty($_POST["answer"]) && !empty($_POST["lang"]) && FAQsData::updateFAQ($faqID, $_POST["question"], $_POST["answer"], $_POST["lang"])) {
                echo json_encode(StatusReturn::S200(Array("id" => $faqID)));
            } else {
                echo json_encode(StatusReturn::E400("Missing Data!"));
            }
        }
    }
    function delete_xhr($faqID) {
        if ($this->checkAuth()) {
            if (!is_null($faqID) && FAQsData::delFAQ($faqID)) {
                echo json_encode(StatusReturn::S200(Array("id" => $faqID)), JSON_NUMERIC_CHECK);
            } else {
                echo json_encode(StatusReturn::E400('Missing FAQ ID!'));
            }
        }
    }
}