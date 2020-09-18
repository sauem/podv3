<?php

use yii\db\Migration;

/**
 * Class m200918_041153_alter_column_draf_import
 */
class m200918_041153_alter_column_draf_import extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn("contacts_log_import", "phone", $this->string(15));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200918_041153_alter_column_draf_import cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200918_041153_alter_column_draf_import cannot be reverted.\n";

        return false;
    }
    */
}
