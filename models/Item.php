<?php

namespace abcms\structure\models;

use Yii;

/**
 * This is the model class for table "structure_item".
 *
 * @property integer $id
 * @property integer $structureId
 * @property integer $ordering
 * @property integer $active
 * @property integer $deleted
 */
class Item extends \abcms\library\base\BackendActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'structure_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ordering'], 'integer'],
            [['active'], 'string', 'max' => 1],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => \abcms\structure\behaviors\CustomFieldsBehavior::className(),
            ],
        ]);
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'structureId' => 'Structure',
            'ordering' => 'Ordering',
            'active' => 'Active',
            'deleted' => 'Deleted',
        ];
    }
    
    /**
     * Get Structure model
     * @return mixed
     */
    public function getStructure()
    {
        return $this->hasOne(Structure::className(), ['id' => 'structureId']);
    }
    
    /**
     * Return a specific custom field from the main structure
     * @param string $field
     * @return string|null
     */
    public function getField($field)
    {
        $structure = $this->structure;
        return $this->getCustomField($field, $structure->name);
    }
    
    public function getFieldDisplayInGridView()
    {
        
    }
}