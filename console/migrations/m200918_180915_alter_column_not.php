<?php

use yii\db\Migration;

/**
 * Class m200918_180915_alter_column_not
 */
class m200918_180915_alter_column_not extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn("contacts_log_import","note", $this->text()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200918_180915_alter_column_not cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200918_180915_alter_column_not cannot be reverted.\n";

        return false;
    }
    */
}
