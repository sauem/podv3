<?php

use yii\db\Migration;

/**
 * Class m200805_092322_revert_name_customer
 */
class m200805_092322_revert_name_customer extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->renameColumn("customers","customer_name","name");
        $this->renameColumn("customers","customer_phone","phone");
        $this->renameColumn("customers","customer_email","email");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200805_092322_revert_name_customer cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200805_092322_revert_name_customer cannot be reverted.\n";

        return false;
    }
    */
}
