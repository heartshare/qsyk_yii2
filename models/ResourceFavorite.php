<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "resource_favorite".
 *
 * @property integer $resource_id
 * @property integer $user_id
 * @property integer $time
 */
class ResourceFavorite extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'resource_favorite';
    }

    public function getResourceCount()
    {
        return $this->hasOne(ResourceCount::className(), ['resource_id' => 'resource_id']);
    }

    public function getResource()
    {
        return $this->hasOne(Resource::className(), ['resource_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['resource_id', 'user_id', 'time'], 'required'],
            [['resource_id', 'user_id', 'time'], 'integer'],
        ];
    }

    public function fields()
    {
        $fields = [
            'resource'
        ];
        return $fields; // TODO: Change the autogenerated stub
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'resource_id' => 'Resource ID',
            'user_id' => 'User ID',
            'time' => 'Time',
        ];
    }
}
