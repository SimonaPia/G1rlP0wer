<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Fonte".
 *
 * @property int $ID
 * @property string|null $Fonte
 * @property float|null $Indice
 */
class Fonte extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Fonte';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Indice'], 'number'],
            [['Fonte'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'Fonte' => 'Fonte',
            'Indice' => 'Indice',
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
