<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/6/6
 * Time: 11:48
 */

namespace app\models\v2;


use app\models\User;
use yii\base\Model;

class ThirdValidForm extends Model
{
    public $oid;
    public $from;
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['oid', 'from'], 'required'],
            ['oid', 'validateDuplicated'],
            ['from', function ($attribute, $params) {
                if (!in_array($this->$attribute, ['qq', 'weixin', 'weibo'])) {
                    $this->addError($attribute, 'Field \'from\' must be qq, weixin or weibo.');
                }
            }],
        ];
    }

    public function validateDuplicated($attribute, $params) {
        $exist = User::find()->where([
            $this->from=>$this->oid,
        ])->exists();
        if ($exist) {
            $this->addError($attribute, '该帐号已注册');
        }
    }
}