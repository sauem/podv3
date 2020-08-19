<?php

use yii\db\Migration;

/**
 * Class m200819_033952_remove_unique_column
 */
class m200819_033952_remove_unique_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey("fk_form_cat","form_info");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200819_033952_remove_unique_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200819_033952_remove_unique_column cannot be reverted.\n";

        return false;
    }
    */
}
