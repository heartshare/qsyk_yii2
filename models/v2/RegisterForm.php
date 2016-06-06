<?php

namespace app\models\v2;

use app\components\QsImageHelper;
use app\models\OauthClients;
use app\models\ResourceFavorite;
use app\models\ResourceLike;
use app\models\User;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class RegisterForm extends Model
{
    public $mobile;
    public $password;
    public $nickname;
    public $avatar;
    public $avatarFile;
    public $user;

    public $client;
    public $client_secret;



    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['nickname', 'client', 'client_secret', 'mobile', 'password'], 'required'],
            ['mobile', 'match', 'pattern' => '/^[\d]{11}$/i'],
            [['nickname'], 'trim'],
            ['nickname', 'match', 'pattern' => '/^[a-zA-Z0-9_\-\x{4e00}-\x{9fa5}]{2,12}$/i'],
            ['password', 'string', 'min' => 6, 'max' => 20],
            [['nickname', 'avatar', 'client', 'client_secret'],  'string'],
            ['client', 'validateClient'],
            [['avatarFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, gif'],
            ['nickname', 'validateNickname'],
            ['mobile', 'validateIsVerified'],
        ];
    }


    public function validateIsVerified($attribute, $params)
    {
        $cache = yii::$app->cache;
        $verifyKey = "verify_" . $this->mobile;
        if (!$cache->get($verifyKey)) {
            $this->addError($attribute, "该手机号注册超时，请重新请求验证码");
        }
    }
    public function validateClient($attribute, $params)
    {
        $oClient = OauthClients::findOne([
            'client_id'=>$this->client,
            'client_secret'=>$this->client_secret,
        ]);
        if (empty($oClient)) {
            $this->addError($attribute, "Cannot find the spec client.");
        }
    }

    public function validateNickname($attribute, $params) {
        $exist = User::find()->where([
            'nick_name'=>$this->$attribute,
        ])->exists();
        if ($exist) {
            $this->addError($attribute, '昵称已存在');
        }
    }

    public function register()
    {
        $oldUser = Yii::$app->user->identity;
        if ($this->validate()) {
            $newUser = User::find()->where(['mobile'=>$this->mobile])->one();
            if (empty($newUser)) {
                $imgPath = '';
                if (!empty($this->avatarFile)) {
                    $imgPath = QsImageHelper::imgPath($this->avatarFile->extension);
                    if ($this->avatarFile->saveAs($imgPath)) {
                        $avatarImgId = QsImageHelper::save($imgPath);
                    }
                } else {
                    if (!empty($this->avatar)) {
                        $pathParts = pathinfo($this->avatar);
                        $imgPath = QsImageHelper::imgPath($pathParts['extension']);
                    }

                }
                if (empty($avatarImgId)) {
                    if (!empty($imgPath) && QsImageHelper::copy($this->avatar, $imgPath)) {
                        $avatarImgId = QsImageHelper::save($imgPath);
                    }
                }

                $newUser = new User();
                $newUser->setAttributes([
                    'user_name'=>'mobile_' . $this->mobile,
                    'mobile'=>$this->mobile,
                    'nick_name'=>$this->nickname,
                    'created_at'=>date('Y-m-d H:i:s'),
                    'updated_at'=>date('Y-m-d H:i:s', time() - 86400 * 90),
                    'type'=>User::MOBILE_TYPE,
                ], false);
                if (!empty($avatarImgId)) {
                    $newUser->avatar_img = $avatarImgId;
                }


                $newUser->status = User::STATUS_ACTIVE;
                $newUser->setPassword($this->password);
                $newUser->generateAuthKey();
                $newUser->generatePasswordResetToken();
                if (!$newUser->save()) {
                    $this->addErrors($newUser->getErrors());
                    return false;
                }
                if (!$newUser->generateToken($this->client)) {
                    $this->addErrors($newUser->getErrors());
                    return false;
                }
                $newUser->client = $this->client;
                $this->user = $newUser;
                $this->migrate($oldUser, $newUser);
                return true;
            }
        }
        return false;
    }

    private function migrate($old, $new) {
        ResourceLike::updateAll(['user_id' => $new->id], ['user_id' => $old->id]);
        ResourceFavorite::updateAll(['user_id' => $new->id], ['user_id' => $old->id]);
    }





}
