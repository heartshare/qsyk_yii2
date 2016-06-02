<?php

use yii\db\Migration;

/**
 * Handles adding mobile_weixin to table `user`.
 */
class m160602_092247_add_mobile_weixin_to_user extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('user', 'mobile', $this->string(64));
        $this->addColumn('user', 'weixin', $this->string(255));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('user', 'mobile');
        $this->dropColumn('user', 'weixin');
    }
}
