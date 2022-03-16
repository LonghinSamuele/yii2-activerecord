<?php

namespace samuelelonghin\db;


use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\db\ActiveQueryInterface;
use yii\db\ActiveQueryTrait;
use yii\db\ActiveRelationTrait;
use yii\helpers\ArrayHelper;

/**
 * Class ActiveQuery
 * @package samuelelonghin\db
 * @property ActiveRecord $modelClass
 */
class ActiveQuery extends \yii\db\ActiveQuery implements ActiveQueryInterface
{
	use ActiveQueryTrait;
	use ActiveRelationTrait;

	public $from_attribute = 'id';
	public string $to_attribute = 'nome';


	/**
	 * @param null $db
	 * @return ActiveRecord[]
	 */
	public function all($db = null)
	{
		return parent::all();
	}

	/**
	 * @param null $db
	 * @return ActiveRecord
	 */
	public function one($db = null)
	{
		return parent::one();
	}

	/**
	 * Map all items ['id' => 'name']
	 * @return array
	 */
	public function asMappedArrayNameId(): array
	{
		return ArrayHelper::map($this->all(), $this->from_attribute, $this->to_attribute);
	}

	/**
	 * Map all items ['name' => 'name']
	 * @return array
	 */
	public function asMappedArrayNameName(): array
	{
		return ArrayHelper::map($this->all(), $this->to_attribute, $this->to_attribute);
	}

	/**
	 * Map all items ['param1' => 'param2']
	 * @param $from
	 * @param $to
	 * @return array
	 */
	public function asMappedArray($from, $to): array
	{
		return ArrayHelper::map($this->all(), $from, $to);
	}

	/**
	 * @return array
	 * @throws NotSupportedException
	 * @deprecated
	 */
	public function asSelect2Value(): array
	{
		throw new NotSupportedException('non supportato');
		return array_keys($this->asSelect2Array());
	}

	/**
	 * @return array
	 */
	public function asMappedArrayId(): array
	{
		return array_keys($this->asMappedArrayNameId());
	}

	/**
	 * @throws NotSupportedException
	 * @deprecated
	 */
	private function asSelect2Array()
	{
		throw new NotSupportedException('non supportato, usa asMappedArrayNameId()');
	}


	/**
	 * @throws NotSupportedException
	 * @deprecated
	 */
	private function asSelect2Tags()
	{
		throw new NotSupportedException('non supportato, usa asMappedArrayNameName()');
	}

	public function init()
	{
		parent::init();
		if (!$this->from_attribute && !$this->modelClass::queryFrom())
			$this->from_attribute = $this->modelClass::primaryKey()[0];
	}


	/**
	 * @param string $attribute the attribute to list
	 * @param string $separator
	 * @param null $function ($value,$model,$index): string
	 * @return string
	 */
	public function asStringList(string $attribute = 'nome', $separator = '<br>', $function = null): string
	{
		$text = '';
		if ($function) {
			foreach ($this->all() as $item) {
				$text .= call_user_func($function, $item->$attribute, $item, $item->id) . $separator;
			}
		} else {
			foreach ($this->all() as $item) {
				$text .= $item->$attribute . $separator;
			}
		}
		return $text;
	}

	/**
	 * @param bool $value
	 * @return $this
	 */
	public function multiple(bool $value = true): ActiveQuery
	{
		$this->multiple = $value;
		return $this;
	}

	/**
	 * return $this->select($this->modelClass::primaryKey());
	 * @return $this
	 */
	public function id(): ActiveQuery
	{
		if ($this->modelClass)
			return $this->select($this->modelClass::primaryKey());
		return $this;
	}


	/**
	 * @return string|ActiveQuery query
	 */
	protected function getQueryFrom()
	{
		$modelClass = $this->modelClass;
		return $modelClass::queryFrom();
	}

	/**
	 * {@inheritdoc}
	 */
	public function prepare($builder)
	{
		if (empty($this->from)) {
			/**
			 * this lines anable using subquery as from table
			 * It's necessary to return a subquery from ActiveRecord::getQueryFrom()
			 */
			if ($this->getQueryFrom())
				$this->from = [$this->getQueryFrom()];
			else
				$this->from = [$this->getPrimaryTableName()];

		}
		return parent::prepare($builder);
	}

}