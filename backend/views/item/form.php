<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

use cms\menu\backend\assets\MenuFormAsset;
use cms\menu\common\models\Menu;

MenuFormAsset::register($this);

$typesWithName = Menu::getTypesWithName();
$typesWithUrl = Menu::getTypesWithUrl();
$typesWithAlias = Menu::getTypesWithAlias();

$typeOptions = [];
if ($model->getObject()->children()->count() > 0)
	$typeOptions['disabled'] = true;

$nameOptions = [];
if (!in_array($model->type, $typesWithName))
	$nameOptions['options'] = ['class' => 'form-group hidden'];

$urlOptions = [];
if (!in_array($model->type, $typesWithUrl))
	$urlOptions['options'] = ['class' => 'form-group hidden'];

$aliasOptions = ['options' => [
	'data-url' => Url::toRoute('alias'),
	'class' => 'form-group',
]];
if (!in_array($model->type, $typesWithAlias))
	Html::addCssClass($aliasOptions['options'], 'hidden');

?>
<?php $form = ActiveForm::begin([
	'layout' => 'horizontal',
	'enableClientValidation' => false,
	'options' => [
		'class' => 'menu-form',
		'data-types-with-name' => $typesWithName,
		'data-types-with-url' => $typesWithUrl,
		'data-types-with-alias' => $typesWithAlias,
	],
]); ?>

	<?= $form->field($model, 'active')->checkbox() ?>

	<?= $form->field($model, 'type')->dropDownList(Menu::getTypeNames(), $typeOptions) ?>

	<?= $form->field($model, 'name', $nameOptions) ?>

	<?= $form->field($model, 'url', $urlOptions) ?>

	<?= $form->field($model, 'alias', $aliasOptions)->dropDownList($model->getAliasList()) ?>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<?= Html::submitButton(Yii::t('menu', 'Save'), ['class' => 'btn btn-primary']) ?>
			<?= Html::a(Yii::t('menu', 'Cancel'), ['menu/index', 'id' => $id], ['class' => 'btn btn-default']) ?>
		</div>
	</div>

<?php ActiveForm::end(); ?>
