<?php
namespace abcms\structure\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `structure_field`.
 */
class m170301_171439_create_structure_field_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('structure_field', [
            'id' => $this->primaryKey(),
            'structureId' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'type' => $this->string()->notNull(),
            'ordering' => $this->integer()->notNull()->defaultValue(1),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('structure_field');
    }
}
