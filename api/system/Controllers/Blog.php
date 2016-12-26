<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Helpers\BaseAPIController;
use \PHP_REST_API\Data\BlogData;

class Blog extends BaseAPIController {
    function get_xhr($lang) {
        if ($this->checkAuth()) {
            echo json_encode(StatusReturn::S200(BlogData::getAllBlogByLang($lang)), JSON_NUMERIC_CHECK);
        }
    }
}