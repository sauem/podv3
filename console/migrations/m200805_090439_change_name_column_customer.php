<?php

use yii\db\Migration;

/**
 * Class m200805_090439_change_name_column_customer
 */
class m200805_090439_change_name_column_customer extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn("customers","name","customer_name");
        $this->renameColumn("customers","phone","customer_phone");
        $this->renameColumn("customers","email","customer_email");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200805_090439_change_name_column_customer cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200805_090439_change_name_column_customer cannot be reverted.\n";

        return false;
    }
    */
}
