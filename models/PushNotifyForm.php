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
    public $resourceId = 0;
    public $deviceType;
    public $androidTitle;
    public $iosDesc;
    public $androidDesc;
    public $environment;
    public $apnsProduction;


    public function rules()
    {
        return [
            // username and password are both required
            [['deviceType', 'androidDesc', 'iosDesc'], 'required'],
            ['androidTitle', 'string'],
            [['resourceId', 'environment', 'apnsProduction'], 'integer'],
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
            'environment' => '推送环境',
            'apnsProduction' => 'ios环境设置',

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
            $pushNotify->environment = $this->environment;
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
            $extra = null;
            if ($this->resourceId > 0) {
                $extra = ['resourceSid'=>QsEncodeHelper::setSid($this->resourceId)];
            }

            if ($this->environment > 0) {
                $key = Yii::$app->params['jpushAppkeyOnline'];
                $secret = Yii::$app->params['jpushSecretOnline'];

            } else {
                $key = Yii::$app->params['jpushAppkeyOffline'];
                $secret = Yii::$app->params['jpushSecretOffline'];

            }
            if ($this->apnsProduction) {
                $apnsProduction = true;
            } else {
                $apnsProduction = false;
            }

            try {
                $client = new JPush($key, $secret, Yii::$app->params['jpushLog']);
                $result = $client->push()
                    ->setPlatform($deviceList)
                    ->addAllAudience()
                    ->addAndroidNotification($this->androidDesc, $this->androidTitle, 1, $extra)
                    ->addIosNotification($this->iosDesc, '', JPush::DISABLE_BADGE, true, 'iOS category', $extra)
                    ->setOptions(null, null, null, $apnsProduction)
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