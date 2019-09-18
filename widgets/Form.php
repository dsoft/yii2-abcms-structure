<?php

namespace abcms\structure\widgets;

use Yii;
use yii\base\InvalidConfigException;

class Form extends WidgetBase
{
    
    /**
     * ActiveForm that the active fields belongs to
     * @var \yii\widgets\ActiveForm
     */
    public $form;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if(!$this->form) {
            throw new InvalidConfigException('"form" property must be set.');
        }
        $this->fillFieldsValues(Yii::$app->request->post('field'));
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        $structure = $this->structure;
        if(!$structure){
            return;
        }
        $dynamicModel = $structure->getDynamicModel();
        $fields = $structure->fields;
        if($fields) {
            return $this->render('form', [
                'title' => $this->title,
                'fields' => $fields,
                'model' => $this->model,
                'dynamicModel' => $dynamicModel,
                'form' => $this->form,
            ]);
        }
    }
    
    /**
     * Fill fields values
     * @param array $data key:field id, value: field value
     */
    protected function fillFieldsValues($data){
        if($this->structure){
            $fields = $this->structure->fields;
            foreach($fields as $field){
                if(isset($data[$field->id])){
                    $field->value = $data[$field->id];
                }
            }
        }
    }

}
