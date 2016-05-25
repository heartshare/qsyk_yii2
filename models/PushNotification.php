<?php

namespace app\models;

use app\components\QsEncodeHelper;
use Yii;

/**
 * This is the model class for table "push_notification".
 *
 * @property integer $id
 * @property string $device_type
 * @property integer $environment
 * @property string $android_content
 * @property string $ios_content
 * @property string $android_title
 * @property integer $resource_id
 * @property integer $status
 * @property string $create_time
 * @property integer $creator
 * @property integer $jpush_msg_id
 */
class PushNotification extends \yii\db\ActiveRecord
{

    const STATUS_NO_SENT = 0;
    const STATUS_SUCCESS = 1;
    const STATUS_FAILED = -1;
    const DEVICE_DICT = [
        '全部',
        'Android',
        'IOS',
    ];
    const STATUS_DICT = [
        self::STATUS_NO_SENT=>'未发送',
        self::STATUS_SUCCESS=>'成功',
        self::STATUS_FAILED=>'失败',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'push_notification';
    }
    public function getDeviceDesc() {
        return self::DEVICE_DICT[$this->device_type];
    }

    public function getStatusDesc() {
        return self::STATUS_DICT[$this->status];
    }

    public function getResourceSid() {
        return QsEncodeHelper::setSid($this->resource_id);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['device_type', 'android_content', 'ios_content', 'android_title', 'resource_id', 'status'], 'required'],
            [['device_type'], 'string'],
            [['environment','resource_id', 'status', 'creator', 'jpush_msg_id'], 'integer'],
            [['create_time'], 'safe'],
            [['android_content', 'ios_content'], 'string', 'max' => 1024],
            [['android_title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'device_type' => '设备类型',
            'environment' => '推送环境',
            'deviceDesc' => '设备类型',
            'android_content' => '内容(android)',
            'ios_content' => '内容(ios)',
            'android_title' => '标题(android)',
            'resource_id' => '资源id',
            'status' => '状态',
            'statusDesc' => '状态',
            'create_time' => '推送时间',
            'creator' => 'Creator',
            'jpush_msg_id' => '极光ID',
        ];
    }
}
