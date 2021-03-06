<?php

use yii\helpers\Html;

$title = Yii::t('menu', 'Create item');

$this->title = $title . ' | ' . Yii::$app->name;

$this->params['breadcrumbs'] = [
	['label' => Yii::t('menu', 'Menus'), 'url' => ['menu/index']],
	$title,
];

?>
<h1><?= Html::encode($title) ?></h1>

<?= $this->render('form', [
	'model' => $model,
	'id' => $id,
]) ?>
