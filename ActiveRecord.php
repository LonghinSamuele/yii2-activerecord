<?php

namespace samuelelonghin\db;

use app\models\Struttura;
use Da\QrCode\QrCode;
use samuelelonghin\qr\ModelSerializable;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * @property string $id
 * @property QrCode $qr
 * @property string $qrSvg
 */
abstract class ActiveRecord extends \yii\db\ActiveRecord implements GridInterface
{
	use ModelSerializable;
	use UrlTrait;

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
	 */
	public function hasOne($class, $link)
	{
		return parent::hasOne($class, $link);
	}

	public static function queryFrom()
	{
		return null;
	}

	/**
	 * Finds the static model based on its primary key value (or condition).
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @return static
	 * @throws NotFoundHttpException
	 */
	public static function getOne($condition)
	{
		if (($model = static::findOne($condition)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException(Yii::t('samuelelonghin/active-record', 'The requested model does not exist.'));
	}
}
