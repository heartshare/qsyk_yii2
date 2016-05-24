<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "double_numbers".
 *
 * @property integer $id
 * @property integer $red_1st
 * @property integer $red_2nd
 * @property integer $red_3rd
 * @property integer $red_4th
 * @property integer $red_5th
 * @property integer $red_6th
 * @property integer $blue_1st
 */
class DoubleNumbers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'double_numbers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['red_1st', 'red_2nd', 'red_3rd', 'red_4th', 'red_5th', 'red_6th', 'blue_1st'], 'required'],
            [['red_1st', 'red_2nd', 'red_3rd', 'red_4th', 'red_5th', 'red_6th', 'blue_1st'], 'integer'],
        ];
    }


    public function fields()
    {
        $fields = parent::fields();
        // remove fields that contain sensitive information
        unset($fields['id']);
        return $fields;
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'red_1st' => 'Red 1st',
            'red_2nd' => 'Red 2nd',
            'red_3rd' => 'Red 3rd',
            'red_4th' => 'Red 4th',
            'red_5th' => 'Red 5th',
            'red_6th' => 'Red 6th',
            'blue_1st' => 'Blue 1st',
        ];
    }
}
