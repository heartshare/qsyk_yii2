<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/5/18
 * Time: 10:39
 */

namespace app\components;


use yii\base\Security;
use yii\web\ResponseFormatterInterface;

class EncryptFormatter implements ResponseFormatterInterface
{
    public function format($response)
    {
        $response->getHeaders()->set('Content-Type', 'text/raw; charset=UTF-8');
        if ($response->data !== null) {
//            $security = new Security();
            $response->content = base64_encode(json_encode($response->data));
        }
    }
}