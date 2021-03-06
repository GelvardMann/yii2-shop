<?php

use common\modules\shop\models\backend\Attribute;
use common\modules\shop\Module;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\shop\models\backend\AttributeValue */
/* @var $relatedData Attribute relations */

$this->title = Module::t('module', 'CREATE_ATTRIBUTE_VALUE');
$this->params['breadcrumbs'][] = ['label' => Module::t('module', 'ATTRIBUTE_VALUES'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attribute-value-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'relatedData' => $relatedData,
    ]) ?>

</div>
