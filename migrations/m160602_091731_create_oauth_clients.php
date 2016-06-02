<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation for table `oauth_clients`.
 */
class m160602_091731_create_oauth_clients extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('oauth_clients', [
            'id' => Schema::TYPE_PK,
            'client_id' => Schema::TYPE_STRING . ' NOT NULL',
            'client_secret' => Schema::TYPE_STRING . ' NOT NULL',
            'redirect_uri' => Schema::TYPE_TEXT,
            'grant_types' => Schema::TYPE_STRING,
            'scope' => Schema::TYPE_TEXT,
            'user_id' => Schema::TYPE_STRING,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('oauth_clients');
    }
}
