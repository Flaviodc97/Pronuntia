<?php

namespace app\models;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Valutaesercizio;
use app\models\User;
use app\models\Esercizio;


/**
 * ValutaesercizioSearch represents the model behind the search form of `app\models\Valutaesercizio`.
 */
class ValutaesercizioSearch extends Valutaesercizio
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdCaregiver', 'IdEsercizio', 'valutazione'], 'integer'],
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
        $query = Valutaesercizio::find()->where(['=','IdCaregiver', $id ]);
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
            'IdCaregiver' => $this->IdCaregiver,
            'IdEsercizio' => $this->IdEsercizio,
            'valutazione' => $this->valutazione,
            
        ]);

        return $dataProvider;
    }
}
