<?php

namespace abcms\structure\classes;

use Yii;
use abcms\structure\models\Structure;
use abcms\library\fields\Field;
use abcms\structure\models\Meta;

/**
 * StructureTranslation class is used as a layer to hold structure/model classes 
 * and provide the necessary functions to enable multilanguage support for custom fields.
 */
class StructureTranslation implements \abcms\multilanguage\behaviors\MultilanguageInterface
{
    
    /**
     * @var Structure
     */
    public $structure;
    
    /**
     * @var \yii\db\ActiveRecord Model that the structure belongs to
     */
    public $model;
    
    /**
     * @var string the Multilanguage application component ID.
     */
    public $multilanguageId = 'multilanguage';
    
    /**
     * Return translation fields for a certain language after setting inputName, label, and value
     * @param string $language
     * @return \abcms\library\fields\Field[]
     */
    public function getLanguageFields($language)
    {
        $structure = $this->structure;
        $model = $this->model;
        $fields = $structure->translatableFields;
        $multilanguage = $this->getMultilanguage();
        
        $array = [];
        foreach($fields as $structureField)
        {
            $inputName = $this->getTranslationInputName($structureField->name, $language);
            $field = clone $structureField->getInputObject();
            // Translation fields are not required
            $field->isRequired = false;
            $field->inputName = $inputName;
            
            // Translation data are saved for Meta model
            $meta = Meta::find()->andWhere([
                'fieldId' => $structureField->id,
                'modelId' => $model->returnModelId(),
                'pk' => $model->id
            ])->one();
            if($meta){
                // Get translation of the Meta model
                $translation = $multilanguage->translation($meta, $language);
                if(isset($translation['value']))
                {
                    $field->value = $translation['value'];
                }
            }
            
            $array[] = $field;
        }
        return $array;
    }
    
    /**
     * Return the input name of the translation field
     * @param string $attribute
     * @param string $language
     * @return string
     */
    protected function getTranslationInputName($attribute, $language)
    {
        return $attribute.'_'.$language;
    }
    
    protected $_translationModel;
    
    /**
     * Return a DynamicModel with all translation languages fields
     * @return \yii\base\DynamicModel
     */
    public function getTranslationModel()
    {
        if(!$this->_translationModel){
            $multilanguage = $this->getMultilanguage();
            $languages = $multilanguage->getTranslationLanguages();
            $allLanguagesFields = [];
            foreach($languages as $languageCode => $languageName)
            {
                $allLanguagesFields = array_merge($allLanguagesFields, $this->getLanguageFields($languageCode));
            }
            $model = Field::getDynamicModel($allLanguagesFields);
            $model->defineAttribute('isLoaded', false);
            $this->_translationModel = $model;
        }
        
        return $this->_translationModel;        
    }
    
    /**
     * Return the multilanguage component
     * @return \abcms\multilanguage\MultilanguageBase
     */
    protected function getMultilanguage()
    {
        return Yii::$app->get($this->multilanguageId);
    }
    
    /**
     * Save translation data
     * @param array $data An array where the key is the attribute name
     * and value is the translation that should be saved
     */
    public function saveTranslationData($data)
    {
        $fields = $this->structure->translatableFields;
        $multilanguage = $this->getMultilanguage();
        $modelId = $this->model->returnModelId();
        $pk = $this->model->id;
        
        foreach($fields as $field){
            $meta = Meta::find()->andWhere([
                'fieldId' => $field->id,
                'modelId' => $modelId,
                'pk' => $pk
            ])->one();
            if($meta){
                $multilanguage->saveTranslation($meta, $data);
            }
        }
    }
}
