<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 30.03.2015
 * Time: 12:57
 */

namespace app\models;


use yii\base\Model;

class Region extends \yii\db\ActiveRecord {

    public static function tableName() {
        return 'regions';
    }

    public function getCities(){
        return $this->hasMany(City::className(), ['region_id'=>'id']);
    }

}