<?php
namespace PHP_REST_API\Data;

use \PHP_REST_API\Helpers\MySQL;
use \PDO;

class CommTemplatesData {

    public static function getCommunicationTemplates() {
        $query = MySQL::getInstance()->prepare("SELECT id, templateName, subject, htmlTemplate, textTemplate, smsTemplate FROM CommTemplates");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getCommunicationTemplatesEmails() {
        $query = MySQL::getInstance()->prepare("SELECT templateName FROM CommTemplates WHERE TRIM(htmlTemplate) != '' AND TRIM(textTemplate) != ''");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getCommunicationTemplatesSMS() {
        $query = MySQL::getInstance()->prepare("SELECT templateName FROM CommTemplates WHERE TRIM(smsTemplate) != ''");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getCommunicationTemplateByID($id) {
        $query = MySQL::getInstance()->prepare("SELECT id, templateName, subject, htmlTemplate, textTemplate, smsTemplate FROM CommTemplates WHERE id=:id");
        $query->bindValue(':id', $id);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public static function getCommunicationTemplateByName($templateName) {
        $query = MySQL::getInstance()->prepare("SELECT id, templateName, subject, htmlTemplate, textTemplate, smsTemplate FROM CommTemplates WHERE templateName=:templateName");
        $query->bindValue(':templateName', $templateName);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public static function updateCommunicationTemplate($id, $templateName, $subject, $htmlTemplate, $textTemplate, $smsTemplate) {
        $dataInstance = MySQL::getInstance();
        $query = $dataInstance->prepare("UPDATE CommTemplates SET templateName=:templateName, subject=:subject, htmlTemplate=:htmlTemplate, textTemplate=:textTemplate, smsTemplate=:smsTemplate WHERE id=:id");
        $query->bindValue(':id', $id);
        $query->bindValue(':templateName', $templateName);
        $query->bindValue(':subject', $subject);
        $query->bindValue(':htmlTemplate', $htmlTemplate);
        $query->bindValue(':textTemplate', $textTemplate);
        $query->bindValue(':smsTemplate', $smsTemplate);
        return $query->execute();
    }

    public static function insertCommunicationTemplate($templateName, $subject, $htmlTemplate, $textTemplate, $smsTemplate) {
        $dataInstance = MySQL::getInstance();
        $query = $dataInstance->prepare("INSERT INTO CommTemplates (templateName, subject, htmlTemplate, textTemplate, smsTemplate) VALUES (:templateName, :subject, :htmlTemplate, :textTemplate, :smsTemplate)");
        $query->bindValue(':templateName', $templateName);
        $query->bindValue(':subject', $subject);
        $query->bindValue(':htmlTemplate', $htmlTemplate);
        $query->bindValue(':textTemplate', $textTemplate);
        $query->bindValue(':smsTemplate', $smsTemplate);
        $query->execute();
        return $dataInstance->lastInsertId();
    }

    public static function delCommunicationTemplate($id) {
        $query = MySQL::getInstance()->prepare("DELETE FROM CommTemplates WHERE id=:id");
        $query->bindValue(':id', $id);
        return $query->execute();
    }
}