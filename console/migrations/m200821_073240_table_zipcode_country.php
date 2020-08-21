<?php

use yii\db\Migration;

/**
 * Class m200821_073240_table_zipcode_country
 */
class m200821_073240_table_zipcode_country extends Migration
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

        $this->createTable('{{%zipcode_country}}', [
            'id' => $this->primaryKey(),
            'country_code' => $this->string(10)->unique(),
            'country_name' => $this->string(255)->notNull(),
            'zipcode' => $this->string(15)->notNull(),
            'city' => $this->string(255)->notNull(),
            'district' => $this->string(255)->null(),
            'address' => $this->string(255)->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200821_073240_table_zipcode_country cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200821_073240_table_zipcode_country cannot be reverted.\n";

        return false;
    }
    */
}
