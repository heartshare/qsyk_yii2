<?php

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
            'client_secret' => \app\components\QsImageHelper::getRandString(12),
        ]);
        $this->insert('oauth_clients', [
            'client_id' => 'test_ios_client',
            'client_secret' => \app\components\QsImageHelper::getRandString(12),
        ]);

        $users = \app\models\User::find()->where(
            ['!=','auth_key','']
        )->andWhere(['type'=>\app\models\User::DEVICE_TYPE])->all();
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
        echo "m160602_101044_insert_rows_to_client cannot be reverted.\n";

        return false;
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
