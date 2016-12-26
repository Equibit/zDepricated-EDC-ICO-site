<?php
namespace PHP_REST_API\Data;

use \PHP_REST_API\Helpers\MySQL;
use \PDO;

class FAQsData {

    public static function getAllFAQsData() {
        $query = MySQL::getInstance()->prepare("SELECT * FROM FAQs ORDER BY id");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllFAQsByLang($lang) {
        $query = MySQL::getInstance()->prepare("SELECT * FROM FAQs WHERE lang=:lang ORDER BY id");
        $query->bindValue(':lang', $lang);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function updateFAQ($id, $question, $answer, $lang) {
        $query = MySQL::getInstance()->prepare("UPDATE FAQs SET question=:question, answer=:answer, lang=:lang WHERE id=:id");
        $query->bindValue(':id', $id);
        $query->bindValue(':question', $question);
        $query->bindValue(':answer', $answer);
        $query->bindValue(':lang', $lang);
        return $query->execute();
    }

    public static function addNewFAQ($question, $answer, $lang) {
        $dataInstance = MySQL::getInstance();
        $query = $dataInstance->prepare("INSERT INTO FAQs (question, answer, lang) VALUES (:question, :answer, :lang)");
        $query->bindValue(':question', $question);
        $query->bindValue(':answer', $answer);
        $query->bindValue(':lang', $lang);
        $query->execute();
        return $dataInstance->lastInsertId();
    }

    public static function delFAQ($id) {
        $query = MySQL::getInstance()->prepare("DELETE FROM FAQs WHERE id=:id");
        $query->bindValue(':id', $id);
        return $query->execute();
    }
}