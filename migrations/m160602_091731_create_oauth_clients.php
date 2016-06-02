<?php

use yii\db\Migration;

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
            'client_id' => $this->string(40)->primaryKey(),
            'client_secret' => $this->string(80),
            'redirect_uri' => $this->text(),
            'grant_types' => $this->string(80),
            'scope' => $this->text(),
            'user_id' => $this->string(80),
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
