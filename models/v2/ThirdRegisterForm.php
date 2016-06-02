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
class ThirdRegisterForm extends Model
{
    public $nickname;
    public $avatar;
    public $user;

    public $client;
    public $client_secret;

    public $oid;
    public $from;

//    public $auth_key;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['nickname', 'client', 'client_secret', 'oid', 'from'], 'required'],
            [['nickname'], 'trim'],
            ['nickname', 'filter', 'filter' => function ($value) {
                // normalize phone input here
                return str_replace('/[^\w-]/', '', $value);
            }],
            [['nickname', 'avatar', 'client', 'client_secret', 'oid', 'from'],  'string'],
            ['client', 'validateClient'],
            ['from', function ($attribute, $params) {
                if (!in_array($this->$attribute, ['qq', 'weixin', 'weibo'])) {
                    $this->addError($attribute, 'Field \'from\' must be qq, weixin or weibo.');
                }
            }],
        ];
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

    private function validateNickname($nickname) {
        $exist = User::find()->where([
            'nick_name'=>$nickname,
        ])->exists();
        return $exist;
    }


    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function thirdRegister()
    {
        $oldUser = Yii::$app->user->identity;
        if ($this->validate()) {
            $newUser = User::find()
                ->where(['qq'=>$this->oid])
                ->orWhere(['weibo'=>$this->oid])
                ->orWhere(['weixin'=>$this->oid])
                ->one();
            if (empty($newUser)) {
                if($this->validateNickname($this->nickname)) {
                    $this->nickname .= rand(100,999);
                }

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
                    'user_name'=>$this->from  . '_' . $this->oid,
                    'nickname'=>$this->nickname,
                    'created_at'=>date('Y-m-d H:i:s'),
                    'updated_at'=>date('Y-m-d H:i:s', time() - 86400 * 90),
                    'type'=>User::THIRD_TYPE,
                ], false);

                if (!empty($avatarImgId)) {
                    $newUser->avatar_img = $avatarImgId;
                }
                switch($this->from) {
                    case "qq":
                        $newUser->qq = $this->oid;
                        break;
                    case "weibo":
                        $newUser->weibo = $this->oid;
                        break;
                    case "weixin":
                        $newUser->weixin = $this->oid;
                        break;
                }

                $newUser->status = User::STATUS_ACTIVE;
                $newUser->genRandomPassword();
                $newUser->generateAuthKey();
                $newUser->generatePasswordResetToken();
                if (!$newUser->save() || !$newUser->generateToken($this->client)) {
                    $this->addErrors($newUser->getErrors());
                    return false;
                }
                $newUser->client = $this->client;
                $this->user = $newUser;

                $this->migrate($oldUser, $newUser);
                return true;
            } else {
                $this->addError('', '用户已存在');
                return false;
            }
        }
        return false;
    }


    private function migrate($old, $new) {
        ResourceLike::updateAll(['user_id' => $new->id], ['user_id' => $old->id]);
        ResourceFavorite::updateAll(['user_id' => $new->id], ['user_id' => $old->id]);
    }


}
