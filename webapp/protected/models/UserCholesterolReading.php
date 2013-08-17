<?php

/**
 * This is the model class for table "tbl_user_cholesterol_readings".
 *
 * The followings are the available columns in table 'tbl_user_cholesterol_readings':
 * @property integer $id
 * @property integer $user_cholesterol_goal_id
 * @property integer $hdl
 * @property integer $ldl
 * @property integer $triglycerides
 * @property integer $total_cholesterol
 * @property string $reading_date
 * @property string $created_at
 * @property string $updated_at
 * @property integer $updated_by
 *
 * The followings are the available model relations:
 * @property AuthUser $updatedBy
 * @property UserCholesterolGoals $userCholesterolGoal
 */
class UserCholesterolReading extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserCholesterolReading the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_user_cholesterol_readings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_cholesterol_goal_id, hdl, ldl, triglycerides, total_cholesterol, reading_date, created_at, updated_at, updated_by', 'required'),
			array('user_cholesterol_goal_id, hdl, ldl, triglycerides, total_cholesterol, updated_by', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_cholesterol_goal_id, hdl, ldl, triglycerides, total_cholesterol, reading_date, created_at, updated_at, updated_by', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'updatedBy' => array(self::BELONGS_TO, 'AuthUser', 'updated_by'),
			'userCholesterolGoal' => array(self::BELONGS_TO, 'UserCholesterolGoals', 'user_cholesterol_goal_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_cholesterol_goal_id' => 'User Cholesterol Goal',
			'hdl' => 'Hdl',
			'ldl' => 'Ldl',
			'triglycerides' => 'Triglycerides',
			'total_cholesterol' => 'Total Cholesterol',
			'reading_date' => 'Reading Date',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
			'updated_by' => 'Updated By',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user_cholesterol_goal_id',$this->user_cholesterol_goal_id);
		$criteria->compare('hdl',$this->hdl);
		$criteria->compare('ldl',$this->ldl);
		$criteria->compare('triglycerides',$this->triglycerides);
		$criteria->compare('total_cholesterol',$this->total_cholesterol);
		$criteria->compare('reading_date',$this->reading_date,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);
		$criteria->compare('updated_by',$this->updated_by);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}