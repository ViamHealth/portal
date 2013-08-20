<?php

/**
 * This is the model class for table "tbl_healthfiles".
 *
 * The followings are the available columns in table 'tbl_healthfiles':
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $description
 * @property string $mime_type
 * @property string $file
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $updated_by
 *
 * The followings are the available model relations:
 * @property HealthfileTags[] $healthfileTags
 * @property AuthUser $updatedBy
 * @property AuthUser $user
 */
class Healthfile extends CActiveRecord
{
	public function resourceUrl()
    {
    	return 'healthfiles/';
    }
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Healthfile the static model class
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
		return 'tbl_healthfiles';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, name, description, mime_type, file, status, created_at, updated_at, updated_by', 'required'),
			array('user_id, updated_by', 'numerical', 'integerOnly'=>true),
			array('name, mime_type, file', 'length', 'max'=>256),
			array('status', 'length', 'max'=>18),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, name, description, mime_type, file, status, created_at, updated_at, updated_by', 'safe', 'on'=>'search'),
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
			'healthfileTags' => array(self::HAS_MANY, 'HealthfileTags', 'healthfile_id'),
			'updatedBy' => array(self::BELONGS_TO, 'AuthUser', 'updated_by'),
			'user' => array(self::BELONGS_TO, 'AuthUser', 'user_id'),
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
			'name' => 'Name',
			'description' => 'Description',
			'mime_type' => 'Mime Type',
			'file' => 'File',
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

		$criteria=new CDbCriteria;

		if(is_array($filters) && count($filters)){
			if(isset($filters['user_id'])){
				$criteria->compare('user_id',$filters['user_id']);
				$criteria->compare('status',$filters['status']);
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
		$criteria->compare('file',$this->file,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);
		$criteria->compare('updated_by',$this->updated_by);
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function get_download_url()
	{
		return 'http://viamhealth-docsbucket.s3.amazonaws.com/'. $this->file;
	}

	public function getPk()
	{
		return $this->id;
	}
}
