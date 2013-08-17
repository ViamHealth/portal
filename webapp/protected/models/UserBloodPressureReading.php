<?php

/**
 * This is the model class for table "tbl_user_blood_pressure_readings".
 *
 * The followings are the available columns in table 'tbl_user_blood_pressure_readings':
 * @property integer $id
 * @property integer $user_blood_pressure_goal_id
 * @property integer $systolic_pressure
 * @property integer $diastolic_pressure
 * @property integer $pulse_rate
 * @property string $reading_date
 * @property string $created_at
 * @property string $updated_at
 * @property integer $updated_by
 *
 * The followings are the available model relations:
 * @property AuthUser $updatedBy
 * @property UserBloodPressureGoals $userBloodPressureGoal
 */
class UserBloodPressureReading extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserBloodPressureReading the static model class
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
		return 'tbl_user_blood_pressure_readings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_blood_pressure_goal_id, systolic_pressure, diastolic_pressure, pulse_rate, reading_date, created_at, updated_at, updated_by', 'required'),
			array('user_blood_pressure_goal_id, systolic_pressure, diastolic_pressure, pulse_rate, updated_by', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_blood_pressure_goal_id, systolic_pressure, diastolic_pressure, pulse_rate, reading_date, created_at, updated_at, updated_by', 'safe', 'on'=>'search'),
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
			'userBloodPressureGoal' => array(self::BELONGS_TO, 'UserBloodPressureGoals', 'user_blood_pressure_goal_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_blood_pressure_goal_id' => 'User Blood Pressure Goal',
			'systolic_pressure' => 'Systolic Pressure',
			'diastolic_pressure' => 'Diastolic Pressure',
			'pulse_rate' => 'Pulse Rate',
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
		$criteria->compare('user_blood_pressure_goal_id',$this->user_blood_pressure_goal_id);
		$criteria->compare('systolic_pressure',$this->systolic_pressure);
		$criteria->compare('diastolic_pressure',$this->diastolic_pressure);
		$criteria->compare('pulse_rate',$this->pulse_rate);
		$criteria->compare('reading_date',$this->reading_date,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);
		$criteria->compare('updated_by',$this->updated_by);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}