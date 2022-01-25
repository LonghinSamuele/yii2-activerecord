<?php

namespace samuelelonghin\db;

use Da\QrCode\QrCode;
use samuelelonghin\qr\ModelSerializable;
use Yii;
use yii\base\Exception;

/**
 * @property string $id
 * @property QrCode $qr
 * @property string $qrSvg
 */
abstract class ActiveRecord extends \yii\db\ActiveRecord implements GridInterface
{
    use ModelSerializable;

    /**
     * @return ActiveQuery
     */
    public static function find()
    {
        return new ActiveQuery(static::class);
    }

    /**
     * @param string $class
     * @param array $link
     * @return ActiveQuery
     */
    public function hasMany($class, $link)
    {
        return parent::hasMany($class, $link);
    }

    /**
     * @param string $class
     * @param array $link
     * @return ActiveQuery
     */
    public function hasOne($class, $link)
    {
        return parent::hasOne($class, $link);
    }

    public static function queryFrom()
    {
        return null;
    }
}
