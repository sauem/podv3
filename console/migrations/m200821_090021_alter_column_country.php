<?php

use yii\db\Migration;

/**
 * Class m200821_090021_alter_column_country
 */
class m200821_090021_alter_column_country extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn("zipcode_country","country_code");
        $this->addColumn("zipcode_country","country_code",$this->string(10));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200821_090021_alter_column_country cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200821_090021_alter_column_country cannot be reverted.\n";

        return false;
    }
    */
}
