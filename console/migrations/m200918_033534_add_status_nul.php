<?php

use yii\db\Migration;

/**
 * Class m200918_033534_add_status_nul
 */
class m200918_033534_add_status_nul extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn("contacts_log_import", "status", $this->string(50)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200918_033534_add_status_nul cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200918_033534_add_status_nul cannot be reverted.\n";

        return false;
    }
    */
}
