<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/5/11
 * Time: 17:09
 */

namespace app\models;


use app\components\QsEncodeHelper;
use JPush;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\base\Model;

require_once __DIR__ . '/../components/JPush/JPush.php';
class PushNotifyForm extends Model
{
    public $resourceId;
    public $deviceType;
    public $androidTitle;
    public $iosDesc;
    public $androidDesc;


    public function rules()
    {
        return [
            // username and password are both required
            [['resourceId', 'deviceType', 'androidDesc', 'iosDesc'], 'required'],
            ['androidTitle', 'string'],
            ['resourceId', 'integer'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'deviceType' => '设备类型',
            'androidTitle' => '标题(android)',
            'iosDesc' => '内容(ios)',
            'androidDesc' => '内容(android)',
            'resourceId' => '资源id',

        ];
    }

    public function push()
    {
        // 初始化
        if ($this->validate()) {
            $pushNotify = new PushNotification();
            $pushNotify->device_type = $this->deviceType;
            $pushNotify->android_content= $this->androidDesc;
            $pushNotify->ios_content = $this->iosDesc;
            $pushNotify->android_title = $this->androidTitle;
            $pushNotify->resource_id = $this->resourceId;
            $pushNotify->status = PushNotification::STATUS_NO_SENT;
            $pushNotify->create_time = time();
            if (!$pushNotify->save()) {
                $this->addErrors($pushNotify->getErrors());
                return false;
            }

            $deviceList = [];
            switch($this->deviceType) {
                case 0:
                    $deviceList = ['ios', 'android'];
                    break;
                case 1:
                    $deviceList = ['android'];
                    break;
                case 2:
                    $deviceList = ['ios'];
                    break;
            }
            $extra = ['resourceSid'=>QsEncodeHelper::setSid($this->resourceId)];
            try {
                $client = new JPush(Yii::$app->params['jpushAppkey'], Yii::$app->params['jpushSecret'], Yii::$app->params['jpushLog']);
                $result = $client->push()
                    ->setPlatform($deviceList)
                    ->addAllAudience()
                    ->addAndroidNotification($this->androidDesc, $this->androidTitle, 1, $extra)
                    ->addIosNotification($this->iosDesc, '', JPush::DISABLE_BADGE, true, 'iOS category', $extra)
                    ->send();

                if (!empty($result) && !empty($result->data->msg_id)) {

                    $pushNotify->status = PushNotification::STATUS_SUCCESS;
                    $pushNotify->jpush_msg_id = $result->data->msg_id;
                    if (!$pushNotify->save()) {
                        $this->addErrors($pushNotify->getErrors());
                        return false;
                    }
                }
                return $result;
            } catch (Exception $e) {
                $this->addError('', $e->getMessage());
                 return false;
            }

        }
        return false;


    }
}