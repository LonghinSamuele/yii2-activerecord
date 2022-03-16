<?php

namespace samuelelonghin\db;


use yii\helpers\Url;

trait UrlTrait
{
	public static abstract function getController();

	protected static $default_route = 'view';

	/**
	 * @param string $action
	 * @return string
	 */
	public static function getBaseUrl(string $action): string
	{
		return '/' . static::getController() . '/' . $action;
	}

	/**
	 * @param $params
	 * @return string
	 */
	public static function getUrl($params): string
	{
		if (is_array($params)) {
			return Url::to(static::getUrlTo($params));
		}
		return $params;
	}

	public static function getUrlTo($params): ?array
	{
		if (is_array($params)) {
			if (array_key_exists(0, $params)) {
				$components = explode('/', $params[0]);
				$action = $components[sizeof($components) - 1];
				$params[0] = static::getBaseUrl($action);
			} else
				$params[0] = static::$default_route;
			return $params;
		}
		return null;
	}

}
