<?php

namespace app\models;
use app\models\Person;
use app\models\Users;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\web\User;
use app\models\LoginForm;
use yii\web\Controller;
use yii\db\Query;
/**
 * PersonSearch represents the model behind the search form about `app\models\Person`.
 */
class PersonSearch extends Person
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'person_type_id', 'categories_id', 'associated_person', 'institution_id', 'city_id'], 'integer'],
            [['special_taxpayer', 'foreigner', 'status'], 'boolean'],
            [['rol', 'ruc', 'cedula', 'name', 'commercial_name', 'phones', 'address', 'emails', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
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

        $query1 = new Query;
        $users = array();
        $usersstr = "";
        $us=Users::findOne(["username"=>Yii::$app->user->identity->username]);

        $usuarios = $query1->select(['institution_id'])->from('users, person')->Where('person.id = person_id')->all();
        if ($usuarios) {
            foreach ($usuarios as $row)
            {
                $users = $row['institution_id'];
                $usersstr = $users.",".$usersstr;
            }
        }
        $modulos = explode(",", $usersstr);
        yii::debug($modulos);
        $institutions=Institution::findOne(['users_id'=>Yii::$app->user->identity->id]);
        $query = Person::find()->andFilterWhere(['institution_id'=>$institutions->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if ($this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->alias('t');
        //file_put_contents('c:/temp/php.log',json_encode($this->rol)."\n\r",FILE_APPEND);
        if ($this->rol) {
            if (array_search('provider',$this->rol)!==false) {
                $query->innerJoin('providers', 'providers.person_id=t.id');
            }
            if (array_search('employee',$this->rol)!==false) {
                file_put_contents('c:/temp/php.log','employee'."\n\r",FILE_APPEND);
                $query->innerJoin('employee', 'employee.person_id=t.id');
            }
            if (array_search('shareholder',$this->rol)!==false) {
                $query->innerJoin('shareholder', 'shareholder.person_id=t.id');
            }
            if (array_search('client',$this->rol)!==false) {
                $query->innerJoin('clients', 'clients.person_id=t.id');
            }
            if (array_search('salesman',$this->rol)!==false) {
                $query->innerJoin('salesman', 'salesman.person_id=t.id');
            }
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'person_type_id' => $this->person_type_id,
            'special_taxpayer' => $this->special_taxpayer,
            'foreigner' => $this->foreigner,
            'categories_id' => $this->categories_id,
            'associated_person' => $this->associated_person,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'institution_id' => $this->institution_id,
            'city_id' => $this->city_id,
        ]);

        $query->andFilterWhere(['like', 'ruc', $this->ruc])
            ->andFilterWhere(['like', 'cedula', $this->cedula])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'commercial_name', $this->commercial_name])
            ->andFilterWhere(['like', 'phones', $this->phones])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'emails', $this->emails]);
        return $dataProvider;
    }
}