<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/4/25
 * Time: 16:33
 */

namespace app\commands;


use app\models\DoubleNumbers;
use app\models\Period;
use app\models\PrizeHistory;
use yii\console\Controller;
use linslin\yii2\curl;
use yii\helpers\Json;


class SpiderController extends Controller
{

    public function actionCurrent()
    {
        //http://www.lecai.com/lottery/ajax_current.php?lottery_type=50
        //http://www.lecai.com/lottery/draw/ajax_get_detail.php?lottery_type=50&phase=2016048
        $curl = new curl\Curl();
        $url = 'http://www.lecai.com/lottery/ajax_current.php?lottery_type=50';
        $response = $curl->get($url);
        $result = Json::decode($response, true);
        $period = Period::findOne($result['data']['phase']);
        if (empty($period)) {
            $period = new Period();
            $period->load([
                'period_id' => $result['data']['phase'],
                'sale_starttime' => $result['data']['time_startsale'],
                'sale_endtime' => $result['data']['time_endsale'],
                'sale_drawtime' => $result['data']['time_draw'],
                'status' => Period::STATUS_ACTIVE,
            ], '');
            if (!$period->save()) {
                var_dump($period->getErrors());
                exit;
            }
        }
    }

    /**
     * 采集 http://www.zhcw.com/ssq/kaijiangshuju/index.shtml?type=0
     */
    public function actionList($isInit = false)
    {
        $curl = new curl\Curl();
        for ($i = 1; $i <= 10; $i++) {

            $url = 'http://kaijiang.zhcw.com/zhcw/html/ssq/list_' . $i . '.html';
            echo $url . "\n";
            $response = $curl->get($url);

            $pattern = '/<tr>\s*<td[^>]*>(?<date>[^<]*)<\/td>\s*<td[^>]*>(?<period>[^<]*)<\/td>'
                . '\s*<td[^>]*>\s*<em[^>]*>(?<red_1st>[^<]*)<\/em>\s*<em[^>]*>(?<red_2nd>[^<]*)<\/em>\s*<em[^>]*>(?<red_3rd>[^<]*)<\/em>'
                . '\s*<em[^>]*>(?<red_4th>[^<]*)<\/em>\s*<em[^>]*>(?<red_5th>[^<]*)<\/em>\s*<em[^>]*>(?<red_6th>[^<]*)<\/em>'
                . '\s*<em[^>]*>(?<blue_1st>[^<]*)<\/em>\s*<\/td>\s*<td><strong>(?<sales>[^<]*)<\/strong><\/td>'
                . '\s*<td[^>]*><strong>(?<first_prize>[^<]*)<\/strong>[^<]*<\/td>'
                . '\s*<td[^>]*><strong class="rc">(?<sec_prize>[^<]*)<\/strong>[^<]*<\/td>/';
            if (preg_match_all($pattern, $response, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    //http://www.lecai.com/lottery/draw/ajax_get_detail.php?lottery_type=50&phase=2016048
                    $detailUrl = 'http://www.lecai.com/lottery/draw/ajax_get_detail.php?lottery_type=50&phase=' . $match['period'];

                    $detailResponse = $curl->get($detailUrl);
                    $detailJson = Json::decode($detailResponse, true);
                    if (empty($detailJson)) {
                        continue;
                    }


                    $period = Period::findOne($match['period']);
                    if (empty($period)) {
                        $period = new Period();
                    }
                    $period->load([
                        'period_id' => $detailJson['data']['phase'],
                        'sale_starttime' => $detailJson['data']['time_startsale'],
                        'sale_endtime' => $detailJson['data']['time_endsale'],
                        'sale_drawtime' => $detailJson['data']['time_draw'],
                        'draw_time' => $detailJson['data']['time_draw'],

                    ], '');


                    $period->status = Period::STATUS_FINISH;
                    if (!$period->save()) {
                        var_dump($period->getErrors());
                        exit;
                    }

                    $prizeHistory = PrizeHistory::find()->where(['period' => $period->period_id])->one();
                    if (empty($prizeHistory)) {
                        $drawResult = new DoubleNumbers();
                        $prizeHistory = new PrizeHistory();
                    } else {
                        $drawResult = DoubleNumbers::findOne($prizeHistory->winning_number_id);
                    }
                    $drawResult->load(array(
                        'red_1st' => $match['red_1st'],
                        'red_2nd' => $match['red_2nd'],
                        'red_3rd' => $match['red_3rd'],
                        'red_4th' => $match['red_4th'],
                        'red_5th' => $match['red_5th'],
                        'red_6th' => $match['red_6th'],
                        'blue_1st' => $match['blue_1st'],
                    ), '');

                    if (!$drawResult->save()) {
                        var_dump($drawResult->getErrors());
                        exit;
                    }
                    $prizeHistory->link('periodAr', $period);
                    $prizeHistory->link('drawResult', $drawResult);
                    if (!$prizeHistory->save()) {
                        var_dump($prizeHistory->getErrors());
                        exit;
                    }


                }

            }
            if (!$isInit) {
                break;
            }
        }

    }

}