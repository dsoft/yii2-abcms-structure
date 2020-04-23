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
 * 
 * @property Field $field
 */
class Meta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'structure_field_meta';
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => \abcms\multilanguage\behaviors\ModelBehavior::className(),
                'attributes' => [
                    'value',
                ],
            ],
        ]);
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fieldId' => Yii::t('abcms.structure', 'Field'),
            'modelId' => Yii::t('abcms.structure', 'Model'),
            'pk' => Yii::t('abcms.structure', 'PK'),
            'value' => Yii::t('abcms.structure', 'Value'),
        ];
    }
    
    /**
     * Field relation
     * @return \yii\db\ActiveQuery
     */
    public function getField()
    {
        return $this->hasOne(Field::className(), ['id' => 'fieldId']);
    }
    
    /**
     * Overwrite ModelBehavior::getTranslationInputName() to read attribute from field
     * @param string $attribute
     * @param string $language
     * @return string
     */
    public function getTranslationInputName($attribute, $language)
    {
        return $this->field->name.'_'.$language;
    }
}
