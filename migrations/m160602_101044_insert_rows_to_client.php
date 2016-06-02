<?php

use app\models\User;
use yii\db\Migration;

class m160602_101044_insert_rows_to_client extends Migration
{
    public function up()
    {
        $this->insert('oauth_clients', [
            'client_id' => 'old_version',
            'client_secret' => 'old_version_secret',
        ]);
        $this->insert('oauth_clients', [
            'client_id' => 'test_android_client',
            'client_secret' => $this->getRandString(12),
        ]);
        $this->insert('oauth_clients', [
            'client_id' => 'test_ios_client',
            'client_secret' => $this->getRandString(12),
        ]);

        $users = User::find()->where(
            ['!=','auth_key','']
        )->andWhere(['type'=>0])->all();
        foreach($users as $user) {
            $this->insert('oauth_access_tokens', [
                'access_token' => $user->auth_key,
                'user_id' => $user->id,
                'client_id' => 'old_version',
                'expires' => date('Y-m-d H:i:s', time() + 86400 * 365),
            ]);
            $this->insert('oauth_refresh_tokens', [
                'refresh_token' => Yii::$app->security->generateRandomString(),
                'user_id' => $user->id,
                'client_id' => 'old_version',
                'expires' => date('Y-m-d H:i:s', time() + 86400 * 365),
            ]);
        }
    }

    public function down()
    {
//        echo "m160602_101044_insert_rows_to_client cannot be reverted.\n";
        $this->delete('oauth_clients', [
            'client_id' => 'old_version',
        ]);
        $this->delete('oauth_clients', [
            'client_id' => 'test_android_client',
        ]);
        $this->delete('oauth_clients', [
            'client_id' => 'test_ios_client',
        ]);
//        return false;
    }

    /*
* 获取指定长度的随机字符串
*/
    public function getRandString($length){
        $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
        $result = '';
        $l = strlen($str);
        for($i = 0;$i < $length;$i ++){
            $num = rand(0, $l-1);
            $result .= $str[$num];
        }
        return $result;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
