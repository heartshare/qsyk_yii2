<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/6/1
 * Time: 15:16
 */

namespace app\models\v2;


use app\models\User;
use yii\base\Model;

class BindForm extends Model
{

    public $mobile;
    public $password;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mobile', 'password'],'required'],
            ['mobile', 'match', 'pattern' => '/^[\d]{11}$/i'],
            ['password', 'string', 'min' => 6, 'max' => 20],
            ['mobile','validateDuplicate'],
        ];
    }

    public function validateDuplicate($attribute, $params)
    {
        $user = \Yii::$app->user->identity;
        $exist = User::find()->where([
            'mobile'=>$this->mobile,
        ])->andWhere(['!=', 'id', $user->id])->exists();
        if ($exist) {
            $this->addError($attribute, "手机号已绑定");
        }
    }

    public function bind() {
        if ($this->validate()) {
            $user = \Yii::$app->user->identity;
            $user->mobile = $this->mobile;
            $user->setPassword($this->password);
            if (!$user->save()) {
                $this->addError($user->getErrors());
                return false;
            }
            return true;
        }
        return false;
    }
}