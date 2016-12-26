<?php
namespace PHP_REST_API\Controllers;

use \PHP_REST_API\Helpers\StatusReturn;
use \PHP_REST_API\Helpers\BaseAPIController;
use \PHP_REST_API\Data\BlogData;

class AdminBlog extends BaseAPIController {
    function get_xhr($lang = null) {
        if ($this->checkAuth()) {
            if (is_null($lang)) {
                echo json_encode(StatusReturn::S200(BlogData::getAllBlogData()), JSON_NUMERIC_CHECK);
            } else {
                echo json_encode(StatusReturn::S200(BlogData::getAllBlogByLang($lang)), JSON_NUMERIC_CHECK);
            }
        }
    }
    function post_xhr($blogID = null) {
        if ($this->checkAuth()) {
            if (is_null($blogID)
                && !empty($_POST["img"])
                && !empty($_POST["title"])
                && isset($_POST["link"])
                && !empty($_POST["short"])
                && isset($_POST["post"])
                && !empty($_POST["lang"])) {
                echo json_encode(StatusReturn::S200(Array("id" => BlogData::addNewBlog($_POST["img"], $_POST["title"], $_POST["link"], $_POST["short"], $_POST["post"], $_POST["lang"]))), JSON_NUMERIC_CHECK);
            } else if (!is_null($blogID)
                && !empty($_POST["img"])
                && !empty($_POST["title"])
                && isset($_POST["link"])
                && !empty($_POST["short"])
                && isset($_POST["post"])
                && !empty($_POST["lang"])
                && BlogData::updateBlog($blogID, $_POST["img"], $_POST["title"], $_POST["link"], $_POST["short"], $_POST["post"], $_POST["lang"])) {
                echo json_encode(StatusReturn::S200(Array("id" => $blogID)));
            } else {
                echo json_encode(StatusReturn::E400("Missing Data!"));
            }
        }
    }
    function delete_xhr($blogID) {
        if ($this->checkAuth()) {
            if (!is_null($blogID) && BlogData::delBlog($blogID)) {
                echo json_encode(StatusReturn::S200(Array("id" => $blogID)), JSON_NUMERIC_CHECK);
            } else {
                echo json_encode(StatusReturn::E400('Missing Blog ID!'));
            }
        }
    }
}