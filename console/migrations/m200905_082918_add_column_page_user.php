<?php

use yii\db\Migration;

/**
 * Class m200905_082918_add_column_page_user
 */
class m200905_082918_add_column_page_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("user","page_id", $this->integer()->null());
        $this->addColumn("user","pic", $this->integer()->null());

        $this->addForeignKey(
            "user_fk_self",
            "user",
            "pic",
            "user",
            "id",
            "SET NULL"
        );
        $this->addForeignKey(
            "user_fk_landing",
            "user",
            "page_id",
            "landing_pages",
            "id",
            "SET NULL"
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200905_082918_add_column_page_user cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200905_082918_add_column_page_user cannot be reverted.\n";

        return false;
    }
    */
}
