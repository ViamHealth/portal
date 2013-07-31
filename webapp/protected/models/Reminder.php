<?php

/**
 * This is the model class for table "{{reminders}}".
 *
 * The followings are the available columns in table '{{reminders}}':
 * @property integer $id
 * @property integer $user_id
 * @property string $details
 * @property integer $start_datetime
 * @property string $repeat_mode
 * @property string $repeat_day
 * @property string $repeat_hour
 * @property string $repeat_min
 * @property string $repeat_weekday
 * @property string $repeat_day_interval
 * @property string $status
 * @property string $created_at 
 * @property string $updated_at
 * @property integer $updated_by
 *
 * The followings are the available model relations:
 * @property Users $user
 */
class Reminder extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Reminder the static model class
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
		return 'tbl_reminders';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, details, start_datetime', 'required'),
			#array('user_id, updated_by', 'numerical', 'integerOnly'=>true),
			#array('repeat_mode', 'length', 'max'=>32),
			#array('repeat_day, repeat_hour, repeat_min', 'length', 'max'=>2),
			#array('repeat_weekday', 'length', 'max'=>9),
			#array('repeat_day_interval', 'length', 'max'=>3),
			#array('status', 'length', 'max'=>18),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, details, start_datetime, repeat_mode, repeat_day, repeat_hour, repeat_min, repeat_weekday, repeat_day_interval, status, created_at, updated_at, updated_by', 'safe'),
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
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
			'details' => 'Reminder',
			'start_datetime' => 'Date',
			'repeat_mode' => 'Repeat Mode',
			'repeat_day' => 'Repeat Day',
			'repeat_hour' => 'Repeat Hour',
			'repeat_min' => 'Repeat Min',
			'repeat_weekday' => 'Repeat Weekday',
			'repeat_day_interval' => 'Repeat Day Interval',
			'status' => 'Status',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
			'updated_by' => 'Updated By',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($filters=array())
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria= new CDbCriteria;

		if(is_array($filters) && count($filters)){
			if(isset($filters['user_id'])){
				$criteria->compare('user_id',$filters['user_id']);
				//hardcoding order for now
				$criteria->order = 'updated_at DESC, created_at DESC';
			}
		}
		else {
		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('details',$this->details,true);
		$criteria->compare('start_datetime',$this->start_datetime,true);
		$criteria->compare('repeat_mode',$this->repeat_mode,true);
		$criteria->compare('repeat_day',$this->repeat_day,true);
		$criteria->compare('repeat_hour',$this->repeat_hour,true);
		$criteria->compare('repeat_min',$this->repeat_min,true);
		$criteria->compare('repeat_weekday',$this->repeat_weekday,true);
		$criteria->compare('repeat_day_interval',$this->repeat_day_interval,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);
		$criteria->compare('updated_by',$this->updated_by);
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function save($runValidation=true,$attributes=null)
	{
		if(empty($attributes)) $attributes = $this->attributes;
		if(!$runValidation || $this->validate($attributes))
		{
			$attributes['user'] = Yii::app()->user->url;
			$attributes['start_datetime'] = strtotime($attributes['start_datetime']);
			/*$attributes['repeat_mode'] = 'NONE';
			$attributes['repeat_day'] = 0;
			$attributes['repeat_hour'] = 0;
			$attributes['repeat_min'] = 0;
			$attributes['repeat_weekday'] = 0;
			$attributes['repeat_day_interval'] = 0;
			$attributes['status'] = 'ACTIVE';*/
			$url = 'reminders/';
			if(isset($attributes['id'])){
				$url = $url.$attributes['id'].'/';
			}
			$a = VApi::apiCall('put', $url, $attributes);
			return true;
			//return $this->getIsNewRecord() ? $this->insert($attributes) : $this->update($attributes);
		}
		else{
			return false;
		}
			
	}
	/*public function beforeSave() {
    if ($this->isNewRecord)
        $this->created_at = new CDbExpression('NOW()');
    else
        $this->updated_at = new CDbExpression('NOW()');
    $this->updated_by = Yii::app()->user->id;
 
    return parent::beforeSave();
  }*/
}