<?php

namespace cms\menu\common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

use creocoder\nestedsets\NestedSetsBehavior;
use creocoder\nestedsets\NestedSetsQueryBehavior;

/**
 * Main menu active record
 */
class Menu extends ActiveRecord
{

	/**
	 * Menu item types
	 */
	const TYPE_SECTION = 0;
	const TYPE_LINK = 1;
	const TYPE_PAGE = 2;
	const TYPE_GALLERY = 3;
	const TYPE_CONTACTS = 4;
	const TYPE_NEWS = 5;

	/**
	 * Making available type list
	 * @return array
	 */
	public static function getTypeList()
	{
		$typeList = [
			self::TYPE_SECTION => Yii::t('menu', 'Section'),
			self::TYPE_LINK => Yii::t('menu', 'Link'),
		];

		foreach (Yii::$app->controller->module->module->getModules(false) as $module) {
			if (is_string($module)) {
				$className = $module;
			} elseif (is_array($module)) {
				$className = $module['class'];
			} else {
				$className = $module::className();
			}

			if ($className == 'cms\page\backend\Module')
				$typeList[self::TYPE_PAGE] = Yii::t('menu', 'Page');

			if ($className == 'cms\gallery\backend\Module')
				$typeList[self::TYPE_GALLERY] = Yii::t('menu', 'Gallery');

			if ($className == 'cms\contact\backend\Module')
				$typeList[self::TYPE_CONTACTS] = Yii::t('menu', 'Contacts');

			if ($className == 'cms\news\backend\Module')
				$typeList[self::TYPE_NEWS] = Yii::t('menu', 'News');
		}

		return $typeList;
	}

	/**
	 * Make alias list for specifid type
	 * @param integer $type Menu item type
	 * @return array
	 */
	public static function getAliasList($type)
	{
		if ($type == self::TYPE_PAGE)
			return self::getPageAliasList();

		if ($type == self::TYPE_GALLERY)
			return self::getGalleryAliasList();

		return [];
	}

	/**
	 * Make pages alias list
	 * @return array
	 */
	protected static function getPageAliasList()
	{
		$items = [];

		foreach (\cms\page\common\models\Page::find()->select(['alias', 'title'])->asArray()->all() as $row) {
			$items[$row['alias']] = $row['title'];
		}

		return $items;
	}

	/**
	 * Make gallery alias list
	 * @return array
	 */
	protected static function getGalleryAliasList()
	{
		$items = [];

		foreach (\cms\gallery\common\models\Gallery::find()->select(['alias', 'title'])->asArray()->all() as $row) {
			$items[$row['alias']] = $row['title'];
		}

		return $items;
	}

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Menu';
	}

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		$this->active = true;
		$this->type = self::TYPE_LINK;
		$this->url = '#';
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'name' => Yii::t('menu', 'Name'),
			'active' => Yii::t('menu', 'Active'),
			'type' => Yii::t('menu', 'Type'),
			'url' => Yii::t('menu', 'Url'),
		];
	}

	/**
	 * Find by alias
	 * @param sring $alias Alias or id
	 * @return Menu
	 */
	public static function findByAlias($alias) {
		$model = static::findOne(['alias' => $alias]);
		if ($model === null)
			$model = static::findOne(['id' => $alias]);

		return $model;
	}

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'tree' => [
				'class' => NestedSetsBehavior::className(),
				'treeAttribute' => 'tree',
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public static function find()
	{
		return new MenuQuery(get_called_class());
	}

}

/**
 * Main menu active query
 */
class MenuQuery extends ActiveQuery
{

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			NestedSetsQueryBehavior::className(),
		];
	}

}
