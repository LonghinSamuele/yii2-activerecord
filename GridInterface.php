<?php

namespace samuelelonghin\db;


interface GridInterface extends BtnInterface
{

    public static function getGridViewColumns();

    public function setId($value);

}
