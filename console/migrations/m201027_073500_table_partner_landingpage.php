<?php

use yii\db\Migration;

/**
 * Class m201027_073500_table_partner_landingpage
 */
class m201027_073500_table_partner_landingpage extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_partner_page}}', [
            'id' => $this->primaryKey(),
            'partner_id' => $this->integer(),
            'page_id' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201027_073500_table_partner_landingpage cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201027_073500_table_partner_landingpage cannot be reverted.\n";

        return false;
    }
    */
}
