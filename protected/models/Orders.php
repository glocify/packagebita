<?php

/**
 * This is the model class for table "posts".
 *
 * The followings are the available columns in table 'posts':
 * @property integer $id
 * @property string $title
 * @property string $content
 */
class Orders extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }  
	public function tableName()
	{
		return 'cads_Orders';
	}
	public function findByOrder($ordernumber)
	{
		return self::model()->findByAttributes(array('id' => $ordernumber));
	}
}
