<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "assegnaesercizio".
 *
 * @property int $ID
 * @property int $IdEsercizio
 * @property int $IdLogoP
 * @property int $IdPaziente
 *
 * @property Esercizio $idEsercizio
 * @property User $idLogoP
 * @property Paziente $idPaziente
 */
class Assegnaesercizio extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'assegnaesercizio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdEsercizio','IdPaziente'], 'required'],
            [['IdEsercizio', 'IdLogoP', 'IdPaziente'], 'integer'],
            [['IdEsercizio'], 'exist', 'skipOnError' => true, 'targetClass' => Esercizio::className(), 'targetAttribute' => ['IdEsercizio' => 'IdEsercizio']],
            [['IdLogoP'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['IdLogoP' => 'IdUser']],
            [['IdPaziente'], 'exist', 'skipOnError' => true, 'targetClass' => Paziente::className(), 'targetAttribute' => ['IdPaziente' => 'IdPaziente']],
        ];
    }
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'IdLogoP',
                'updatedByAttribute' => false,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'IdEsercizio' => 'Esercizio',
            'IdLogoP' => 'Id Logo P',
            'IdPaziente' => 'Paziente',
        ];
    }

    public static function findMyesercizio()
    {
        $user = Yii::$app->user->identity;
        $id = $user->getId();
        $i = 0;
        $rows = (new \yii\db\Query())
        ->select(['IdPaziente'])
        ->from('paziente')
        ->where(['IdCaregiver' => $id])
        ->all();
        foreach($rows as $result) {
            $idp[$i] = $result['IdPaziente'];
            $i++;
        }
        
        $i = 0;
        $rows = (new \yii\db\Query())
        ->select(['IdEsercizio'])
        ->from('assegnaesercizio')
        ->where(['IdPaziente' => $idp])
        ->all();
        foreach($rows as $result) {
            $ide[$i] = $result['IdEsercizio'];
            $i++;
        }
       
   
        
        return Assegnaesercizio::find()->where(['IdEsercizio' =>$ide]);
    }
    /**
     * Gets query for [[IdEsercizio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdEsercizio()
    {
        return $this->hasOne(Esercizio::className(), ['IdEsercizio' => 'IdEsercizio']);
    }

    /**
     * Gets query for [[IdLogoP]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdLogoP()
    {
        return $this->hasOne(User::className(), ['IdUser' => 'IdLogoP']);
    }

    /**
     * Gets query for [[IdPaziente]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdPaziente()
    {
        return $this->hasOne(Paziente::className(), ['IdPaziente' => 'IdPaziente']);
    }
}
