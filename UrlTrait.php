<?php

namespace samuelelonghin\db;


use yii\helpers\Url;

trait UrlTrait
{
    public static abstract function getController();

    protected static $default_route = 'index';

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
    public static function getUrlString($params): string
    {
        return Url::toRoute(static::getUrlTo($params));
    }

    /**
     * @param $params
     * @return string
     * @deprecated
     */
    public static function getUrl($params)
    {
        return self::getUrlString($params);
    }

    /**
     * ```php
     * User::getUrlTo('all') // 'user/all'
     * User::getUrlTo(['view', 'id' => 4]) // 'user/view?id=4'
     * User::getUrlTo() // 'user/index'
     * ```
     * @param $params accepts array with the standard yii2 format ['action', 'p1' => v1, '#' => 'paragraph']
     * and accepts also only the action value.
     * @return array|string[]
     */
    public static function getUrlTo($params = null): array
    {
        // 'view' => ['view']
        if (is_string($params))
            $params = [$params];

        // null => []
        if (!is_array($params))
            $params = [];

        if (array_key_exists(0, $params)) {
            $components = explode('/', $params[0]);
            $action = $components[sizeof($components) - 1];
            $params[0] = $action;
        } else
            $params[0] = static::$default_route;

        // ['view', 'id' => 4] => ['controller/view', 'id' =>4]
        $params[0] = self::getBaseUrl($params[0]);
        return $params;
    }

}
