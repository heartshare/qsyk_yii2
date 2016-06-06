<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/6/6
 * Time: 11:48
 */

namespace app\models\v2;


use yii\base\Model;

class NameValidForm extends Model
{
    public $nickname;
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['nickname'], 'required'],
            [['nickname'], 'trim'],
            ['nickname', 'match', 'pattern' => '/^[a-zA-Z0-9_\-\x{4e00}-\x{9fa5}]{2,12}$/u'],
        ];
    }
}