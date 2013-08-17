<?php

/**
 * This is the model class for table "tbl_user_blood_pressure_goals".
 *
 * The followings are the available columns in table 'tbl_user_blood_pressure_goals':
 * @property integer $id
 * @property integer $user_id
 * @property string $target_date
 * @property integer $systolic_pressure
 * @property integer $diastolic_pressure
 * @property integer $pulse_rate
 * @property integer $interval_num
 * @property string $interval_unit
 * @property string $created_at
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $status
 *
 * The followings are the available model relations:
 * @property AuthUser $updatedBy
 * @property AuthUser $user
 * @property UserBloodPressureReadings[] $userBloodPressureReadings
 */
class UserBloodPressureGoal extends VCActiveRecord
{
	public function resourceUrl()
    {
    	return 'blood-pressure-goals/';
    }
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserBloodPressureGoal the static model class
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
		return 'tbl_user_blood_pressure_goals';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, target_date, systolic_pressure, diastolic_pressure, pulse_rate, interval_num, interval_unit, created_at, updated_at, updated_by, status', 'required'),
			array('user_id, systolic_pressure, diastolic_pressure, pulse_rate, interval_num, updated_by', 'numerical', 'integerOnly'=>true),
			array('interval_unit', 'length', 'max'=>6),
			array('status', 'length', 'max'=>18),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, target_date, systolic_pressure, diastolic_pressure, pulse_rate, interval_num, interval_unit, created_at, updated_at, updated_by, status', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'AuthUser', 'user_id'),
			'userBloodPressureReadings' => array(self::HAS_MANY, 'UserBloodPressureReadings', 'user_blood_pressure_goal_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'target_date' => 'Target Date',
			'systolic_pressure' => 'Systolic Pressure',
			'diastolic_pressure' => 'Diastolic Pressure',
			'pulse_rate' => 'Pulse Rate',
			'interval_num' => 'Interval Num',
			'interval_unit' => 'Interval Unit',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
			'updated_by' => 'Updated By',
			'status' => 'Status',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('target_date',$this->target_date,true);
		$criteria->compare('systolic_pressure',$this->systolic_pressure);
		$criteria->compare('diastolic_pressure',$this->diastolic_pressure);
		$criteria->compare('pulse_rate',$this->pulse_rate);
		$criteria->compare('interval_num',$this->interval_num);
		$criteria->compare('interval_unit',$this->interval_unit,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);
		$criteria->compare('updated_by',$this->updated_by);
		$criteria->compare('status',$this->status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}