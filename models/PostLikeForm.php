<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/5/25
 * Time: 9:52
 */

namespace app\models;


use app\components\QsEncodeHelper;
use yii\base\Model;

class PostLikeForm extends Model
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

    public function like()
    {
        $this->userId = \Yii::$app->user->identity->id;
        $post = ResourcePost::find()->where([
            'resourceid' => $this->getId(),
            'user' => $this->userId,
        ])->one();
        if ($post) {
            if (!$post->updateCounters(['dig' => 1])) {
                $this->addErrors($post->getErrors());
                return false;
            }
        }

        return true;
    }
}