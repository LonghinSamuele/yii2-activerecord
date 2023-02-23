<?php

namespace samuelelonghin\db;


interface GridInterface extends BtnInterface
{

    public static function getGridViewColumns(): array;

    public function setId($value);
}