<?php

namespace app\controllers;

use app\models\Institution;
use Yii;
use app\models\Person;
use app\models\PersonSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Clients;
use app\models\Providers;
use app\models\Shareholder;
use app\models\Employee;
use app\models\Salesman;
use app\models\PersonBankInfo;
use yii\helpers\ArrayHelper;
use app\models\City;

/**
 * PersonController implements the CRUD actions for Person model.
 */
class PersonController extends Controller
{

    public function actionCities()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $cat_id = $parents[0];
                $cities = City::find()->where(['province_id' => $cat_id])->all();
                $out = [];
                foreach ($cities as $city) {
                    $t = ['id' => $city->id, 'name' => $city->cityname];
                    $out[] = $t;
                }
                return ['output' => $out, 'selected' => ''];
            }
        }
        return ['output' => '', 'selected' => ''];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulkdelete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Person models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PersonSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $searchModel->institution_id = 1;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Person model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Person #" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Person model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $request = Yii::$app->request;
        $model = new Person();
        $institutions=Institution::findOne(['users_id'=>Yii::$app->user->identity->id]);
        $model->institution_id = $institutions->id;
        $client = new Clients;
        $provider = new Providers;
        $employee = new Employee;
        $salesman = new Salesman;
        $shareholder = new Shareholder;
        $personbank = new PersonBankInfo;
        $check = [];

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Crear nueva Persona",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'client' => $client,
                        'employee' => $employee,
                        'salesman' => $salesman,
                        'shareholder' => $shareholder,
                        'provider' => $provider,
                        'personbank' => $personbank,
                        'check' => $check,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else {
                //load & validate data
                $result = [];

                $result['person load'] = $model->load($request->post());
                $result['person validate'] = $model->validate();
                if (isset($_POST['client'])) {
                    $result['client load'] = $client->load($request->post());
                    $client->validate();
                    if (count($client->errors) > 1) $result['client validate'] = false;
                    $check['client'] = true;
                }
                if (isset($_POST['provider'])) {
                    $result['provider load'] = $provider->load($request->post());
                    $provider->validate();
                    if (count($provider->errors) > 1) $result['provider validate'] = false;
                    $check['provider'] = true;
                }
                if (isset($_POST['employee'])) {
                    $result['employee load'] = $employee->load($request->post());
                    $employee->validate();
                    if (count($employee->errors) > 1) $result['employee validate'] = false;
                    $check['employee'] = true;
                }
                if (isset($_POST['salesman'])) {
                    $check['salesman'] = true;
                }
                if (isset($_POST['shareholder'])) {
                    $result['shareholder load'] = $shareholder->load($request->post());
                    $shareholder->validate();
                    if (count($shareholder->errors) > 1) $result['shareholder validate'] = false;
                    $check['shareholder'] = true;
                }
                if ( (isset($check['client']) || isset($check['provider']) || isset($check['employee']))){
                    //bank
                    $result['bank load'] = $personbank->load($request->post());
                    $personbank->validate();
                    if (count($personbank->errors) > 1) $result['personbank validate'] = false;
                    $check['personbank'] = true;
                }
                //all ok
                if (array_search(false, $result)) {
                    return [
                        'title' => "Crear nueeva Persona",
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                            'client' => $client,
                            'employee' => $employee,
                            'salesman' => $salesman,
                            'shareholder' => $shareholder,
                            'provider' => $provider,
                            'personbank' => $personbank,
                            'check' => $check,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                    ];
                } else {
                    $model->save();
                    if (isset($check['client'])) {
                        $client->person_id = $model->id;
                        $client->save();
                    }
                    if (isset($check['employee'])) {
                        $employee->person_id = $model->id;
                        $employee->save();
                    }
                    if (isset($check['salesman'])) {
                        $salesman->person_id = $model->id;
                        $salesman->save();
                    }
                    if (isset($check['shareholder'])) {
                        $shareholder->person_id = $model->id;
                        $shareholder->save();
                    }
                    if (isset($check['provider'])) {
                        $provider->person_id = $model->id;
                        $provider->save();
                    }

                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Crear nueva Persona",
                        'content' => '<span class="text-success">Persona Creada satisfactoriamente</span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Crear mas Personas', ['create', 'id' => $model->institution_id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                    ];
                }
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['index', 'id' => $id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'client' => $client,
                    'employee' => $employee,
                    'salesman' => $salesman,
                    'shareholder' => $shareholder,
                    'provider' => $provider,
                    'personbank' => $personbank,
                    'check' => $check,
                ]);
            }
        }
    }

    /**
     * Updates an existing Person model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $check = [];
        if ($model->clients) {
            $client = $model->clients[0];
            $check['client'] = true;
        } else $client =  new Clients;
        if ($model->providers) {
            $provider = $model->providers[0];
            $check['provider'] = true;
        } else $provider =  new Providers;
        if ($model->salesmen) {
            $salesman = $model->salesmen[0];
            $check['salesman'] = true;
        } else $salesman = new Salesman;
        if ($model->employees) {
            $employee = $model->employees[0];
            $check['employee'] = true;
        } else  $employee = new Employee;
        if ($model->shareholders) {
            $shareholder = $model->shareholders[0];
            $check['shareholder'] = true;
        } else $shareholder =  new Shareholder;
        if ($model->personBankInfos) {
            $personbank = $model->personBankInfos[0];
            $check['personbank'] = true;
        } else $personbank =  new PersonBankInfo;


        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Modeificar Persona",
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'client' => $client,
                        'employee' => $employee,
                        'salesman' => $salesman,
                        'shareholder' => $shareholder,
                        'provider' => $provider,
                        'personbank' => $personbank,
                        'check' => $check,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else {
                //load & validate data
                $result = [];

                $result['person load'] = $model->load($request->post());
                $result['person validate'] = $model->validate();
                if (isset($_POST['client'])) {
                    $result['client load'] = $client->load($request->post());
                    $client->validate();
                    if ((count($client->errors) > 1) || ((count($client->errors) == 1) && (!$client->isNewRecord))) $result['client validate'] = false;
                    $check['client'] = true;
                }
                if (isset($_POST['provider'])) {
                    $result['provider load'] = $provider->load($request->post());
                    $provider->validate();
                    if ((count($provider->errors) > 1) || ((count($provider->errors) == 1) && (!$provider->isNewRecord))) $result['provider validate'] = false;
                    $check['provider'] = true;
                }
                if (isset($_POST['employee'])) {
                    $result['employee load'] = $employee->load($request->post());
                    $employee->validate();
                    if ((count($employee->errors) > 1) || ((count($employee->errors) == 1) && (!$employee->isNewRecord))) $result['employee validate'] = false;
                    $check['employee'] = true;
                }
                if (isset($_POST['salesman'])) {
                    $check['salesman'] = true;
                }
                if (isset($_POST['shareholder'])) {
                    $result['shareholder load'] = $shareholder->load($request->post());
                    $shareholder->validate();
                    if ((count($shareholder->errors) > 1) || ((count($shareholder->errors) == 1) && (!$shareholder->isNewRecord))) $result['shareholder validate'] = false;
                    $check['shareholder'] = true;
                }
                if ( (isset($check['client']) || isset($check['provider']) || isset($check['employee']))){
                    //bank
                    $result['bank load'] = $personbank->load($request->post());
                    $personbank->validate();
                    if ((count($personbank->errors) > 1) || ((count($personbank->errors) == 1) && (!$personbank->isNewRecord))) $result['personbank validate'] = false;
                    $check['personbank'] = true;
                }
                //all ok
                if (array_search(false, $result)) {
                    return [
                        'title' => "Modificar Persona",
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                            'client' => $client,
                            'employee' => $employee,
                            'salesman' => $salesman,
                            'shareholder' => $shareholder,
                            'provider' => $provider,
                            'personbank' => $personbank,
                            'check' => $check,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                    ];
                } else {
                    $model->save();
                    if (isset($check['client'])) {
                        $client->person_id = $model->id;
                        $client->save();
                    } else {
                        Clients::deleteAll(['person_id' => $model->id]);
                    }
                    if (isset($check['employee'])) {
                        $employee->person_id = $model->id;
                        $employee->save();
                    } else {
                        Employee::deleteAll(['person_id' => $model->id]);
                    }
                    if (isset($check['salesman'])) {
                        $salesman->person_id = $model->id;
                        $salesman->save();
                    } else {
                        Salesman::deleteAll(['person_id' => $model->id]);
                    }
                    if (isset($check['shareholder'])) {
                        $shareholder->person_id = $model->id;
                        $shareholder->save();
                    } else {
                        Shareholder::deleteAll(['person_id' => $model->id]);
                    }
                    if (isset($check['provider'])) {
                        $provider->person_id = $model->id;
                        $provider->save();
                    } else {
                        Providers::deleteAll(['person_id' => $model->id]);
                    }
                    if (isset($check['personbank'])) {
                        $personbank->person_id = $model->id;
                        $personbank->save();
                    } else {
                        PersonBankInfo::deleteAll(['person_id' => $model->id]);
                    }
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Crear nueva Persona",
                        'content' => '<span class="text-success">Persona Creada satisfactoriamente</span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Crear mas Personas', ['create', 'id' => $model->institution_id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                    ];
                }
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                    'client' => $client,
                    'employee' => $employee,
                    'salesman' => $salesman,
                    'shareholder' => $shareholder,
                    'provider' => $provider,
                    'personbank' => $personbank,
                    'check' => $check,
                ]);
            }
        }
    }

    /**
     * Delete an existing Person model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing Person model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkdelete()
    {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
        foreach ($pks as $pk) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Person model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Person the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Person::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
