<?php

namespace common\modules\shop;

use Yii;

/**
 * shop module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'common\modules\shop\controllers';

    /**
     * {}
     * @param $category
     * @param $message
     * @param array $params
     * @param null $language
     * @return string
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/shop/' . $category, $message, $params, $language);
    }
}
