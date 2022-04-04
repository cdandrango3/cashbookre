<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use app\models\ChartAccounts;

/**
 * ChartAccountsSearch represents the model behind the search form about `app\models\ChartAccounts`.
 */
class ChartAccountsSearch extends ChartAccounts
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'institution_id', 'bigparent_id', 'parent_id'], 'integer'],
            [['code', 'slug', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['status'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
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
    $id_ins=Institution::findOne(['users_id'=>Yii::$app->user->identity->id]);
    Yii::debug($id_ins->id);
        $query = $this::find()->select(["*,
        (select count(*) as childs from chart_accounts as t6 where t6.parent_id = t.id)
        ,
        (
            (
                SELECT  
                 (sum(debit)-sum(credit)) as balance
              FROM
                public.accounting_seats_details as t5
                INNER JOIN public.accounting_seats as t4 ON (t5.accounting_seat_id = t4.id)
                
                INNER JOIN public.chart_accounts as t3 ON (t5.chart_account_id = t3.id)
              WHERE
                t4.institution_id = ".$id_ins->id." AND  
                t3.institution_id = ".$id_ins->id." AND  
                substring(t3.code, 1, char_length((t.code))) = (t.code)           
                )
        )"])->alias('t');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 999999,
            ],
            'sort' => [
                'defaultOrder' => ['code' => SORT_ASC, 'parent_id' => SORT_ASC]
            ],


        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'institution_id' => $id_ins->id,
            'bigparent_id' => $this->bigparent_id,
            'parent_id' => $this->parent_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'slug', $this->slug]);

        return $dataProvider;
    }

    public function searchbigbook($params)
    {

        $id_ins=Institution::findOne(['users_id'=>Yii::$app->user->identity->id]);

        $this->account = isset($params['AccountingSeats']['account']) ? $params['AccountingSeats']['account'] : $this->account;
        $this->cost_center = isset($params['AccountingSeats']['cost_center']) ? $params['AccountingSeats']['cost_center'] : null;
        $this->datefrom = isset($params['AccountingSeats']['datefrom']) ? $params['AccountingSeats']['datefrom'] : date('Y-m-d', strtotime('first day of this month'));
        $this->dateto = isset($params['AccountingSeats']['dateto']) ? $params['AccountingSeats']['dateto'] : date('Y-m-d');
        if ($this->datefrom > $this->dateto) {
            $t = $this->datefrom;
            $this->datefrom = $this->dateto;
            $this->dateto = $t;
        }
        $tmodel = $this::find()->where(["id"=>$this->account])->andwhere(["institution_id"=>$id_ins->id])->one();
        $accounts = ChartAccounts::find()->select(["*,
        (select count(*) as childs from chart_accounts as t6 where t6.parent_id = t.id  )
        ,
        (
            (
                SELECT  
                 (sum(debit)-sum(credit)) as balance
              FROM
                public.accounting_seats_details as t5
                INNER JOIN public.accounting_seats as t4 ON (t5.accounting_seat_id = t4.id)
                INNER JOIN public.chart_accounts as t3 ON (t5.chart_account_id = t3.id)
              WHERE
                t4.date < '$this->datefrom' and 
                t4.institution_id = ".$id_ins->id."AND 
                t3.institution_id = ".$id_ins->id."AND 
                substring(t3.code, 1, char_length(t.code)) = t.code           
                )
        )"])
            ->andWhere("substring(t.code, 1, char_length('$tmodel->code')) = '$tmodel->code'")
            ->andWhere(['institution_id' => $id_ins->id])
            ->alias('t')
            ->orderBy(['code' => SORT_ASC, 'parent_id' => SORT_ASC])
            ->all();
        //
        $data = [];
        $lastaccount = null;
        foreach ($accounts as $account) {
            
            /*
            SELECT 
                t2.date,
                t2.description,
                t.debit,
                t.credit
            FROM
                public.accounting_seats_details t
                INNER JOIN public.accounting_seats t2 ON (t.accounting_seat_id = t2.id)
            */
            $details = AccountingSeatsDetails::find()->select('
            t2.date,
            t2.description,
            t.debit,
            t.credit') 
            ->alias('t')
            ->innerJoin('accounting_seats t2','(t.accounting_seat_id = t2.id)')
            ->where(['institution_id'=>$id_ins->id])
            ->andWhere(['between','date',$this->datefrom,$this->dateto])
            ->andWhere(['chart_account_id'=>$account->id])
            ->andFilterWhere(['cost_center_id'=>$this->cost_center])
            ->orderBy('date ASC')
            ->asArray()
            ->all();
            $balance=0;
            if ((($account->id!=$lastaccount) && ($details) ) || (count($data)==0)) {
                $temp['date'] = $account->code;
                $temp['description'] = $account->slug;
                $temp['childs'] = $account->childs;
                $temp['balance'] = $account->balance;
                $temp['debit'] = 'SALDO ANTERIOR';
                $temp['credit'] = '';
                $balance = $account->balance;
                $data[] = $temp;
            }
           
            foreach ($details as $detail){
                $temp['date'] = $detail['date'];
                $temp['description'] = $detail['description'];
                $temp['debit'] = $detail['debit'];
                $temp['credit'] = $detail['credit'];
                $balance += $detail['debit'];
                $balance -= $detail['credit'];
                $temp['balance'] = $balance;
                $data[] = $temp;
            }
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $dataProvider;
    }
}
