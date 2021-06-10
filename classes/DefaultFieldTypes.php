<?php

namespace abcms\structure\classes;

use Yii;

class DefaultFieldTypes implements FieldTypesInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getTypes()
    {
        $array = [
            'drop-down' => Yii::t('abcms.structure', 'Drop Down List'),
            'file' => Yii::t('abcms.structure', 'File'),
            'image' => Yii::t('abcms.structure', 'Image'),
            'integer' => Yii::t('abcms.structure', 'Integer'),
            'multiple-choice-select' => Yii::t('abcms.structure', 'Multiple Choice Select'),
            'pdf' => Yii::t('abcms.structure', 'Pdf'),
            'text' => Yii::t('abcms.structure', 'Text'),
            'text-area' => Yii::t('abcms.structure', 'Text Area'),
            'text-editor' => Yii::t('abcms.structure', 'Text Editor'),
            'url' => Yii::t('abcms.structure', 'Url'),
            'video' => Yii::t('abcms.structure', 'Video'),
        ];
        return $array;
    }
}
