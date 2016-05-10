<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/5/6
 * Time: 12:29
 */

namespace app\models;


use app\components\QsEncodeHelper;
use yii\base\Model;

class ResourceLikeForm extends Model
{

    public $sid;
    private $userId;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['sid'], 'required'],
        ];
    }

    public function getId() {
        return QsEncodeHelper::getSid($this->sid);
    }

    public function like($status) {
        $this->userId = \Yii::$app->user->identity->id;
        $resourceLike = ResourceLike::find()->where([
            'resource_id'=>$this->getId(),
            'user_id'=>$this->userId,
        ])->one();
        if (empty($resourceLike)) {
            $resourceLike = new ResourceLike();
            $resourceLike->setAttributes([
                'status'=>$status,
                'resource_id'=>$this->getId(),
                'user_id'=>$this->userId,
                'time'=>time(),
            ]);
            $resourceCount = $resourceLike->resourceCount;
            if ($resourceCount) {
                if ($status == ResourceLike::STATUS_LIKE) {
                    $resourceCount->updateCounters(['resource_dig' => 1]);
                } else if ($status == ResourceLike::STATUS_LIKE) {
                    $resourceCount->updateCounters(['resource_bury' => 1]);
                }

            }
            if (!$resourceLike->save()) {
                $this->addErrors($resourceLike->getErrors());
                return false;
            }
        } else {
            $resourceLike->status = $status;
            if (!$resourceLike->save()) {
                $this->addErrors($resourceLike->getErrors());
                return false;
            }
        }

        return true;
    }

}