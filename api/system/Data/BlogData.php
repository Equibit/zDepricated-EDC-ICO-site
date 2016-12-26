<?php
namespace PHP_REST_API\Data;

use \PHP_REST_API\Helpers\MySQL;
use \PDO;

class BlogData {

    public static function getAllBlogData() {
        $query = MySQL::getInstance()->prepare("SELECT id, img, title, link, short, post, lang, UNIX_TIMESTAMP(dateTime) AS dateTime FROM Blog ORDER BY dateTime DESC LIMIT 10");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllBlogByLang($lang) {
        $query = MySQL::getInstance()->prepare("SELECT id, img, title, link, short, post, lang, UNIX_TIMESTAMP(dateTime) AS dateTime FROM Blog WHERE lang=:lang ORDER BY dateTime DESC LIMIT 10");
        $query->bindValue(':lang', $lang);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function updateBlog($id, $img, $title, $link, $short, $post, $lang) {
        $query = MySQL::getInstance()->prepare("UPDATE Blog SET img=:img, title=:title, link=:link, short=:short, post=:post, lang=:lang WHERE id=:id");
        $query->bindValue(':id', $id);
        $query->bindValue(':img', $img);
        $query->bindValue(':title', $title);
        $query->bindValue(':link', $link);
        $query->bindValue(':short', $short);
        $query->bindValue(':post', $post);
        $query->bindValue(':lang', $lang);
        return $query->execute();
    }

    public static function addNewBlog($img, $title, $link, $short, $post, $lang) {
        $dataInstance = MySQL::getInstance();
        $query = $dataInstance->prepare("INSERT INTO Blog (img, title, link, short, post, lang, dateTime) VALUES (:img, :title, :link, :short, :post, :lang, FROM_UNIXTIME(:dateTime))");
        $query->bindValue(':img', $img);
        $query->bindValue(':title', $title);
        $query->bindValue(':link', $link);
        $query->bindValue(':short', $short);
        $query->bindValue(':post', $post);
        $query->bindValue(':lang', $lang);
        $query->bindValue(':dateTime', time());
        $query->execute();
        return $dataInstance->lastInsertId();
    }

    public static function delBlog($id) {
        $query = MySQL::getInstance()->prepare("DELETE FROM Blog WHERE id=:id");
        $query->bindValue(':id', $id);
        return $query->execute();
    }
}