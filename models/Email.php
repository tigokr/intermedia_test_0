<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 30.03.2015
 * Time: 12:57
 */

namespace app\models;

use yii\helpers\Json;

class Email extends \yii\db\ActiveRecord {

    public static function tableName() {
        return 'emails';
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('app', 'ID'),
            'created' => \Yii::t('app', 'Created'),
            'received' => \Yii::t('app', 'Received'),
            'emailMessage' => \Yii::t('app', 'Email message'),
        ];
    }

    public function genId(){
        $this->id = uniqid();
    }

    public function getEmailMessage(){
        return Json::decode($this->data);
    }

    /*
     * \yii\swiftmailer\Message $email (by default)
     */
    public function setEmailMessage($email){
        $this->data = Json::encode($email);
    }

}