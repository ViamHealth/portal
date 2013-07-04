<?php

/**
 * This is the model class for table "{{healthfiles}}".
 *
 * The followings are the available columns in table '{{healthfiles}}':
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $description
 * @property string $mime_type
 * @property string $stored_url
 * @property string $created_at
 * @property string $updated_at
 * @property integer $updated_by
 *
 * The followings are the available model relations:
 * @property HealthfileTags[] $healthfileTags
 * @property Users $user
 */
class Healthfile extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Healthfile the static model class
	 */
	//Temporary var to make forms simple
	public $tagsCsv;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{healthfiles}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('description', 'required'),
			array('name, mime_type, stored_url', 'length', 'max'=>256),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, name, description, mime_type, stored_url, created_at, updated_at, updated_by', 'safe', 'on'=>'search'),
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
			'healthfileTags' => array(self::HAS_MANY, 'HealthfileTag', 'healthfile_id'),
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
			'name' => 'File Name',
			'description' => 'Label',
			'mime_type' => 'Mime Type',
			'stored_url' => 'Stored Url',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
			'updated_by' => 'Updated By',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @param array $filters criterias to filter on
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($filters=array())
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

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
			$criteria->compare('name',$this->name,true);
			$criteria->compare('description',$this->description,true);
			$criteria->compare('mime_type',$this->mime_type,true);
			$criteria->compare('stored_url',$this->stored_url,true);
			$criteria->compare('created_at',$this->created_at,true);
			$criteria->compare('updated_at',$this->updated_at,true);
			$criteria->compare('updated_by',$this->updated_by);
		}
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

  public function getTagsArray(){
  	$tags = array();
  	foreach ($this->healthfileTags as $key => $value) {
  		$tags[] = $value->tag;
  	}
  	return $tags;
  }

  public function behaviors()
    {
        return array('ESaveRelatedBehavior' => array(
                'class' => 'application.components.ESaveRelatedBehavior')
        );
    }
}