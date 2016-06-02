<?php

use yii\db\Migration;

/**
 * Handles the creation for table `oauth_access_tokens`.
 */
class m160602_091303_create_oauth_access_tokens extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('oauth_access_tokens', [
            'access_token' => $this->string(40)->primaryKey(),
            'client_id' => $this->string(80),
            'user_id' => $this->string(255),
            'expires' => $this->timestamp(),
            'scope' => $this->text(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('oauth_access_tokens');
    }
}
