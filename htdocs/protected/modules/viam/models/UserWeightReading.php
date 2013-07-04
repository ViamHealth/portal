<?php

/**
 * This is the model class for table "{{user_weight_readings}}".
 *
 * The followings are the available columns in table '{{user_weight_readings}}':
 * @property integer $id
 * @property integer $user_weight_goal_id
 * @property integer $weight
 * @property string $weight_measure
 * @property string $reading_date
 * @property string $created_at
 * @property string $updated_at
 * @property integer $updated_by
 *
 * The followings are the available model relations:
 * @property UserWeightGoals $userWeightGoal
 */
class UserWeightReading extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserWeightReading the static model class
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
		return '{{user_weight_readings}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_weight_goal_id, weight, weight_measure, reading_date, created_at, updated_at, updated_by', 'required'),
			array('user_weight_goal_id, weight, updated_by', 'numerical', 'integerOnly'=>true),
			array('weight_measure', 'length', 'max'=>12),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_weight_goal_id, weight, weight_measure, reading_date, created_at, updated_at, updated_by', 'safe', 'on'=>'search'),
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
			'userWeightGoal' => array(self::BELONGS_TO, 'UserWeightGoal', 'user_weight_goal_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_weight_goal_id' => 'User Weight Goal',
			'weight' => 'Weight',
			'weight_measure' => 'Weight Measure',
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
		$criteria->compare('user_weight_goal_id',$this->user_weight_goal_id);
		$criteria->compare('weight',$this->weight);
		$criteria->compare('weight_measure',$this->weight_measure,true);
		$criteria->compare('reading_date',$this->reading_date,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);
		$criteria->compare('updated_by',$this->updated_by);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function beforeSave() {
    if ($this->isNewRecord)
        $this->created_at = new CDbExpression('NOW()');
    else
        $this->updated_at = new CDbExpression('NOW()');
    $this->updated_by = Yii::app()->user->id;
 
    return parent::beforeSave();
  }
}