<?php

namespace abcms\structure\widgets;

use Yii;

class Form extends WidgetBase
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->fillFieldsValues(Yii::$app->request->post('field'));
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        $structure = $this->structure;
        $fields = $structure->fields;
        if($fields) {
            return $this->render('form', [
                'title' => $this->title,
                'fields' => $fields,
                'model' => $this->model,
            ]);
        }
    }
    
    /**
     * Fill fields values
     * @param array $data key:field id, value: field value
     */
    protected function fillFieldsValues($data){
        $fields = $this->structure->fields;
        foreach($fields as $field){
            if(isset($data[$field->id])){
                $field->value = $data[$field->id];
            }
        }
    }

}
