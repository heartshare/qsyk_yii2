<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/6/1
 * Time: 18:08
 */

namespace app\components;


use app\models\Img;
use Yii;
use yii\base\Component;

class QsImageHelper extends Component
{
    public static function copy($url, $imgPath)
    {
        if (!file_exists($imgPath)) {
            @mkdir(dirname($imgPath), 0777, true);
            return copy($url, $imgPath);
        } else {
            return true;
        }
    }
    public static function save($imgPath) {
        $imgContent = file_get_contents($imgPath);
        $imageInfo = getimagesize($imgPath);
        $dynamic = 0;
        if ('image/gif' == $imageInfo['mime']) {
            $dynamic   = strpos($imgContent, chr(0x21).chr(0xff).chr(0x0b).'NETSCAPE2.0') === FALSE ? 0 : 1;
        }
        $md5 = md5($imgContent);
        $img = new Img();
        $img->addtime=time();
        $img->modtime=time();
        $img->pathfile = substr($imgPath, strlen(yii::$app->params['imgDir']) - 1);
        $img->width = $imageInfo[0];
        $img->height = $imageInfo[1];
        $img->mime = $imageInfo['mime'];
        $img->md5 = $md5;
        $img->size = filesize($imgPath);
        $img->dynamic = $dynamic;
        if ($img->save()) {
            return $img->id;
        }
        return false;

    }

    public static function imgPath($ext) {
        $path = date('Ym', time()) . '/' . date('Ymd', time()) . '/';
        $filePath = yii::$app->params['imgDir'] . $path . self::getRandString(32) . '.' . $ext;
        if (!file_exists($filePath)) {
            @mkdir(dirname($filePath), 0777, true);
        }
        return $filePath;
    }

    /*
  * 获取指定长度的随机字符串
  */
    public static function getRandString($length){
        $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
        $result = '';
        $l = strlen($str);
        for($i = 0;$i < $length;$i ++){
            $num = rand(0, $l-1);
            $result .= $str[$num];
        }
        return $result;
    }

}