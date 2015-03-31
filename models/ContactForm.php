<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $recipient;

    public $name;
    public $phone;
    public $city;
    public $region;
    public $email;
    public $text;
    public $file;
    public $verifyCode;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['name', 'phone', 'text', 'email', 'city', 'verifyCode'], 'required'],
            [['email', 'recipient'], 'email'],
            ['phone', 'match', 'pattern' => '/^\([0-9]{3}\) [0-9]{3}-[0-9]{4}$/'],
            [['region', 'city'], 'integer'],
            ['text', 'safe'],
            ['file', 'file', 'maxSize' => 1024*1024],
            [['name', 'email',], 'trim'],
            [['recipient',], 'default', 'value'=>\Yii::$app->params['defaultEmail']],
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => \Yii::t('app', 'Verification Code'),
            'name' => \Yii::t('app', 'Name'),
            'phone' => \Yii::t('app', 'Phone'),
            'text' => \Yii::t('app', 'text'),
            'city' => \Yii::t('app', 'City'),
            'region' => \Yii::t('app', 'Region'),
            'file' => \Yii::t('app', 'Enclosure'),
            'email' => \Yii::t('app', 'Email'),
            'recipient' => \Yii::t('app', 'Recipient'),
            'text' => \Yii::t('app', 'Text'),

        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param  string  $email the target email address
     * @return boolean whether the model passes validation
     */
    public function contact($email)
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom([$this->email => $this->name])
                ->setSubject($this->subject)
                ->setTextBody($this->body)
                ->send();

            return true;
        } else {
            return false;
        }
    }
}
