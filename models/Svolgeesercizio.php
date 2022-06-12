<?php

namespace app\models;

use Yii;
use yii\helpers\FileHelper;
use yii\behaviors\BlameableBehavior;
use app\models\ValutaEsercizio;
use app\models\Valutasvolto;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "svolgeesercizio".
 *
 * @property int $ID
 * @property int $IdPaziete
 * @property int $IdEsercizio
 * @property string $audio
 *
 * @property Valutasvolto[] $valutasvoltos
 */
class Svolgeesercizio extends \yii\db\ActiveRecord
{

    public $audioFile;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'svolgeesercizio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdPaziete', 'IdEsercizio', 'audio'], 'required'],
            [['IdPaziete', 'IdEsercizio'], 'integer'],
            [['audio'], 'file', 'maxSize' => 1024 * 1024 * 1024],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'IdPaziete' => 'Paziente',
            'IdEsercizio' => 'Esercizio',
            'audio' => 'Audio',
            'audioFile' => 'Audio Esercizio',
        ];
    }
    public function save($runValidation = true, $attributeNames = null)
    {
        
        if ($this->audioFile) {
            $this->audio = '/audio/' . Yii::$app->security->generateRandomString() . '/' . $this->audioFile->name;
        }

        $transaction = Yii::$app->db->beginTransaction();
        $ok = parent::save($runValidation, $attributeNames);

        if ($ok && $this->audioFile) {
            $fullPath = Yii::getAlias('@app/web/uploads' . $this->audio);
            $dir = dirname($fullPath);
            if (!FileHelper::createDirectory($dir) | !$this->audioFile->saveAs($fullPath)) {
                $transaction->rollBack();

                return false;
            }
        }

        $transaction->commit();

        return $ok;
    }

    public function getAudioUrl()
    {
        return self::formatAudioUrl($this->audio);
    }

    public static function formatAudioUrl($audioPath)
    {
        if ($audioPath) {
            return Yii::$app->params['pronuntia'] . '/uploads' . $Path;
        }

    }
    public function afterDelete()
    {
        parent::afterDelete();
        if ($this->audio) {
            $dir = Yii::getAlias('@app/web/uploads'). dirname($this->audio);
            FileHelper::removeDirectory($dir);
        }
    }
    
    public function getValutazione()
    {
        $id = $this ->ID;
        $somma = 0;
        $count = 0;
        $rows = (new \yii\db\Query())
        ->select(['valutazione'])
        ->from('valutasvolto')
        ->where(['IdSvolto' => $id])
        ->all();
        foreach($rows as $result) {
            $somma= $somma + $result['valutazione'];
            $count ++;
        }
        if($count==0)
        {
            return 'Nessuna valutazione';
        }else{
            return $somma/$count;
        }
      
    }
    /**
     * Gets query for [[Valutasvoltos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getValutasvoltos()
    {
        return $this->hasMany(Valutasvolto::className(), ['IdSvolto' => 'ID']);
    }
}
