<?php

namespace abcms\structure\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use abcms\library\models\Model;
use abcms\structure\classes\StructureTranslation;

/**
 * This is the model class for table "structure".
 *
 * @property integer $id
 * @property string $name
 * @property integer $modelId
 * @property integer $pk
 * 
 * @property Field[] $fields
 * @property Field[] $translatableFields
 */
class Structure extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'structure';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['modelId', 'pk'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
            ['name', 'match', 'pattern' => '/^[a-zA-Z][a-zA-Z0-9]*$/', 'message' => Yii::t('abcms.structure', '{attribute} should only contain alphanumeric characters and start with an alphabetic character.')]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('abcms.structure', 'Name'),
            'modelId' => Yii::t('abcms.structure', 'Model'),
            'pk' => Yii::t('abcms.structure', 'PK'),
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
     * Get Fields models that belongs to this model
     * @return \yii\db\ActiveQuery
     */
    public function getFields()
    {
        return $this->hasMany(Field::className(), ['structureId' => 'id'])->orderBy(['ordering' => SORT_ASC]);
    }
    
    /**
     * Get translatable Fields models that belongs to this model
     * @return \yii\db\ActiveQuery
     */
    public function getTranslatableFields()
    {
        return $this->hasMany(Field::className(), ['structureId' => 'id'])->andWhere(['isTranslatable' => 1])->orderBy(['ordering' => SORT_ASC]);
    }
    
    /**
     * Model relation.
     * @return mixed
     */
    public function getModel()
    {
        return $this->hasOne(Model::className(), ['id' => 'modelId']);
    }
    
    /**
     * Return the class name of the model
     * @return string|null
     */
    public function getModelName()
    {
        return $this->model ? $this->model->className : null;
    }
    
    /**
     * Read custom fields values and save them for a certain model.
     * @param int $structureId
     * @param int $modelId Model class identifier
     * @param int $pk Primary key of the model
     * @param array $data Array where key is the field name and value is the field value.
     */
    public static function saveValuesByName($structureId, $modelId, $pk, $data)
    {
        if(!is_array($data) || !$data) {
            return;
        }
        $structure = Structure::findOne(['id' => $structureId]);
        if(!$structure){
            return false;
        }
        foreach($data as $name => $value) {
            $field = Field::find()->with('structure')->andWhere(['structureId' => $structure->id, 'name' => $name])->one();
            if($field) {
                $field->commitValue($value, $modelId, $pk);
            }
        }
        return true;
    }

    /**
     * Fill value for each field.
     * @param int $modelId
     * @param int $pk
     */
    public function fillFieldsValues($modelId, $pk)
    {
        $fields = $this->fields;
        foreach($fields as $field) {
            $field->fillValue($modelId, $pk);
        }
    }

    /**
     * Returns custom fields for a certain model
     * @param int $modelId Model class identifier
     * @param int $pk Primary key of the model
     * @param string|null $language Language
     * @param string $multilanguage Multi-language component ID
     * @return array
     */
    public static function getCustomFields($modelId, $pk, $language = null, $multilanguage = 'multilanguage')
    {
        $metas = Meta::find()->joinWith(['field', 'field.structure'], true, 'INNER JOIN')->andWhere(['structure_field_meta.modelId' => $modelId, 'structure_field_meta.pk' => $pk])->all();
        if($language)
        {
            $multilanguage = Yii::$app->get($multilanguage);
            $metas = $multilanguage->translateMultiple($metas, $language, FALSE);
        }
        $array = [];
        foreach($metas as $meta){
            if($meta->value){
                if($meta->field->structure->name){
                    $array[$meta->field->structure->name][$meta->field->name] = $meta->value; 
                }
                else{
                    $array[$meta->field->name] = $meta->value; 
                }
                
            }
        }
        return $array;
    }
    
    protected $_dynamicModel;
    
    /**
     * Creates a dynamic model for this structure fields.
     * @return \yii\base\DynamicModel
     */
    public function getDynamicModel()
    {
        if(!$this->_dynamicModel){
            $fields = $this->fields;
            $this->_dynamicModel = Field::getDynamicModel($fields);
            $this->_dynamicModel->defineAttribute('isLoaded', false);
        }
        return $this->_dynamicModel;
    }
    
    /**
     * Return plural name from name attribute
     * @return string
     */
    public function getPluralName()
    {
        $name = Inflector::pluralize(Inflector::camel2words($this->name));
        return $name;
    }
    
    /**
     * Return singular name from name attribute
     * @return string
     */
    public function getSingularName()
    {
        return Inflector::camel2words($this->name);
    }
    
    /**
     * Return all models as array where id is the key, and name or id as value.
     * @return array
     */
    public static function getList()
    {
        $models = self::find()->orderBy(['name' => SORT_ASC, 'id' => SORT_ASC])->all();
        $array = [];
        foreach($models as $model){
            $array[$model->id] = $model->name ? $model->name : 'Structure #' . $model->id;
        }
        return $array;
    }
    
    /**
     * Return the StructureTranslation class of this Structure for a certain model
     * @param ActiveRecord $model
     * @return StructureTranslation
     */
    public function getStructureTranslation($model)
    {
        $structureTranslation = new StructureTranslation();
        $structureTranslation->structure = $this;
        $structureTranslation->model = $model;
        return $structureTranslation;
    }

}
