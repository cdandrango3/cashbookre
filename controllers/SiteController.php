<?php

namespace app\controllers;

use app\models\Forgotpass;
use app\models\Users;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\ChartAccounts;
use yii\web\User;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    public function actionPerfil(){
        $user=New Users();
        if ($user->load(Yii::$app->request->post())) {
           $passv= Yii::$app->getSecurity()->validatePassword($user->passanterior,
                Yii::$app->user->identity->password);
            if($passv){
                if($user->password != $user->passrea){
                    Yii::debug("aqui estoy");
                    Yii::$app->session->addFlash("error", "Las contraseñas no coincide");
                    $url = $_SERVER['HTTP_REFERER'];
                    return $this->redirect($url);
                }
                $us=Users::findOne(["username"=>Yii::$app->user->identity->username]);
                $us->updateAttributes(['password' => Yii::$app->getSecurity()->generatePasswordHash($user->password)]);
                $us->updateAttributes(['active' => True]);
                $this->redirect("/web");

            }
            else{
                Yii::$app->session->addFlash("error", "Escribio mal su contraseña anterior vuelva a intentarlo");

            }

        }
        else{
            yii::debug($user->errors);
        }
        return $this->render('perfil',['user' => $user ]);
    }
    public function actionUpdate()
    {
        $list = \app\models\ChartAccounts::find()->all();
        foreach ($list as $model) {
            $m2 = Yii::$app->db2->createCommand("SELECT * FROM chart_accounts where id=" . $model->id)->queryOne();
            if ($m2) {


                $model->type_account = $m2['type_account'];
                $model->save();
            }
        }
    }
    public function actionOrdena()
    {
        $list = ChartAccounts::find()
            ->where(['institution_id' => 1])
            ->orderBy('length(code),code')
            ->all();

        foreach ($list as $model) {
            //buscar padre, no tiene ok, tiene poner parent_id
            if (strpos($model->code, '.') !== false) {
                $codes = explode('.', $model->code);
                array_pop($codes);
                $code = implode('.', $codes);
                $key = array_search($code, array_column($list, 'code'));
                $model->parent_id = $list[$key]->id;
                if (strlen($model->type_account) == 0) $model->type_account = '';
                $model->save();
            }
        }
    }
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
		 if (!Yii::$app->user->isGuest) {
          return $this->render('index');
        }
        $this->layout = 'blank';
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $user=Users::findOne(["username"=>$model->username]);
            yii::debug($user->active);
            if($user->active==True){

                return $this->goBack();
            }
            else{
                yii::debug($user->active);
                return $this->redirect("/web/site/validate");
            }

        }
        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
        //return $this->render('/site/login');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionFormchange($token){
        $model=New Users();
        $this->layout = 'blank';
        $validate=Users::findByPasswordResetToken($token);
        if (is_null($validate)){
            throw new NotFoundHttpException('El token ha expirado');
        }
        if($model->load(Yii::$app->request->post())){
            if($model->password != $model->passrea){
                Yii::debug("aqui estoy");
                Yii::$app->session->addFlash("error", "Las contraseñas no coinciden");
                $url = $_SERVER['HTTP_REFERER'];
                return $this->redirect($url);
            }
            $validate=$model::findByPasswordResetToken($token);
            $validate->updateAttributes(['password' => Yii::$app->getSecurity()->generatePasswordHash($model->password)]);
            $validate->updateAttributes(['active' => True]);
            Yii::$app->session->setFlash("complete", "Su contraseña ha sido cambiada satisfactoriamente");
            $this->redirect("/web");
        }
        return $this->render('formchange',["user"=>$model]);

     }
    public function actionChangepassword(){
        $model=new Forgotpass();
        $this->layout = 'blank';
        if($model->load(Yii::$app->request->post()) && $model->validate()){
            $c=Users::find()->where(["username"=>$model->user])->orFilterWhere(["email"=>$model->user])->exists();
            if ($c){
                $em=Users::find()->where(["username"=>$model->user])->orFilterWhere(["email"=>$model->user])->one();
                $em->updateAttributes(['remember_token'=>Users::generatePasswordResetToken()]);
                Yii::$app->mailer->compose()
                    ->setFrom('cdandrango@gmail.com')
                    ->setTo($em->email)
                    ->setSubject('Recuperar contraseña')
                    ->setHtmlBody('
                      <p>Estimado usuario'. $em->username.  ' En la parte de abajo va a tener el link para resetear su contraseña </p>
                      <p>Link</p> <a href="http://tgcashpruebas.herokuapp.com/web/site/formchange?token='.$em->remember_token.'">http://tgcashpruebas.herokuapp.com/web/</a>
                      
                      <h1>Gracias por utilizar tg cashbook</h1>
                   ')
                    ->send();

                Yii::$app->session->setFlash("success", "Se ha enviado un correo electronico con sus credenciales");
            }


            else{
                Yii::$app->session->setFlash("error", "No se encuentra el usuario registrado");

            }
        }
        return $this->render('changepassword', ['model' => $model]);
    }
    public function actionLogin()
    {

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $this->layout = 'blank';
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionValidate(){
        $user=New Users();
        $this->layout = 'blank';
        if ($user->load(Yii::$app->request->post())) {
            if($user->password != $user->passrea){

                Yii::$app->session->addFlash("error", "Las contraseñas no coinciden");
                $url = $_SERVER['HTTP_REFERER'];
                return $this->redirect($url);
            }
            $us=Users::findOne(["username"=>Yii::$app->user->identity->username]);
            $us->updateAttributes(['password' => Yii::$app->getSecurity()->generatePasswordHash($user->password)]);
            $us->updateAttributes(['active' => True]);
            $this->redirect("/web");
        }
        return $this->render("validate",["user"=>$user]);
    }
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}

