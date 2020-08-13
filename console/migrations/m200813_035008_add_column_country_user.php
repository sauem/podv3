<?php

use yii\db\Migration;

/**
 * Class m200813_035008_add_column_country_user
 */
class m200813_035008_add_column_country_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("user","country",$this->string(50)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200813_035008_add_column_country_user cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200813_035008_add_column_country_user cannot be reverted.\n";

        return false;
    }
    */
}
