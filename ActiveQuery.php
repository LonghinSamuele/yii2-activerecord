<?php


namespace samuelelonghin\db;

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
    public $to_attribute = 'nome';


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
        $this->from_attribute = $this->modelClass::primaryKey()[0];
    }


    /**
     * @param string $attribute the attribute to list
     * @return string
     */
    public function asStringList($attribute = 'nome'): string
    {
        $text = '';
        foreach ($this->all() as $item) {
            $text .= $item->$attribute . '<br>';
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


}