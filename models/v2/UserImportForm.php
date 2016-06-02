<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/5/31
 * Time: 9:42
 */

namespace app\models\v2;


use app\components\QsEncodeHelper;
use app\models\ResourceFavoriteForm;
use app\models\ResourceLike;
use app\models\ResourceLikeForm;
use Yii;
use yii\base\Model;

/*
 * @property array $idsArr;
 */
class UserImportForm extends Model
{

    public $ids;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['ids'], 'required'],
            [['ids'],  'string'],
        ];
    }
    public function getIdsArr() {
        return explode(',', $this->ids);
    }

    public function importFav() {
        if ($this->validate()) {
            foreach($this->idsArr as $id) {
                $favForm = new ResourceFavoriteForm();
                if ($favForm->load([
                        'sid'=>QsEncodeHelper::setSid($id)
                    ], '') && $favForm->fav()) {

                }
            }
            return true;
        }
        return false;

    }

    public function importLike() {
        if ($this->validate()) {
            foreach($this->idsArr as $id) {
                $likeForm = new ResourceLikeForm();
                if ($likeForm->load([
                        'sid'=>QsEncodeHelper::setSid($id)
                    ], '') && $likeForm->like(ResourceLike::STATUS_HATE)) {

                }
            }
            return true;
        }
        return false;

    }

}