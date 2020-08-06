<?php

use yii\db\Migration;

/**
 * Class m200806_072018_add_column_approved_user_id
 */
class m200806_072018_add_column_approved_user_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("orders", "approved_user_id", $this->integer()->null());

        $this->addForeignKey("approve_fk_user",
            "orders", "approved_user_id", "user", "id", "SET NULL"
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200806_072018_add_column_approved_user_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200806_072018_add_column_approved_user_id cannot be reverted.\n";

        return false;
    }
    */
}
