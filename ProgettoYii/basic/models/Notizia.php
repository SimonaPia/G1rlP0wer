<?php

namespace app\models;

use Yii;
use yii\base\Model;

class Notizia extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
	public $notizia;
	private $indiceDiAttendibilita;

	public static function tableName()
    {
        return 'Notizia';
    }

	public function rules()
	{
		return[
			[['Notizia'], 'required'],
			[['Notizia'], 'string', 'max' => 255],
            [['Categoria'], 'string', 'max' => 100],
            [['Argomento'], 'string', 'max' => 255],
            [['Incongruenze'], 'boolean'],
			//[['link'], 'url']
		];
	}

	public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'Notizia' => 'Notizia',
            'Indice' => 'Indice',
            'Categoria' => 'Categoria',
        ];
    }

    public function getAuthKey()
    {
        return null;
    }

    public function getId()
    {
        return $this->ID;
    }

    public function validateAuthKey($authKey)
    {
        //return $this->authKey===$authKey;
        return null;
    }

    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type=null)
    {
        throw new \yii\base\NotSupportedException();
    }

    public static function findByEmail($email)
    {
        return self::findOne(['Email'=>$email]);
    }

    public function validatePassword($password)
    {
        return $this->Password===$password;
    }

    public function setPassword($password)
    {
        $this->Password=$password;
    }

	public function inserimentoForm()
	{
		$link=$this->link;
	}

	public function getLink()
	{
		return $link;
	}
}