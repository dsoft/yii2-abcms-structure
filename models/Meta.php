<?php

namespace abcms\structure\models;

use Yii;

/**
 * This is the model class for table "structure_field_meta".
 *
 * @property integer $id
 * @property integer $fieldId
 * @property integer $modelId
 * @property integer $pk
 * @property string $value
 */
class Meta extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'structure_field_meta';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fieldId'], 'required'],
            [['fieldId', 'modelId', 'pk'], 'integer'],
            [['value'], 'string'],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fieldId' => 'Field ID',
            'modelId' => 'Model ID',
            'pk' => 'PK',
            'value' => 'Value',
        ];
    }
    
    /**
     * Get Field model that this field belongs to
     * @return mixed
     */
    public function getField()
    {
        return $this->hasOne(Field::className(), ['id' => 'fieldId']);
    }
}
