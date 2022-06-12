<?php

namespace app\models;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Svolgeesercizio;

/**
 * SvolgeesercizioSearch represents the model behind the search form of `app\models\Svolgeesercizio`.
 */
class SvolgeesercizioSearch extends Svolgeesercizio
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID', 'IdPaziete', 'IdEsercizio'], 'integer'],
            [['audio'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {   
        $user = Yii::$app->user->identity;
        $id = $user->getId();
        $somma = 0;
        $rows = (new \yii\db\Query())
        ->select(['IdPaziente'])
        ->from('Paziente')
        ->where(['IdCaregiver' => $id])
        ->all();
        foreach($rows as $result) {
            $idpaz[$somma] = $result['IdPaziente'];
            $somma ++;
        }
        $query = Svolgeesercizio::find()->where(['IdPaziete'=>$idpaz]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'ID' => $this->ID,
            'IdPaziete' => $this->IdPaziete,
            'IdEsercizio' => $this->IdEsercizio,
        ]);

        $query->andFilterWhere(['like', 'audio', $this->audio]);

        return $dataProvider;
    }
}
