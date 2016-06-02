<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class RegisterForm extends Model
{
    public $uuid;
    public $user = null;
    public $client = 'old_version';

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['uuid'], 'required'],
//            ['uuid', 'validateUuid'],
        ];
    }

    public function validateUuid($attribute, $params)
    {
        if (User::find()->where(['user_name'=>$this->username])->exists()) {
            $this->addError($attribute, '设备已经注册');
        }
    }

//    /**
//     * Validates the password.
//     * This method serves as the inline validation for password.
//     *
//     * @param string $attribute the attribute currently being validated
//     * @param array $params the additional name-value pairs given in the rule
//     */
//    public function validatePassword($attribute, $params)
//    {
//        if (!$this->hasErrors()) {
//            $user = $this->getUser();
//
//            if (!$user || !$user->validatePassword($this->password)) {
//                $this->addError($attribute, 'Incorrect username or password.');
//            }
//        }
//    }
    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function register()
    {
        if ($this->validate()) {
            $newUser = User::find()->where(['user_name'=>$this->uuid])->one();
            if (empty($newUser)) {
                $newUser = new User();
                $newUser->setAttributes([
                    'user_name'=>$this->uuid,
                    'created_at'=>date('Y-m-d'),
                    'updated_at'=>date('Y-m-d H:i:s'),
                    'type'=>User::DEVICE_TYPE,
                ]);
                $newUser->status = User::STATUS_ACTIVE;
                $newUser->setPassword(\Yii::$app->params['password']);
                $newUser->generateAuthKey();
                $newUser->generatePasswordResetToken();
                if (!$newUser->save() || !$newUser->generateToken($this->client)) {
                    $this->addErrors($newUser->getErrors());
                    return false;
                }
                $this->user = $newUser;
                return true;
            } else {
                $this->addError('', '用户已存在');
                return false;
            }
        }
        return false;
    }

}
