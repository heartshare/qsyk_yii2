<?php

namespace app\models\v2;

use app\models\OauthClients;
use app\models\ResourceFavorite;
use app\models\ResourceLike;
use app\models\User;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    public $mobile;
    public $password;
    public $user;

    public $client;
    public $client_secret;

    public $oid;
    public $from;

//    private $_user = false;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client', 'client_secret'], 'required'],
            ['mobile', 'match', 'pattern' => '/^[\d]{11}$/i'],
            ['password', 'string', 'min' => 6, 'max' => 20],
            [['client', 'client_secret', 'oid', 'from'],  'string'],
            ['client', 'validateClient'],
            [['oid', 'from'], 'required', 'when' => function ($model) {
                return empty($model->mobile);
            }],
            [['mobile', 'password'], 'required', 'when' => function ($model) {
                return empty($model->oid);
            }],

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


    public function login()
    {
        $oldUser = Yii::$app->user->identity;
        if ($this->validate()) {
            $newUser = User::findByMobile($this->mobile);
            if (!empty($newUser) && $newUser->validatePassword($this->password)) {
                if ($newUser->hasTokenExpired($this->client)) {
                    $newUser->generateToken($this->client, null, true);
                }
                $newUser->client = $this->client;
                $this->user = $newUser;
                $this->migrate($oldUser, $newUser);
                return true;
            } else {
                $this->addError('', '用户不存在或者密码不正确');
                return false;
            }
        }
        return false;
    }

    private function migrate($old, $new) {
        ResourceLike::updateAll(['user_id' => $new->id], ['user_id' => $old->id]);
        ResourceFavorite::updateAll(['user_id' => $new->id], ['user_id' => $old->id]);
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function thirdLogin()
    {
        $oldUser = Yii::$app->user->identity;
        if ($this->validate()) {
            $newUser = User::findByThirdAccount($this->oid, $this->from);
            if (!empty($newUser)) {
                if ($newUser->hasTokenExpired($this->client)) {
                    $newUser->generateToken($this->client, null, true);
                }
                $newUser->client = $this->client;
                $this->user = $newUser;
                $this->migrate($oldUser, $newUser);
                return true;
            } else {
                $this->addError('', '用户不存在或者第三方账户没有注册');
                return false;
            }
        }
        return false;
    }


    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }



}
