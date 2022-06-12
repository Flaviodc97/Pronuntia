<?php 

namespace app\models;
use yii\base\Model;
use yii\helpers\VarDumper;


class SignupForm extends Model
{
    public $nome;
    public $cognome;
    public $ntelefono;
    public $descrizione;
    public $email;
    public $pass;
    public $password_repeat;
    public $authKey;
    public $accessToken;
    public $tipo;


    public function rules()
    {
        return [
            [['nome', 'cognome', 'ntelefono', 'descrizione', 'email', 'pass','tipo'], 'required'],
            [['tipo'], 'integer'],
            [['nome', 'cognome', 'descrizione', 'email', 'pass', 'authKey', 'accessToken','ntelefono'], 'string', 'max' => 255],
            ['password_repeat','compare', 'compareAttribute'=>'pass']
        ];
    }
    public function attributeLabels()
    {
        return [
            'IdUser' => 'Id User',
            'nome' => 'Nome',
            'cognome' => 'Cognome',
            'ntelefono' => 'Numero di telefono',
            'descrizione' => 'Descrizione',
            'email' => 'Email',
            'pass' => 'Password',
            'password_repeat'=> 'Ripeti password',
            'username' => 'Username',
            'accessToken' => 'Access Token',
            'authKey' => 'Auth Key',
            'tipo' => 'Tipologia',
        ];
    }
    public function signup()
    {
        $user = new User();
        $user->nome = $this->nome;
        $user->cognome = $this->cognome;
        $user->ntelefono = $this->ntelefono;
        $user->descrizione = $this->descrizione;
        $user->email = $this->email;
        $user->pass = \Yii::$app->security->generatePasswordHash($this->pass);  
        $user->authKey = \Yii::$app->security->generateRandomString();;
        $user->accessToken = \Yii::$app->security->generateRandomString();
        $user->tipo = $this->tipo;

        if($user->save())
        {
            return true;            
        }
        
        \Yii::error(message: "Utente non salvato.". VarDumper::dumpAsString($user->errors));
        return false;
        


    }
}





?>