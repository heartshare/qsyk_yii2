<?php

use yii\db\Migration;

class m160606_103949_insert_rows_to_random_cache extends Migration
{
    public function up()
    {
        $this->addColumn('random_cache', 'updated_at', $this->string());
    }

    public function down()
    {
        $this->dropColumn('random_cache', 'updated_at');
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
