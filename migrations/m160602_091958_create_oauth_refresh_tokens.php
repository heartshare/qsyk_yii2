<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation for table `oauth_refresh_tokens`.
 */
class m160602_091958_create_oauth_refresh_tokens extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('oauth_refresh_tokens', [
            'id' => Schema::TYPE_PK,
            'refresh_token' => Schema::TYPE_STRING . ' NOT NULL',
            'client_id' => Schema::TYPE_STRING . ' NOT NULL',
            'user_id' => Schema::TYPE_STRING,
            'expires' => Schema::TYPE_TIMESTAMP . ' on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'scope' => Schema::TYPE_TEXT,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('oauth_refresh_tokens');
    }
}
