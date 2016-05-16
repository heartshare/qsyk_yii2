<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;


/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $user_name
 * @property integer $type
 * @property string $auth_key
 * @property string $nick_name
 * @property string $password
 * @property string $password_hash
 * @property string $password_reset_token
 * @property integer $status
 * @property string $email
 * @property string $salt
 * @property integer $sex
 * @property integer $avatar_img
 * @property string $qq
 * @property string $weibo
 * @property integer $join_time
 * @property string $created_at
 * @property string $updated_at
 * @property integer $last_visit_time
 * @property integer $resource_num
 * @property integer $post_num
 * @property integer $dig_num
 * @property integer $points
 */
class User extends ActiveRecord implements IdentityInterface
{

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    const DEVICE_TYPE = 1;



//    const SCENARIO_REGISTER = 'register';



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_name', 'auth_key'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['user_name', 'email', 'password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
        ];
    }



    public function fields()
    {
        $fields = ['auth_key', 'points', 'nick_name'];
        // remove fields that contain sensitive information
//        $fields[] = 'drawResult';
//        $fields[] = 'periodDesc';
//        $fields[] = 'periodAr';
        return $fields;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_name' => 'User Name',
            'type' => 'Type',
            'auth_key' => 'Auth Key',
            'nick_name' => 'Nick Name',
            'password' => 'Password',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'status' => 'Status',
            'email' => 'Email',
            'salt' => 'Salt',
            'sex' => 'Sex',
            'avatar_img' => 'Avatar Img',
            'qq' => 'Qq',
            'weibo' => 'Weibo',
            'join_time' => 'Join Time',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'last_visit_time' => 'Last Visit Time',
            'resource_num' => 'Resource Num',
            'post_num' => 'Post Num',
            'dig_num' => 'Dig Num',
            'points' => 'Points',
        ];
    }


    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }
    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token]);
    }
    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['user_name' => $username, 'status' => self::STATUS_ACTIVE]);
    }
    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }
    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }
    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }
    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }
    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
