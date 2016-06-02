<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/6/1
 * Time: 12:37
 */

namespace app\models\v2;


use app\components\QsImageHelper;
use app\models\User;
use yii\base\Model;

class UserInfoForm extends Model
{
    public $nick_name;
    public $password;
    public $sex;
    public $avatar_img;
    public $avatar;
    public $avatarFile;
//    public $updated_at;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'string', 'min' => 6, 'max' => 20],
            [['sex','avatar_img'], 'integer'],
            [['avatar'], 'string'],
            ['nick_name', 'trim'],
            ['nick_name', 'match', 'pattern' => '/^[A-Za-z0-9_\-\x80-\xff\s\']{2,12}$/i'],
            ['nick_name', 'validateDuplicate'],
            [['avatarFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, gif'],
            ['nick_name', 'validateFrequency'],

        ];
    }

    public function validateDuplicate($attribute, $params)
    {
        $user = \Yii::$app->user->identity;
        $exist = User::find()->where([
            'nick_name'=>$this->nick_name,
        ])->andWhere(['!=', 'id', $user->id])->exists();
        if ($exist) {
            $this->addError($attribute, "昵称已存在");
        }
    }

    public function validateFrequency($attribute, $params)
    {
        $user = \Yii::$app->user->identity;
        if (time() - strtotime($user->updated_at) < 86400 * 90) {
            $this->addError($attribute, "昵称修改过于频繁");
        }
    }

    public function edit() {
        $user = \Yii::$app->user->identity;
        if ($this->validate()) {

            if (!empty($this->avatarFile)) {
                $imgPath = QsImageHelper::imgPath($this->avatarFile->extension);
                if ($this->avatarFile->saveAs($imgPath)) {
                    $this->avatar_img = QsImageHelper::save($imgPath);
                }
            } else {
                $imgPath = QsImageHelper::imgPath('gif');
            }
            if (empty($this->avatar_img)) {
                if (QsImageHelper::copy($this->avatar, $imgPath)) {
                    $this->avatar_img = QsImageHelper::save($imgPath);
                }
            }
            $attributes = [];
            foreach($this->getAttributes() as $attr=>$val) {
                if ($this->$attr != null && $this->$attr != false) {
                    if (property_exists(new User, $attr)) {
                        if ($attr == 'password') {
                            $user->setPassword($val);
                        } else {
                            $attributes[$attr] = $val;
                        }
                    }
                }
            }
            $user->setAttributes($attributes);
            if (!empty($this->nick_name)) {
                $user->updated_at = date('Y-m-d H:i:s');
            }
            if (!$user->save()) {
                $this->addError($user->getErrors());
                return false;
            }
            return true;
        }
        return false;
    }
}