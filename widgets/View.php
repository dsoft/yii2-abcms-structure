<?php

namespace abcms\structure\widgets;

use Yii;
use abcms\structure\widgets\WidgetBase;

class View extends WidgetBase
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        $fields = $this->structure->fields;
        $attributes = [];
        foreach($fields as $field) {
            $attributes[] = $field->getDetailViewAttribute();
        }
        if($attributes) {
            return $this->render('view', [
                        'attributes' => $attributes,
                        'title' => $this->title,
            ]);
        }
    }

}
