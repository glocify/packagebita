<?php

/**
 * This is the model class for table "posts".
 *
 * The followings are the available columns in table 'posts':
 * @property integer $id
 * @property string $title
 * @property string $content
 */
class Jobs extends CActiveRecord
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
		return 'cads_Jobs';
	}
	
	public function findByEmail($email)
	  {
		return self::model()->findByAttributes(array('email' => $email));
	  }

}
