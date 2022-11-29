<?php

namespace samuelelonghin\db;


use Exception;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQueryInterface;
use yii\db\ActiveQueryTrait;
use yii\db\ActiveRelationTrait;
use yii\helpers\ArrayHelper;

/**
 * Class ActiveQuery
 * @package samuelelonghin\db
 * @property-read null|string|ActiveQuery $queryFrom
 * @property ActiveRecord $modelClass
 */
class ActiveQuery extends \yii\db\ActiveQuery implements ActiveQueryInterface
{
    use ActiveQueryTrait;
    use ActiveRelationTrait;

    public ?string $from_attribute = 'id';
    public ?string $to_attribute = 'nome';


    public function init()
    {
        parent::init();
        if (!$this->from_attribute && !$this->modelClass::queryFrom())
            $this->from_attribute = $this->modelClass::primaryKey()[0];
    }

    /**
     * @param null $db
     * @return ActiveRecord[]
     */
    public function all($db = null): array
    {
        return parent::all();
    }

    /**
     * @param null $db
     * @return ActiveRecord|array|null
     */
    public function one($db = null): ActiveRecord|array|null
    {
        return parent::one();
    }


    public function asMappedIdName($from = null, $to = null): ActiveQuery
    {
        if (!$from) $from = $this->from_attribute;
        if (!$to) $to = $this->to_attribute;
        return $this->select(['id' => $from, 'name' => $to])->asArray();
    }

    public function asMappedArrayIdName($from = null, $to = null): array
    {
        return $this->asMappedIdName($from, $to)->all();
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
     */
    public function asMappedArrayId(): array
    {
        return array_keys($this->asMappedArrayNameId());
    }

    public function aggregateColumn($label, $column, $group = false): self
    {
        if ($group)
            $this->groupBy($group);
        $group[$label] = "group_concat( $column )";
        return $this->addSelect($group);
    }


    /**
     * @param string|null $attribute the attribute to list
     * @param string $separator
     * @param null $function ($value,$model,$index): string
     * @return string
     */
    public function asStringList(?string $attribute = null, string $separator = '<br>', $function = null): string
    {
        if (!$attribute)
            $attribute = $this->to_attribute;
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

    public function asDataProvider($options = []): ActiveDataProvider
    {
        $options['query'] = $this;
        return new ActiveDataProvider($options);
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
     * @throws Exception
     */
    public function get($select, $default = null) 
    {
        return ArrayHelper::getValue($this->select($select)->asArray()->one(), $select, $default);
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
     * @return ActiveQuery|string|null query
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
