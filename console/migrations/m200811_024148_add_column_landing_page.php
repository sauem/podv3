<?php

use yii\db\Migration;

/**
 * Class m200811_024148_add_column_landing_page
 */
class m200811_024148_add_column_landing_page extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("landing_pages","country" , $this->string(50)->null());
        $this->addColumn("landing_pages","marketer" , $this->string(50)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200811_024148_add_column_landing_page cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200811_024148_add_column_landing_page cannot be reverted.\n";

        return false;
    }
    */
}
