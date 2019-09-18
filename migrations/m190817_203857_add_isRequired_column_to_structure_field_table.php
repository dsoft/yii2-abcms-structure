<?php
namespace abcms\structure\migrations;

use yii\db\Migration;

/**
 * Handles adding isRequired to table `{{%structure_field}}`.
 */
class m190817_203857_add_isRequired_column_to_structure_field_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('structure_field', 'isRequired', $this->boolean()->notNull()->defaultValue(0)->after('type'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('structure_field', 'isRequired');
    }
}
