<?php


namespace samuelelonghin\db;


class StringIdLenght extends \yii\base\Component
{
    public $stringIdLenght = 10;

    public function getStringIdLenght()
    {
        if (is_callable($this->stringIdLenght))
            $this->stringIdLenght = call_user_func($this->stringIdLenght);
        return $this->stringIdLenght;
    }
}