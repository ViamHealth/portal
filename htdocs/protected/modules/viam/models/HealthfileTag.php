<?php

/**
 * This is the model class for table "{{healthfile_tags}}".
 *
 * The followings are the available columns in table '{{healthfile_tags}}':
 * @property integer $id
 * @property integer $healthfile_id
 * @property string $tag
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Healthfiles $healthfile
 */
class HealthfileTag extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return HealthfileTag the static model class
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
		return '{{healthfile_tags}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tag', 'required'),
			array('tag', 'length', 'max'=>64),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, healthfile_id, tag, created_at', 'safe', 'on'=>'search'),
			array('id, tag', 'safe', 'on'=>'rest'),
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
			'healthfile' => array(self::BELONGS_TO, 'Healthfile', 'healthfile_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'healthfile_id' => 'Healthfile',
			'tag' => 'Tag',
			'created_at' => 'Created At',
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
		$criteria->compare('healthfile_id',$this->healthfile_id);
		$criteria->compare('tag',$this->tag,true);
		$criteria->compare('created_at',$this->created_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function beforeSave() {
    if ($this->isNewRecord)
        $this->created_at = new CDbExpression('NOW()');
 
    return parent::beforeSave();
  }
}