<?php

use yii\db\Migration;

/**
 * Class m200916_094137_alter_column_log_import
 */
class m200916_094137_alter_column_log_import extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn("contacts_log_import","code", $this->string(255));
        $this->alterColumn("contacts_log_import","address", $this->string(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200916_094137_alter_column_log_import cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200916_094137_alter_column_log_import cannot be reverted.\n";

        return false;
    }
    */
}
