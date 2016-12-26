<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Helpers\BaseAPIController;
use \PHP_REST_API\Data\TextVariablesData;

class AdminTextVariables extends BaseAPIController {
    function get_xhr() {
        if ($this->checkAuth()) {
            $textVariables = TextVariablesData::getAllTextVariables();

            foreach ($textVariables as &$variable) {

            }
            unset($variable);

            echo json_encode(StatusReturn::S200($textVariables), JSON_NUMERIC_CHECK);
        }
    }
    function post_xhr() {
        if ($this->checkAuth()) {
            if (!empty($_POST['textText'])) {
                echo json_encode(StatusReturn::S200(TextVariablesData::updateTextVariable($_POST['textKey'], $_POST['textLang'], $_POST['textText'])), JSON_NUMERIC_CHECK);
            } else {
                echo json_encode(StatusReturn::E400('Missing Email Variable Text!'));
            }
        }
    }
    function delete_xhr($id) {
        if ($this->checkAuth()) {
            if (!is_null($id) && TextVariablesData::delTextVariable($id)) {
                echo json_encode(StatusReturn::S200(Array("id" => $id)), JSON_NUMERIC_CHECK);
            } else {
                echo json_encode(StatusReturn::E400('Missing ID!'));
            }
        }
    }
}