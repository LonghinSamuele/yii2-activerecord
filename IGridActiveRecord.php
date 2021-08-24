<?php

namespace samuelelonghin\db;


interface IGridActiveRecord
{

    public static function getGridViewColumns();

    public static function getController();

    public function getId();

    public function setId($value);

}
