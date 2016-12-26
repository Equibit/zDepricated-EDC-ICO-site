<?php
namespace PHP_REST_API\Data;

use \PHP_REST_API\Helpers\MySQL;
use \PDO;

class TextVariablesData {

    public static function getAllTextVariables() {
        $query = MySQL::getInstance()->prepare("SELECT textKey, textLang, textText FROM TextVariables");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getTextVariable($textKey, $textlLang) {
        $query = MySQL::getInstance()->prepare("SELECT textText, COUNT(*) AS count FROM TextVariables WHERE textKey=:textKey AND textLang=:textLang");
        $query->bindValue(':textKey', $textKey);
        $query->bindValue(':textLang', $textlLang);
        $query->execute();
        $temp = $query->fetch(PDO::FETCH_ASSOC);
        return ($temp['count'] > 0 ? $temp['textText'] : null);
    }

    public static function updateTextVariable($textKey, $textLang, $textText) {
        $dataInstance = MySQL::getInstance();
        $query = $dataInstance->prepare("INSERT INTO TextVariables (textKey, textLang, textText) VALUES (:textKey, :textLang, :textText) ON DUPLICATE KEY UPDATE textText=:textText");
        $query->bindValue(':textKey', $textKey);
        $query->bindValue(':textLang', $textLang);
        $query->bindValue(':textText', $textText);
        return $query->execute();
    }

    public static function delTextVariable($id) {
        $query = MySQL::getInstance()->prepare("DELETE FROM TextVariables WHERE id=:id");
        $query->bindValue(':id', $id);
        return $query->execute();
    }

}