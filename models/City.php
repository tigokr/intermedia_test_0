<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 30.03.2015
 * Time: 12:57
 */

namespace app\models;


use yii\base\Model;

class City extends \yii\db\ActiveRecord {

    public static function tableName() {
        return 'cities';
    }

}