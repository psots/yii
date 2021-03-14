<?php
namespace app\models;

use yii\db\ActiveRecord;

/**
 * 
 */
class CurrencyList extends ActiveRecord
{
    public function attributeLabels() {
        return [
            'short_name' => '',
            'name' => '',
            'is_using' => ''
        ];
    }

    public function rules() {
        return [
            [['short_name', 'name'], 'trim'],
            ['short_name', 'required', 'message' => 'обязательно для заполнения'],
            ['name', 'required', 'message' => 'обязательно для заполнения'],
        ];
    }
}