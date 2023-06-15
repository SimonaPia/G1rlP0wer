<?php
namespace app\models;

use Yii;
use yii\base\Model;


class RegistrationForm extends Model
{
    public $nome;
    public $cognome;
    public $email;
    public $password;

    public function rules()
    {
        return [
            [['nome', 'cognome', 'email', 'password'], 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => 'app\models\UtenteRegistrato', 'message' => 'Questo indirizzo email è già registrato.'],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function registration()
    {
        if ($this->validate()) {
            $user = new UtenteRegistrato();
            $user->Nome = $this->nome;
            $user->Cognome = $this->cognome;
            $user->Email = $this->email;
            $user->setPassword($this->password);

            if ($user->save()) {
                return true;
            }
        }

        return false;
    }
}