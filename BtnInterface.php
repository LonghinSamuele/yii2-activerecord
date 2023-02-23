<?php

namespace samuelelonghin\db;


interface BtnInterface
{

	public static function getController(): string;

	public function getId();

}