<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Utente".
 *
 * @property int $ID
 * @property string|null $Nome
 * @property string|null $Cognome
 * @property string $Email
 * @property string $Password
 */
class UtenteRegistrato extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Utente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Email', 'Password'], 'required'],
            [['Nome'], 'string', 'max' => 30],
            [['Cognome'], 'string', 'max' => 50],
            [['Email'], 'string', 'max' => 80],
            [['Password'], 'string', 'max' => 15],
            [['Email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'Nome' => 'Nome',
            'Cognome' => 'Cognome',
            'Email' => 'Email',
            'Password' => 'Password',
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
}
