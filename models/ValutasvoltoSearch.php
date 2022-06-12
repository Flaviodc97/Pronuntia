<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Valutasvolto;

/**
 * ValutasvoltoSearch represents the model behind the search form of `app\models\Valutasvolto`.
 */
class ValutasvoltoSearch extends Valutasvolto
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID', 'IdLogoP', 'IdSvolto', 'valutazione'], 'integer'],
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
        $query = Valutasvolto::find()->where(['=','IdLogoP', $id ]);

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
            'IdLogoP' => $this->IdLogoP,
            'IdSvolto' => $this->IdSvolto,
            'valutazione' => $this->valutazione,
        ]);

        return $dataProvider;
    }
}
