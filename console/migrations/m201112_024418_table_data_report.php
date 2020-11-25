<?php

use yii\db\Migration;

/**
 * Class m201112_024418_table_data_report
 */
class m201112_024418_table_data_report extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('landing_pages', 'partner_id', $this->integer()->null());
    }

    /**
     *
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201112_024418_table_data_report cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201112_024418_table_data_report cannot be reverted.\n";

        return false;
    }
    */
}
