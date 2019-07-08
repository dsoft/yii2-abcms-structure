<?php
namespace abcms\structure\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%structure_item}}`.
 */
class m190702_195841_create_structure_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%structure_item}}', [
            'id' => $this->primaryKey(),
            'structureId' => $this->integer()->notNull(),
            'active' => $this->boolean()->notNull()->defaultValue(1),
            'deleted' => $this->boolean()->notNull()->defaultValue(0),
            'ordering' => $this->integer()->notNull()->defaultValue(1),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%structure_item}}');
    }
}
