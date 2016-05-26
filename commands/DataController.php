<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/5/26
 * Time: 10:47
 */

namespace app\commands;


use app\models\RandomQueue;
use app\models\Resource;
use yii\console\Controller;
use yii\db\Query;

class DataController extends Controller
{

    public function actionPrepare() {
        $allRows = [];
        $startTime = mktime(date('H') - 24, date('i'), date('s'), date('m'), date('d'), date('y'));
        $endTime = mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('y'));

        $time_start = microtime(true);

        $rows = (new Query())
            ->select(['id'])
            ->from('resource')
            ->where(['status' => Resource::STATUS_ACTIVE])
            ->andWhere(['between', 'pub_time', $startTime, $endTime])
            ->orderBy(['pub_time' => SORT_DESC])
            ->limit(300)
            ->column();
        $allRows = array_merge($allRows, $rows);

        $time_end = microtime(true);
        $time = $time_end - $time_start;
        echo "300 Rows Query in $time seconds\n";

        $startTime = mktime(date('H'), date('i'), date('s'), date('m'), date('d') - 3, date('y'));
        $endTime = mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('y'));
        echo date("Ymd H:i:s",$startTime) ."\n";
        echo date("Ymd H:i:s",$endTime) ."\n";


        $time_start = microtime(true);


        $rows = (new Query())
            ->select(['id'])
            ->from('resource')
            ->where(['status' => Resource::STATUS_ACTIVE])
            ->andWhere(['between', 'pub_time', $startTime, $endTime])
            ->orderBy('RAND()')
            ->limit(450)
            ->column();


        $time_end = microtime(true);
        $time = $time_end - $time_start;
        echo "450 Rows Query in $time seconds\n";

        $allRows = array_merge($allRows, $rows);

        echo count($allRows) . " Rows selected\n";
//        shuffle($rows);
//        foreach ($rows as $idx => $row) {
//            $random = RandomQueue::findOne($idx + 1);
//            if (empty($random)) {
//                $random = new RandomQueue();
//            }
//            $random->id = $idx + 1;
//            $random->album_id = $row;
//            $random->save();
//        }
    }

}