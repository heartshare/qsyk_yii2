<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "img".
 *
 * @property integer $id
 * @property integer $albumId
 * @property integer $articleId
 * @property integer $weiboContentId
 * @property string $name
 * @property integer $status
 * @property string $description
 * @property string $pathfile
 * @property integer $addtime
 * @property integer $modtime
 * @property integer $hits
 * @property integer $width
 * @property integer $height
 * @property string $mime
 * @property string $md5
 * @property integer $size
 * @property integer $dynamic
 * @property integer $iscover
 * @property integer $like_times
 * @property integer $favorite_times
 * @property integer $comment_times
 * @property integer $share_times
 * @property integer $rank
 * @property integer $recommendId
 */
class Img extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'img';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pathfile', 'addtime', 'modtime', 'size', 'dynamic'], 'required'],
            [['albumId', 'articleId', 'weiboContentId', 'status', 'addtime', 'modtime', 'hits', 'width', 'height', 'size', 'dynamic', 'iscover', 'like_times', 'favorite_times', 'comment_times', 'share_times', 'rank', 'recommendId'], 'integer'],
            [['name', 'description', 'pathfile'], 'string', 'max' => 2000],
            [['mime'], 'string', 'max' => 30],
            [['md5'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'albumId' => 'Album ID',
            'articleId' => 'article的主键，标识这幅图属于哪篇文章',
            'weiboContentId' => 'weibo_content的主键，标识这幅图属于哪条微博',
            'name' => 'Name',
            'status' => 'Status',
            'description' => '图片描述',
            'pathfile' => 'Pathfile',
            'addtime' => 'Addtime',
            'modtime' => 'Modtime',
            'hits' => 'Hits',
            'width' => 'Width',
            'height' => 'Height',
            'mime' => 'Mime',
            'md5' => 'Md5',
            'size' => '图片大小',
            'dynamic' => '是否为动图',
            'iscover' => '是否是相册的封面',
            'like_times' => 'Like Times',
            'favorite_times' => 'Favorite Times',
            'comment_times' => 'Comment Times',
            'share_times' => 'Share Times',
            'rank' => 'Rank',
            'recommendId' => 'recommend_resource的主键，标识这幅图属于哪条推荐',
        ];
    }
}
