<?php

use yii\db\Migration;

/**
 * Class m200905_081922_add_column_user_client
 */
class m200905_081922_add_column_user_client extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("user","is_partner", $this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200905_081922_add_column_user_client cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200905_081922_add_column_user_client cannot be reverted.\n";

        return false;
    }
    */
}
