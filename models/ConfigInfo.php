<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/4/15
 * Time: 11:45
 */

namespace app\models;


use yii\base\Model;


/**
 * This is the model class for table "image".
 *
 * @property integer $id
 * @property integer $rateEnable
 * @property integer $adEnable
 * @property string $rateTitle
 * @property string $rateConfirm
 * @property string $rateRefuse
 */
class ConfigInfo extends Model
{
    public $id;
    public $rateEnable;
    public $adEnable;
    public $rateTitle;
    public $rateConfirm;
    public $rateRefuse;

    private static $configInfo = [
        'id' => 100,
		'config'=>[
	        'lotteryEnable' => 0,
		],
		'rate'=>[
	        'rateEnable' => 0,
		    'rateTitle' => '给个好评吧！标题要长长长长长！还要长长长长长长长！',
			'rateConfirm' => '给个好评',
	        'rateRefuse' => '残忍拒绝',
		],
        'outOfService' => [
			'enalbe'=>1,
			'action'=>[
				'action'=>'update',
				'message'=>'本版本已经停止使用，请下载最新版本。',
				'link'=>'',
			],
		],
		'ad' => [
			'enable'=>1,
			'qqappid'=>'1105333924',
			'qqposid'=>'5030015066168155',
			'qqnum'=>5,
			'google'=>[
				'indexBannerEnable'=>1,
				'indexBannerId'=>'ca-app-pub-2992367458107598/7867984670',
			],
			'ads'=>[
				[
					'banner'=>'http://in.3b2o.com/img/show/sid/zc731CHo5Ov/w/1280/h/720/t/1/show.jpg',
					'logo'=>'http://in.3b2o.com/img/show/sid/zc731CHo5Ov/w/128/h/128/t/1/show.jpg',
					'title'=>'测试广告标题1',
					'description'=>'测试广告描述1',
					'link'=>'http://m.zhiboba.com/',
					'action'=>'行动语',
				],
				[
					'banner'=>'http://in.3b2o.com/img/show/sid/zc731CHo5Ov/w/1280/h/720/t/1/show.jpg',
					'logo'=>'http://in.3b2o.com/img/show/sid/zc731CHo5Ov/w/128/h/128/t/1/show.jpg',
					'title'=>'测试广告标题2',
					'description'=>'测试广告描述2',
				'link'=>'http://m.zhiboba.com/',
					'action'=>'去看看啊',
				],
			],
		],
    ];

    public static function getConfigInfo() {
        return self::$configInfo;
    }

    public static function getIsInreview() {
		$minfo = self::getMobileInfo();
		if ('qq' == $minfo['channel']) {
			return 0;
		}
		if ('meizu' == $minfo['channel']) {
			return 0;
		}
		if (!empty($_SERVER['HTTP_USER_AGENT']) && strstr($_SERVER['HTTP_USER_AGENT'], 'okhttp')) {
			return 0;
		}
		else {
			return 1;
		}
	}

    public static function getMobileInfo(){
        $ret = array();
        $ret['app'] = '';
        $ret['system'] = '';
        $ret['systemVersion'] = '';
        $ret['appversion'] = 0;
        $ret['browser'] = '';
        $ret['browserVersion'] = '';
        $ret['userid'] = '';
        $ret['channel'] = '';
		if (empty($_SERVER['HTTP_USER_AGENT'])) {
			return $ret;
		}
		$ua = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/([\w]+) ([\w]+) v([\d\.]+)/si', $ua, $m)) {
	        $ret['app'] 		= $m[1];
	        $ret['system'] 		= $m[2];
	    	$versions = explode('.', $m[3]);
	       	if (count($versions) == 3) {
	    		$ret['appversion'] = $versions[0]*10000+$versions[1]*100+$versions[2];
	      	}
        }
        if (preg_match('/mid:([\d\-a-f]+)/si', $ua, $userid)) {
        	$ret['userid'] 	= $userid[1];
        }
        if (preg_match('/channel:([\w]+)/si', $ua, $channel)) {
        	$ret['channel'] 	= $channel[1];
        }
        return $ret;
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rateEnable'], 'integer'],
            [['rateTitle'], 'string', 'max' => 255],
            [['rateConfirm', 'rateRefuse'], 'string', 'max' => 64],
        ];
    }
}
