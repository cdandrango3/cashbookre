<?php

namespace app\controllers;
use app\models\AccountingSeats;
use app\models\AccountingSeatsDetails;
use app\models\Charges;
use app\models\ChartAccounts;
use app\models\Clients;
use app\models\FacturaBody;
use app\models\Facturafin;
use app\models\Institution;
use app\models\Person;
use app\models\HeadFact;
use app\models\Product;
use app\models\ProductType;
use app\models\Providers;
use app\models\Retention;
use app\models\Retentiondetail;
use app\models\Salesman;
use Cassandra\Date;
use kartik\mpdf\Pdf;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Json;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\web\Controller;
use yii\db\Query;
use yii\web\NotFoundHttpException;

class ClienteController extends controller
{
    public $id;
    public $id_product;
    public $id_ins=0;
public function actionIndex($tipos){
    $models=New clients;
    $modelhead=New HeadFact;
    $modelf=New Facturafin;
    $_SESSION['id_ins'] = Institution::findOne(['users_id'=>Yii::$app->user->identity->id]);
    if($tipos=="Cliente"){

        $query1 = HeadFact::find()->innerJoin("person","head_fact.id_personas=person.id")->where(["head_fact.tipo_de_documento"=>"Cliente"])->andWhere(["person.institution_id"=>
            $_SESSION['id_ins']->id]);
    }
    else{
        if ($tipos=="Proveedor") {
            $query1 = HeadFact::find()->innerJoin("person","head_fact.id_personas=person.id")->where(["head_fact.tipo_de_documento"=>"Proveedor"])->andWhere(["person.institution_id"=>
                $_SESSION['id_ins']->id]);;
        }
        else{
            $query1 = HeadFact::find()->innerJoin("person","head_fact.id_personas=person.id")->andWhere(["person.institution_id"=>
                $_SESSION['id_ins']->id]);
        }

    }
    $pages = new Pagination(['defaultPageSize' => 5,'totalCount' => $query1->count()]);
    $modelhe = $query1->offset($pages->offset)
        ->limit($pages->limit)
        ->all();
    Yii::debug($modelhe);
    $query = $models::find();
    $dataProvider = new ActiveDataProvider([
        'query' => $query,
    ]);

    return $this->render('index',["models"=>$dataProvider,"modelhead"=>$modelhead,"pages"=>$pages,"headfac"=>$modelhe]);
}
    public function actionViewf($id){
    $modelhead=New HeadFact;
    $modelbody=New FacturaBody;
    $modelfin=New Facturafin;
        $persona=New Person;


    $id=$_GET["id"];
    $model1=$modelhead::findOne(["n_documentos"=>$id]);
    $model2=$modelbody::find()->where(["id_head"=>$id])->all();
    $model3=$modelfin::findOne(["id_head"=>$id]);
    $persona=$persona::findOne(["id"=>$model1->id_personas]);
        $salesman=$persona::findOne(["id"=>$model1->id_saleman]);


        return $this->render("viewf",["salesman"=>$salesman,"model"=>$model1,"model2"=>$model2,"modelfin"=>$model3,"personam"=>$persona]);
    }
    public function actionFactura()
    {
        $model = new HeadFact;
        $person = new Person;
        $client=New Clients;
        $retention = new Retentiondetail;
        $institucion=New Institution;
        $model_tip=New ProductType;
        $salesman = new Salesman;
        $model2 = new FacturaBody;
        $productos = new Product;
        $facturafin = new Facturafin;
        $accounting_seats=new AccountingSeats;
        $accounting_seats_details=New AccountingSeatsDetails;
        $persona = $person::find()->select("name")->innerJoin("clients","person.id=clients.person_id")->where(["institution_id"=>
            $_SESSION['id_ins']->id])->all();
        $model_tipo=$model_tip::find()->select("name")->all();
        $pro = $productos::find()->select("name")->where(['institution_id'=>$_SESSION['id_ins']->id])->all();
        $precio = $productos::find()->where(['institution_id'=>$_SESSION['id_ins']->id])->all();
        $precioser = $productos::find()->where(['product_type_id'=>2])->all();
        $d= Yii::$app->request->post('Facturafin');
        $per= Yii::$app->request->post('Person');

        $retimp=Retention::find()->select(["(concat(retention.codesri,'._',retention.slug))",'retention.id'])->where(["type"=>1])->asArray()->all();
        $retiva=Retention::find()->select(["(concat(retention.codesri,'._',retention.slug))",'retention.id'])->where(["type"=>2])->asArray()->all();

        $query = $person::find()->innerJoin("clients","person.id=clients.person_id")->where(["person.institution_id"=>$_SESSION['id_ins']->id])->all();
        $providers = $person::find()->innerJoin("providers","person.id=providers.person_id")->where(["person.institution_id"=>$_SESSION['id_ins']->id])->all();
        $salesman=$person::find()->innerJoin("salesman","person.id=salesman.person_id")->where(["person.institution_id"=>$_SESSION['id_ins']->id])->all();
        if ($model->load(Yii::$app->request->post())) {
            $model->id_personas=$per["id"];
            $model->id_saleman=$per["id_ven"];
            yii::debug($per["id_ven"]);
            if ($model->validate())
                $model->id_personas=$per["id"];
                $model->save();
                $c = rand(1, 100090000);
                $this->id=$c;
                $facturafin->id = $c;
                $facturafin->subtotal12 = $d["subtotal12"]?:0;
                $facturafin->subtotal0 = $d["subtotal0"]?:0;
                $facturafin->total = $d["total"];
                $facturafin->iva = $d["iva"];
                $facturafin->description = $d["description"];
                $facturafin->descuento = $d["descuento"];
                $facturafin->id_head = $model->n_documentos;
                $facturafin->save();

                if($model->save()){
                    $tipo=$model->tipo_de_documento;
                    if($tipo=="Cliente") {
                        //* Aqui inicia si es una compra//
                        $ch1 = $client::findOne(['person_id' => $model->id_personas]);
                        $accou_c = $ch1->chart_account_id;
                        $ins = $person::findOne(['id' => $model->id_personas]);
                        $ins = $person::findOne(['id' => $model->id_personas]);

                        $descripcion = $facturafin->description;
                        $nodeductible = False;
                        $status = True;
                        $h = rand(1, 10000000);
                        $accounting_seats->id = $h;
                        $accounting_seats->head_fact = $model->n_documentos;
                        $accounting_seats->institution_id = $_SESSION['id_ins']->id;
                        $accounting_seats->description = $descripcion;
                        $accounting_seats->nodeductible = $nodeductible;
                        $accounting_seats->status = $status;
                        $accounting_seats->type= "ingresos";
                        if ($accounting_seats->save()) {
                            $debea = $accou_c;
                            $bodyf=FacturaBody::find()->where(['id_head'=>$model->n_documentos])->all();
                            $haber=array();
                            $suma=array();
                            $sum=0;
                            foreach ($bodyf as $bod){
                                $cos=Product::findOne(["id"=>$bod->id_producto]);
                                $sum=$sum+($bod->precio_total);
                                if (!(is_null($cos->charingresos))) {
                                    $haber[] = $cos->charingresos;
                                    $suma[] = $bod->precio_total;
                                }

                            }


                            Yii::debug(count($haber));
                            if(count($haber) !=0 ){
                                $debea=$accou_c;
                                $haber[]=13273;
                                $i=count($haber);
                                $count=0;

                                $accounting_seats_details = new AccountingSeatsDetails;
                                $accounting_seats_details->accounting_seat_id = $accounting_seats->id;
                                $accounting_seats_details->chart_account_id = $debea;
                                $accounting_seats_details->debit = $facturafin->total;
                                $accounting_seats_details->credit = 0;
                                $accounting_seats_details->cost_center_id = 1;
                                $accounting_seats_details->status = true;
                                $accounting_seats_details->save();
                                foreach ($haber as $habe) {

                                    if($count<$i-1){
                                        $accounting_seats_details = new AccountingSeatsDetails;
                                        $accounting_seats_details->accounting_seat_id = $accounting_seats->id;
                                        $accounting_seats_details->chart_account_id = $habe;
                                        $accounting_seats_details->debit = 0;
                                        $accounting_seats_details->credit = $suma[$count];
                                        $accounting_seats_details->cost_center_id = 1;
                                        $accounting_seats_details->status = true;
                                        $accounting_seats_details->save();

                                    }
                                    else{
                                        $accounting_seats_details = new AccountingSeatsDetails;
                                        $accounting_seats_details->accounting_seat_id = $accounting_seats->id;
                                        $accounting_seats_details->chart_account_id = $habe;
                                        $accounting_seats_details->debit =0;
                                        $accounting_seats_details->credit = $facturafin->iva;;
                                        $accounting_seats_details->cost_center_id = 1;
                                        $accounting_seats_details->status = true;
                                        $accounting_seats_details->save();
                                    }
                                    $count=$count+1;

                                }
                            }
                                $gr = rand(1, 100090000);
                                //Aqui inicia Inventarios
                            $bodyf=FacturaBody::find()->where(['id_head'=>$model->n_documentos])->all();
                            $sum=0;
                            $debe=array();
                            $haber=array();
                            $suma=array();
                            foreach ($bodyf as $bod){
                                $cos=Product::findOne(["id"=>$bod->id_producto]);
                                Yii::debug($cos);
                                if (!(is_null($cos->Chairinve))) {
                                    $sum=$sum+(($cos->costo)*($bod->cant));
                                    $debe[]=$cos->Chairinve;
                                    $haber[] = $cos->chairaccount_id;
                                    $suma[]=($cos->costo)*($bod->cant);
                                    yii::debug($haber);
                                }


                            }
                            if(count($haber)!=0) {
                                $accounting_sea = new AccountingSeats;
                                $accounting_sea->head_fact = $model->n_documentos;
                                $accounting_sea->id = $gr;
                                $accounting_sea->institution_id =$_SESSION['id_ins']->id;
                                $accounting_sea->description = $descripcion;
                                $accounting_sea->nodeductible = $nodeductible;
                                $accounting_sea->status = $status;
                                $accounting_sea->type= "inventario";
                                if ($accounting_sea->save()) {

                                    $pro = Yii::$app->request->post("Product");
                                    for ($i = 0; $i < count($debe); $i++) {
                                        $accounting_seats_details = new AccountingSeatsDetails;
                                        $accounting_seats_details->accounting_seat_id = $accounting_sea->id;
                                        $accounting_seats_details->chart_account_id = $debe[$i];
                                        yii::debug($debe[$i]);
                                        $accounting_seats_details->debit = $suma[$i];
                                        $accounting_seats_details->credit = 0;
                                        $accounting_seats_details->cost_center_id = 1;
                                        $accounting_seats_details->status = true;
                                        $accounting_seats_details->save();
                                        $accounting_seats_details = new AccountingSeatsDetails;
                                        $accounting_seats_details->accounting_seat_id = $accounting_sea->id;
                                        $accounting_seats_details->chart_account_id = $haber[$i];
                                        $accounting_seats_details->debit = 0;
                                        $accounting_seats_details->credit = $suma[$i];
                                        $accounting_seats_details->cost_center_id = 1;
                                        $accounting_seats_details->status = true;
                                        $accounting_seats_details->save();
                                    }
                                }
                            }



                            return $this->redirect('viewf?id='.$model->n_documentos);
                        }
                        else{

                            $fac=FacturaBody::find()->where(["id_head"=>$model->n_documentos])->exists();
                            if($fac){
                                $this->query("factura_body","id_head",$model->n_documentos);
                            }
                            $facfin=Facturafin::find()->where(["id_head"=>$model->n_documentos])->exists();
                            if($facfin){
                                $this->query("facturafin","id_head",$model->n_documentos);
                            }
                            $fachead=HeadFact::find()->where(["n_documentos"=>$model->n_documentos])->exists();
                            if($fachead){
                                $this->query("head_fact","n_documentos",$model->n_documentos);
                            }

                            foreach($accounting_seats->errors as $key=>$val) {
                                \Yii::$app->getSession()->addFlash('error', $val);

                            }
                            return $this->redirect('factura');
                        }
                    }
                    else{
                        /* Aqui inicia compras */

                        if($tipo=="Proveedor"){
                            $accounting_seats=new AccountingSeats;
                            $h = rand(1, 100000000000);
                            $ch1 = Providers::findOne(['person_id' => $model->id_personas]);
                            $accou_c = $ch1->paid_chart_account_id;
                            $ins = $person::findOne(['id' => $model->id_personas]);
                           $descripcion = $facturafin->description;
                           $nodeductible = False;
                            $status = True;
                            $accounting_seats->head_fact = $model->n_documentos;
                            $accounting_seats->id = $h;
                            $accounting_seats->institution_id = $_SESSION['id_ins']->id;
                            $accounting_seats->description = $descripcion;
                            $accounting_seats->nodeductible = $nodeductible;
                            $accounting_seats->status = $status;
                            $accounting_seats->type = "ingresos";

                            if ($accounting_seats->save()) {
                                $bodyf=FacturaBody::find()->where(['id_head'=>$model->n_documentos])->all();
                                $sum=0;
                                $haber=array();
                                $suma=array();
                                foreach ($bodyf as $bod) {
                                    $cos = Product::findOne(["id" => $bod->id_producto]);
                                    $sum = $sum + ($bod->precio_total);
                                    if ($cos->product_type_id == 1) {
                                        $debea[] = $cos->chairaccount_id;;
                                    } else {
                                        if ($cos->product_type_id == 2){
                                            $debea[] = $cos->chairaccount_id;
                                        }
                                }
                                    $suma[]=$bod->precio_total;
                                }

                                $debea[]= 13161;
                                $habera = $accou_c;
                                $i=count($debea);

                                 $facturafin->iva;
                                $count=0;
                                foreach ($debea as $debe) {

                                    if($count<$i-1){
                                        $accounting_seats_details = new AccountingSeatsDetails;
                                        $accounting_seats_details->accounting_seat_id = $accounting_seats->id;
                                        $accounting_seats_details->chart_account_id = $debe;
                                        $accounting_seats_details->debit = $suma[$count];
                                        $accounting_seats_details->credit = 0;
                                        $accounting_seats_details->cost_center_id = 1;
                                        $accounting_seats_details->status = true;
                                        $accounting_seats_details->save();

                                        yii::debug($suma);
                                    }
                                    else{
                                        $accounting_seats_details = new AccountingSeatsDetails;
                                        $accounting_seats_details->accounting_seat_id = $accounting_seats->id;
                                        $accounting_seats_details->chart_account_id = $debe;
                                        $accounting_seats_details->debit = $facturafin->iva;
                                        $accounting_seats_details->credit = 0;
                                        $accounting_seats_details->cost_center_id = 1;
                                        $accounting_seats_details->status = true;
                                        $accounting_seats_details->save();
                                        yii::debug("aqui");
                                    }
                                    $count=$count+1;

                                }
                                $accounting_seats_details = new AccountingSeatsDetails;
                                $accounting_seats_details->accounting_seat_id = $accounting_seats->id;
                                $accounting_seats_details->chart_account_id = $habera;
                                $accounting_seats_details->debit = 0;
                                $accounting_seats_details->credit = $facturafin->total;
                                $accounting_seats_details->cost_center_id = 1;
                                $accounting_seats_details->status = true;
                                $accounting_seats_details->save();
                                if($_SESSION['codeimp']){
                                    $sum=0;
                                    foreach ($_SESSION['codeimp'] as $key => $value) {
                                          $sum += $value;
                                    }
                                    $accounting_seats = new AccountingSeats;
                                    $h = rand(1, 100000000000);
                                    $ch1 = Providers::findOne(['person_id' => $model->id_personas]);
                                    $accou_c = $ch1->paid_chart_account_id;
                                    $ins = $person::findOne(['id' => $model->id_personas]);
                                    $descripcion = $facturafin->description;
                                    $nodeductible = False;
                                    $status = True;
                                    $accounting_seats->head_fact = $model->n_documentos;
                                    $accounting_seats->id = $h;
                                    $accounting_seats->institution_id = $_SESSION['id_ins']->id;
                                    $accounting_seats->description = $descripcion;
                                    $accounting_seats->nodeductible = $nodeductible;
                                    $accounting_seats->status = $status;
                                    $accounting_seats->type = "retencion";
                                    if($accounting_seats->save()){
                                        $accounting_seats_details = new AccountingSeatsDetails;
                                        $accounting_seats_details->accounting_seat_id = $accounting_seats->id;
                                        $accounting_seats_details->chart_account_id = 13234;
                                        $accounting_seats_details->debit = $sum;
                                        $accounting_seats_details->credit = 0;
                                        $accounting_seats_details->cost_center_id = 1;
                                        $accounting_seats_details->status = true;
                                        $accounting_seats_details->save();
                                        foreach($_SESSION['codeimp'] as $key => $value){
                                            $accounting_seats_details = new AccountingSeatsDetails;
                                            $accounting_seats_details->accounting_seat_id = $accounting_seats->id;
                                            $accounting_seats_details->chart_account_id = $key;
                                            $accounting_seats_details->debit = 0;
                                            $accounting_seats_details->credit = $value;
                                            $accounting_seats_details->cost_center_id = 1;
                                            $accounting_seats_details->status = true;
                                            $accounting_seats_details->save();
                                        }
                                    }
                                }
                                return $this->redirect('viewf?id='.$model->n_documentos);
}
else{
    $fac=FacturaBody::find()->where(["id_head"=>$model->n_documentos])->exists();
    if($fac){

        $this->query("factura_body","id_head",$model->n_documentos);
    }
    $facfin=Facturafin::find()->where(["id_head"=>$model->n_documentos])->exists();
    if($facfin){
        $this->query("facturafin","id_head",$model->n_documentos);
    }
    $fachead=HeadFact::find()->where(["n_documentos"=>$model->n_documentos])->exists();
    if($fachead){
        $this->query("head_fact","n_documentos",$model->n_documentos);
    }

    foreach($accounting_seats->errors as $key=>$val) {
        \Yii::$app->getSession()->addFlash('error', $val);

    }
    return $this->redirect('factura');
}

                        }
                    }
                }
            if (!$model->validate()){
                $fac=FacturaBody::find()->where(["id_head"=>$model->n_documentos])->exists();
                if($fac){
                    $this->query("factura_body","id_head",$model->n_documentos);
                }
                $facfin=Facturafin::find()->where(["id_head"=>$model->n_documentos])->exists();
                if($facfin){
                    $this->query("facturafin","id_head",$model->n_documentos);
                }
                foreach($model->errors as $key=>$val){
                    \Yii::$app->getSession()->addFlash('error', $val);
                }

                $url = $_SERVER['HTTP_REFERER'];
                return $this->redirect($url);



            }
        }












        if ($persona && $salesman) {
            return $this->render('factura', [
               'retention'=>$retention,'retiva' => $retiva, 'retimp' => $retimp, 'salesman' => $salesman, 'model' => $model, "ven" => $persona, "model2" => $model2, "produc" => $pro, "precio" => $precio, "query" => $query, 'model3' => $facturafin, 'modeltype' => $model_tipo, 'produ' => $productos, "providers" => $providers

            ]);
        }
        return $this->renderContent("<h1>No se ha encontrado clientes o vendedores para la facturacion</h1>");
        }
        public function actionMatrixial($id,$ischair){
            $modelhead=New HeadFact;
            $modelbody=New FacturaBody;
            $modelfin=New Facturafin;
            $persona=New Person;


            $id=$_GET["id"];
            $model1=$modelhead::findOne(["n_documentos"=>$id]);
            $model2=$modelbody::find()->where(["id_head"=>$id])->all();
            $model3=$modelfin::findOne(["id_head"=>$id]);
            $persona=$persona::findOne(["id"=>$model1->id_personas]);

            $content = $this->renderPartial('matricial', [
                'model' => $model1,"model2"=>$model2,"modelfin"=>$model3,"personam"=>$persona

            ]);
            $css='
            .fin{
           padding:5px;
            }
            ';
            $pdf = new \kartik\mpdf\Pdf([
                'mode' => \kartik\mpdf\Pdf::MODE_UTF8, // leaner size using standard fonts
                'format' => [210,148],
                'content' => $content,
                'marginTop' => 25,
                'marginBottom' => 10,
                'marginLeft' => 10,
                'marginRight' => 10,
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => $css,
                'options' => [
                    'title' => 'Factuur',
                    'subject' => 'Generating PDF files via yii2-mpdf extension has never been easy',
                ],
            ]);
            return $pdf->render();



        }

public function actionGetdata($data){
    $model=New Providers;
    $model2=New Clients;
    $model3=New Person;
if ($data=="Proveedor"){
    $model2::find()->all();
    $c=$model3::find()->innerJoin('providers',"person.id=providers.person_id")->where(["person.institution_id"=>$_SESSION['id_ins']->id])->all();
    foreach($c as $co){
        echo "<option value='$co->id'>$co->name</option>";
    }
}
else{
    if ($data=="Cliente"){
        $model2::find()->all();
        $c=$model3::find()->innerJoin('clients',"person.id=clients.id_personas")->where(["person.institution_id"=>$_SESSION['id_ins']->id])->all();
        foreach($c as $co){
            echo "<option value='$co->id'>$co->name</option>";
        }
    }
}
}
    public function actionGetretention()
    {
        if(Yii::$app->request->isAjax){
            $data=Yii::$app->request->post();
            $datos=Retention::findOne([$data["single"]]);
            return(\yii\helpers\Json::encode($datos));
        }
    }
    public function actionBuscarf($fil,$per,$tipo)
    {


        $model2 = HeadFact::find()->innerjoin("person","person.id=head_fact.id_personas")->andFilterWhere(['like', 'head_fact.n_documentos', $fil . '%', false])->andFilterWhere(['head_fact.id_personas' => $per])->andFilterWhere(['head_fact.tipo_de_documento' => $tipo])->andFilterWhere(['person.institution_id' =>  $_SESSION['id_ins']->id])->all();
        yii::debug($model2);


        foreach ($model2 as $mod) {
            $total = Facturafin::findOne(['id_head' => $mod->n_documentos]);
            if (!is_null($total)) {
                echo " <tr>";

                echo "<td>
        <div class='row'>
            <div class='col-4'>
                <a href='" . Url::to(['cliente/editar', 'id' => $mod->n_documentos]) . "' class='btn btn-primary' title='Editar' data-toggle='tooltip'> <i class='fas fa-edit'></i></a>" .
                    "</div>" .
                    "<div class='col-4'>" .
                    "<a href='" . Url::to(['cliente/delete', 'id' => $mod->n_documentos]) . "' class='btn btn-danger' title='Eliminar' data-toggle='tooltip'> <i class='fas fa-trash'></i></a>" .

                    "</div>" .
                    "</div>" .
                    "</td>";
                echo "<td>";
                echo Yii::$app->formatter->asDate($mod->f_timestamp, 'php:Y-m-d');
                echo "</td>";
                echo "<td>" .
                    HTML::a('fac' . $mod->n_documentos, Url::to(['cliente/viewf', 'id' => $mod->n_documentos])) . "</td>
</td>";
                echo "<td>" .
                    $total->subtotal12 .
                    "</td>";
                echo "<td>" .
                    $total->iva .
                    "</td>";
                echo "<td>" .
                    $total->total .
                    "</td>";

                echo "</tr>";


            }


            echo $fil;


        }
    }
    public function action





























    ($fil){




            $nombre = $_POST['tipo'];
        yii::debug($nombre);
        $model2=HeadFact::find()->filterWhere(["tipo_de_documento"=>$fil])->andFilterWhere(['like', 'n_documentos', $nombre. '%' , false])->all();
         yii::debug($model2);


         foreach($model2 as $mod){
             $total=Facturafin::findOne(['id_head'=>$mod->n_documentos]);

        echo "<tr>";
    echo"<td>
        <div class='row'>
            <div class='col-4'>
                <a href='".Url::to(['cliente/editar','id' => $fac->n_documentos])."' class='btn btn-primary' title='Editar' data-toggle='tooltip'> <i class='fas fa-edit'></i></a>".
            "</div>".
            "<div class='col-4'>".
               "<a href='".Url::to(['cliente/delete','id' => $fac->n_documentos])."' class='btn btn-danger' title='Eliminar' data-toggle='tooltip'> <i class='fas fa-trash'></i></a>".

            "</div>".
        "</div>".
                 "</td>";
      echo "<td>";
echo Yii::$app->formatter->asDate($mod->f_timestamp, 'php:Y-m-d');
echo "</td>";
             echo "<td>".
        HTML::a('fac' .  $mod->n_documentos,Url::to(['cliente/viewf', 'id' => $mod->n_documentos]))."</td>
</td>";
             echo "<td>".
                 $total->subtotal12.
            "</td>";
             echo "<td>".
                 $total->iva.
                 "</td>";
             echo "<td>".
                 $total->total.
                 "</td>";

             echo "</tr>";


         }



            echo $fil;


        }



    public function actionFormclientrender(){
    $person=new Person;
    $query=$person::find()->select("ruc")->all();
    $dataProvider = new ActiveDataProvider([
            'query' => $query

        ]);





    return $this->render('formclientrender', [
            'model' => $query
        ]);
    }

    public function actionVer(){


        $this->render("ver",["hola"=>"carton"]);
    }
    public function actionGuardarproceso(){

        if(Yii::$app->request->isAjax){
            $data=Yii::$app->request->post();
            $cantidad=$data['cantidad'];
            $producto=$data['produc'];
            $preciou=$data['preciou'];
            $precioto=$data['precioto'];
            $id_head=$data['ndocumento'];
            $retimp=$data['retimp'];
            $retiva=$data['retinv'];
            $nre=$data['nret'];
            $autorite=$data['autorite'];

            $_SESSION['codeimp']=\yii\helpers\Json::decode($data['codeimp']);
            $i=count($cantidad);
            for($k=0;$k<$i;$k++){
                $id_product=New Product;
                $i_pro=$id_product::findOne(['name'=>$producto[$k]]);
                $facbody=New FacturaBody;
                $facbody->cant=$cantidad[$k];
                $facbody->precio_u=$preciou[$k];
                $facbody->precio_total=$precioto[$k];
                $facbody->id_producto=$i_pro->id;
                $facbody->id_head=$id_head;
                $facbody->retencion_imp=$retimp[$k];
                $facbody->retencion_iva=$retiva[$k];
                $facbody->n_de_retencion=$nre;
                $facbody->autorizacion=$autorite;
                $facbody->save();
            }

        }


        $this->render("guardarproceso");
    }
    public function actionEditarproceso(){

        if(Yii::$app->request->isAjax){
            $data=Yii::$app->request->post();
            $cantidad=$data['cantidad'];
            $producto=$data['produc'];
            $preciou=$data['preciou'];
            $precioto=$data['precioto'];
            $id_head=$data['ndocumento'];
            $head_anterior=$data['nant'];
            $this->query("factura_body","id_head",$head_anterior);

            yii::debug($id_head);
            $i=count($cantidad);
            for($k=0;$k<$i;$k++){
                $id_product=New Product;
                $i_pro=$id_product::findOne(['name'=>$producto[$k]]);
                $facbody=New FacturaBody;
                $facbody->cant=$cantidad[$k];
                $facbody->precio_u=$preciou[$k];
                $facbody->precio_total=$precioto[$k];
                $facbody->id_producto=$i_pro->id;
                $facbody->id_head=$id_head;
                $facbody->save();
            }

        }

    }
    public function actionPdfview($id,$ischair){
        $modelhead=New HeadFact;
        $modelbody=New FacturaBody;
        $modelfin=New Facturafin;
        $persona=New Person;


        $id=$_GET["id"];
        $model1=$modelhead::findOne(["n_documentos"=>$id]);
        $model2=$modelbody::find()->where(["id_head"=>$id])->all();
        $model3=$modelfin::findOne(["id_head"=>$id]);
        $persona=$persona::findOne(["id"=>$model1->id_personas]);
        $modelo= AccountingSeats::find()->where(["head_fact"=>$id])->all();

        $content = $this->renderPartial('pdfview', [
            'model' => $model1,"model2"=>$model2,"modelfin"=>$model3,"personam"=>$persona,"modelas"=>$modelo

        ]);
        $pdf = new \kartik\mpdf\Pdf([
            'mode' => \kartik\mpdf\Pdf::MODE_UTF8, // leaner size using standard fonts
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'options' => [
                'title' => 'Factuur',
                'subject' => 'Generating PDF files via yii2-mpdf extension has never been easy'
            ],
            'methods' => [
                'SetHeader' => ['<br> <br> <br> <br> <br>'],
                'SetFooter' => ['|Page {PAGENO}|'],
            ]
        ]);
        return $pdf->render();
    }
    public function actionDelete($id)
    {
        $fre = Charges::find()->where(["n_document" => $id])->exists();
        if (!$fre) {
            $head = new HeadFact;
            $nhe = $head::findOne(["n_documentos" => $id]);
            $this->query("facturafin", "id_head", $id);
            $this->query("factura_body", "id_head", $id);
            $this->query("head_fact", "n_documentos", $id);
            $v = AccountingSeats::find()->where(["head_fact" => $id])->all();
            foreach ($v as $ac) {
                $this->query("accounting_seats_details", "accounting_seat_id", $ac->id);
            }
            $this->query("accounting_seats", "head_fact", $id);
            $url = $_SERVER['HTTP_REFERER'];
            return $this->redirect($url);
        }
        else{
            Yii::$app->session->addFlash("error", "No se puede eliminar la factura");
            $url = $_SERVER['HTTP_REFERER'];
            return $this->redirect($url);


        }
    }

    public function query($query,$col,$id){
        (new Query)
            ->createCommand()
            ->delete($query, [$col => $id])
            ->execute();
    }
    public function actionEditar($id)
    {
        $fre = Charges::find()->where(["n_document" => $id])->exists();
        if (!$fre) {
            $model = new HeadFact;
            $person = new Person;
            $client = new Clients;
            $institucion = new Institution;
            $model_tip = new ProductType;
            $salesman = new Salesman;
            $model2 = new FacturaBody;
            $productos = new Product;
            $facturafin = new Facturafin;
            $accounting_seats = new AccountingSeats;

            $accounting_seats_details = new AccountingSeatsDetails;
            $dbody = $model2::find()->where(['id_head' => $id])->all();
            $dfin = $facturafin::findOne(['id_head' => $id]);
            $head_fact = $model::findOne(['n_documentos' => $id]);
            $providers = $person::find()->innerJoin('providers', "person.id=providers.person_id")->all();
            $persona = $person::find()->select("name")->innerJoin("clients", "person.id=clients.person_id")->all();
            $model_tipo = $model_tip::find()->select("name")->all();
            $pro = $productos::find()->select("name")->all();
            $precio = $productos::find()->all();
            $precioser = $productos::find()->where(['product_type_id' => 2])->all();

            $per = Yii::$app->request->post('Person');
            $query = $person::find()->innerJoin("clients", "person.id=clients.person_id")->all();
            $providers = $person::find()->innerJoin("providers", "person.id=providers.person_id")->all();
            if ($model->load(Yii::$app->request->post())) {
                /* Actualiza Header */
                $d = Yii::$app->request->post('Facturafin');
                yii::debug($d["subtotal12"]);
                $fac = HeadFact::findOne(["n_documentos" => $_GET["id"]]);
                $fac->updateAttributes(['Entregado' => $model->Entregado]);
                $fac->updateAttributes(['f_timestamp' => $model->f_timestamp]);
                $fac->updateAttributes(['n_documentos' => $model->n_documentos]);
                $fac->updateAttributes(['referencia' => $model->referencia]);
                $fac->updateAttributes(['orden_cv' => $model->orden_cv]);
                $fac->updateAttributes(['autorizacion' => $model->autorizacion]);
                $fac->updateAttributes(['tipo_de_documento' => $model->tipo_de_documento]);
                /* Actualiza detalle */
                $ac = Facturafin::findOne(["id_head" => $_GET["id"]]);
                $ac->updateAttributes(['total' => $d["total"]]);
                $ac->updateAttributes(['id_head' => $model->n_documentos]);
                $ac->updateAttributes(['subtotal12' => $d["subtotal12"]]);
                $ac->updateAttributes(['descuento' => $d["descuento"]]);
                $ac->updateAttributes(['subtotal0' => $d["subtotal0"]]);
                $ac->updateAttributes(['iva' => $d["iva"]]);
                /* Actualiza account seat */
                $account = AccountingSeats::find()->where(["head_fact" => $_GET["id"]])->all();
                foreach ($account as $aci) {
                    $aci->updateAttributes(['head_fact' => $model->n_documentos]);
                }
                /* Aqui comienza asiento cliente */
                if ($model->tipo_de_documento == "Cliente") {
                    $per = Yii::$app->request->post('Person');
                    $ch1 = $client::findOne(['person_id' => $per["id"]]);
                    $accou_c = $ch1->chart_account_id;
                    $bodyf = FacturaBody::find()->where(['id_head' => $model->n_documentos])->all();

                    /* obtenemos productos */
                    $sum = 0;
                    $debe = array();
                    $haber = array();
                    $suma = array();
                    foreach ($bodyf as $bod) {
                        $cos = Product::findOne(["id" => $bod->id_producto]);
                        $sum = $sum + ($bod->precio_total);
                        if (!(is_null($cos->charingresos))) {
                            $haber[] = $cos->charingresos;
                            $suma[] = $bod->precio_total;
                            yii::debug($suma);
                        }
                    }
                    if (count($haber) > 0) {
                        $haber[] = 13273;
                        $i = count($haber);
                        $count = 0;
                        $account = AccountingSeats::find()->where(["head_fact" => $model->n_documentos])->andWhere(["type" => "ingresos"])->all();
                        $account1 = AccountingSeats::find()->where(["head_fact" => $model->n_documentos])->andWhere(["type" => "ingresos"])->asArray()->one();
                        $f = $account1["id"];

                        foreach ($account as $aco) {
                            $asientos = AccountingSeatsDetails::find()->where(["accounting_seat_id" => $aco->id])->all();
                            yii::debug($asientos);
                        }

                        $cana = count($asientos) - 1;
                        yii::debug($haber);
                        if ($i == $cana) {
                            $co = 0;
                            foreach ($asientos as $asi) {

                                if ($co == 0) {
                                    $asi->updateAttributes(['debit' => $d["total"]]);
                                    $asi->updateAttributes(['credit' => 0]);
                                    $asi->updateAttributes(['chart_account_id' => $accou_c]);
                                } else {
                                    if ($co < $i) {
                                        $asi->updateAttributes(['debit' => 0]);
                                        yii::debug($suma);
                                        $asi->updateAttributes(['credit' => $suma[$co - 1]]);
                                        $asi->updateAttributes(['chart_account_id' => $haber[$co - 1]]);

                                    } else {
                                        $asi->updateAttributes(['debit' => 0]);
                                        $asi->updateAttributes(['credit' => $d["iva"]]);
                                        $asi->updateAttributes(['chart_account_id' => $haber[$co - 1]]);
                                    }
                                }
                                $co = $co + 1;
                            }

                        }
                        if ($i < $cana) {
                            $co = 0;
                            foreach ($asientos as $asi) {
                                if ($co == 0) {
                                    $asi->updateAttributes(['debit' => $d["total"]]);
                                    $asi->updateAttributes(['credit' => 0]);
                                    $asi->updateAttributes(['chart_account_id' => $accou_c]);
                                } else {
                                    if ($co < $i) {
                                        $asi->updateAttributes(['debit' => 0]);
                                        yii::debug($suma);
                                        $asi->updateAttributes(['credit' => $suma[$co - 1]]);
                                        $asi->updateAttributes(['chart_account_id' => $haber[$co - 1]]);

                                    } else {
                                        if ($co == $i) {
                                            $asi->updateAttributes(['debit' => 0]);
                                            $asi->updateAttributes(['credit' => $d["iva"]]);
                                            $asi->updateAttributes(['chart_account_id' => $haber[$co - 1]]);
                                        }
                                    }
                                    if ($co > $i) {
                                        $asi->delete();
                                    }
                                }
                                $co = $co + 1;
                            }
                        }
                        if ($i > $cana) {
                            $co = 0;
                            foreach ($asientos as $asi) {
                                if ($co == 0) {
                                    $asi->updateAttributes(['debit' => $d["total"]]);
                                    $asi->updateAttributes(['credit' => 0]);
                                    $asi->updateAttributes(['chart_account_id' => $accou_c]);
                                } else {
                                    if ($co < $i) {
                                        $asi->updateAttributes(['debit' => 0]);
                                        yii::debug($suma);
                                        $asi->updateAttributes(['credit' => $suma[$co - 1]]);
                                        $asi->updateAttributes(['chart_account_id' => $haber[$co - 1]]);

                                    }
                                }
                                $co = $co + 1;
                            }
                            for ($k = $co; $k <= $i; $k++) {
                                if ($k < $i) {
                                    $accounting_seats_details = new AccountingSeatsDetails;
                                    $accounting_seats_details->accounting_seat_id = $f;
                                    $accounting_seats_details->chart_account_id = $haber[$k - 1];
                                    $accounting_seats_details->debit = 0;
                                    $accounting_seats_details->credit = $suma[$k-1];
                                    $accounting_seats_details->cost_center_id = 1;
                                    $accounting_seats_details->status = true;
                                    $accounting_seats_details->save();
                                }
                                else {
                                    $accounting_seats_details = new AccountingSeatsDetails;
                                    $accounting_seats_details->accounting_seat_id = $f;
                                    $accounting_seats_details->chart_account_id = $haber[$k - 1];
                                    $accounting_seats_details->debit = 0;
                                    $accounting_seats_details->credit = $d["iva"];
                                    $accounting_seats_details->cost_center_id = 1;
                                    $accounting_seats_details->status = true;
                                    $accounting_seats_details->save();
                                }
                            }


                        }
                        /* Asiento de inventario */
                        $bodyf = FacturaBody::find()->where(['id_head' => $model->n_documentos])->all();
                        $sum = 0;

                        $debe = array();
                        $haber = array();
                        $suma = array();
                        foreach ($bodyf as $bod) {
                            $cos = Product::findOne(["id" => $bod->id_producto]);
                            Yii::debug($cos);
                            if (!(is_null($cos->Chairinve))) {
                                $sum = $sum + (($cos->costo) * ($bod->cant));
                                $debe[] = $cos->Chairinve;
                                $haber[] = $cos->chairaccount_id;
                                $suma[] = ($cos->costo) * ($bod->cant);
                                yii::debug($debe);
                            }
                        }
                        if (count($debe) == 0) {
                            $account1 = AccountingSeats::find()->where(["head_fact" => $model->n_documentos])->andWhere(["type" => "inventario"])->exists();
                            if ($account1) {
                                $account = AccountingSeats::find()->where(["head_fact" => $model->n_documentos])->andWhere(["type" => "inventario"])->all();
                                foreach ($account as $aco) {
                                    $asientos = AccountingSeatsDetails::find()->where(["accounting_seat_id" => $aco->id])->all();
                                    foreach ($asientos as $asi) {
                                        $asi->delete();
                                    }

                                    $aco->delete();
                                }

                            }
                        } else {
                            $account1 = AccountingSeats::find()->where(["head_fact" => $model->n_documentos])->andWhere(["type" => "inventario"])->exists();
                            if (!$account1) {
                                $accounting_sea = new AccountingSeats;
                                $gr = rand(1, 100090000);
                                $accounting_sea->head_fact = $model->n_documentos;
                                $accounting_sea->id = $gr;
                                $accounting_sea->institution_id = $_SESSION['id_ins']->id;
                                $accounting_sea->description = "inventario";
                                $accounting_sea->nodeductible = true;
                                $accounting_sea->type = "inventario";
                                $accounting_sea->status = true;
                                if ($accounting_sea->save()) {

                                    $pro = Yii::$app->request->post("Product");
                                    for ($i = 0; $i < count($debe); $i++) {
                                        $accounting_seats_details = new AccountingSeatsDetails;
                                        $accounting_seats_details->accounting_seat_id = $accounting_sea->id;
                                        $accounting_seats_details->chart_account_id = $debe[$i];
                                        yii::debug($debe[$i]);
                                        $accounting_seats_details->debit = $suma[$i];
                                        $accounting_seats_details->credit = 0;
                                        $accounting_seats_details->cost_center_id = 1;
                                        $accounting_seats_details->status = true;
                                        $accounting_seats_details->save();
                                        $accounting_seats_details = new AccountingSeatsDetails;
                                        $accounting_seats_details->accounting_seat_id = $accounting_sea->id;
                                        $accounting_seats_details->chart_account_id = $haber[$i];
                                        $accounting_seats_details->debit = 0;
                                        $accounting_seats_details->credit = $suma[$i];
                                        $accounting_seats_details->cost_center_id = 1;
                                        $accounting_seats_details->status = true;
                                        $accounting_seats_details->save();
                                    }
                                }
                            } else {
                                $account = AccountingSeats::find()->where(["head_fact" => $model->n_documentos])->andWhere(["type" => "inventario"])->all();
                                foreach ($account as $aco) {
                                    $asientos = AccountingSeatsDetails::find()->where(["accounting_seat_id" => $aco->id])->all();
                                    foreach ($asientos as $asi) {
                                        $asi->delete();
                                    }

                                    $aco->delete();
                                }
                                $accounting_sea = new AccountingSeats;
                                $gr = rand(1, 100090000);
                                $accounting_sea->head_fact = $model->n_documentos;
                                $accounting_sea->id = $gr;
                                $accounting_sea->institution_id = $_SESSION['id_ins']->id;
                                $accounting_sea->description = "fact2";
                                $accounting_sea->nodeductible = true;
                                $accounting_sea->status = true;
                                $accounting_sea->type = "inventario";
                                if ($accounting_sea->save()) {

                                    $pro = Yii::$app->request->post("Product");
                                    for ($i = 0; $i < count($debe); $i++) {
                                        $accounting_seats_details = new AccountingSeatsDetails;
                                        $accounting_seats_details->accounting_seat_id = $accounting_sea->id;
                                        $accounting_seats_details->chart_account_id = $debe[$i];
                                        yii::debug($debe[$i]);
                                        $accounting_seats_details->debit = $suma[$i];
                                        $accounting_seats_details->credit = 0;
                                        $accounting_seats_details->cost_center_id = 1;
                                        $accounting_seats_details->status = true;
                                        $accounting_seats_details->save();
                                        $accounting_seats_details = new AccountingSeatsDetails;
                                        $accounting_seats_details->accounting_seat_id = $accounting_sea->id;
                                        $accounting_seats_details->chart_account_id = $haber[$i];
                                        $accounting_seats_details->debit = 0;
                                        $accounting_seats_details->credit = $suma[$i];
                                        $accounting_seats_details->cost_center_id = 1;
                                        $accounting_seats_details->status = true;
                                        $accounting_seats_details->save();
                                    }

                                }

                            }

                        }
                    }
                }
                /* Asiento de compras */
                if ($model->tipo_de_documento == "Proveedor") {
                    $bodyf = FacturaBody::find()->where(['id_head' => $model->n_documentos])->all();
                    $sum = 0;
                    $haber = array();
                    $debe = [];
                    $suma = array();
                    $per = Yii::$app->request->post('Person');
                    $ch1 = Providers::findOne(['person_id' => $per["id"]]);
                    $accou_c = $ch1->paid_chart_account_id;
                    foreach ($bodyf as $bod) {
                        $cos = Product::findOne(["id" => $bod->id_producto]);
                        $sum = $sum + ($bod->precio_total);
                        $debe[] = $cos->chairaccount_id;
                        $suma[] = $bod->precio_total;
                    }

                    yii::debug($debe);
                    if (count($debe) > 0) {
                        $debe[] = 13162;

                        $i = count($debe);
                        $count = 0;
                        $account = AccountingSeats::find()->where(["head_fact" => $model->n_documentos])->andWhere(["type" => "ingresos"])->all();
                        $account1 = AccountingSeats::find()->where(["head_fact" => $model->n_documentos])->andWhere(["type" => "ingresos"])->asArray()->one();
                        $f = $account1["id"];
                        foreach ($account as $aco) {
                            $asientos = AccountingSeatsDetails::find()->where(["accounting_seat_id" => $aco->id])->all();
                        }


                        yii::debug(count($asientos) - 1);
                        $cana = count($asientos) - 1;
                        yii::debug($haber);
                        yii::debug($d["total"]);
                        if ($i == $cana) {
                            $co = 0;
                            foreach ($asientos as $asi) {

                                if ($co < $i - 1) {

                                    $asi->updateAttributes(['debit' => $suma[$co]]);
                                    yii::debug($suma[$co]);
                                    $asi->updateAttributes(['credit' => 0]);
                                    $asi->updateAttributes(['chart_account_id' => $debe[$co]]);
                                    yii::debug($debe[$co]);
                                } else
                                    if ($co == $i - 1) {
                                        $asi->updateAttributes(['debit' => $d["iva"]]);
                                        yii::debug($suma);
                                        $asi->updateAttributes(['credit' => 0]);
                                        $asi->updateAttributes(['chart_account_id' => $debe[$co]]);

                                    } else {
                                        $asi->updateAttributes(['debit' => 0]);
                                        $asi->updateAttributes(['credit' => $d["total"]]);
                                        $asi->updateAttributes(['chart_account_id' => $accou_c]);
                                    }

                                $co = $co + 1;
                            }

                        }
                        if ($i < $cana) {
                            $co = 0;
                            foreach ($asientos as $asi) {
                                if ($co < $i - 1) {
                                    $asi->updateAttributes(['debit' => $suma[$co]]);
                                    yii::debug($suma[$co]);
                                    $asi->updateAttributes(['credit' => 0]);
                                    $asi->updateAttributes(['chart_account_id' => $debe[$co]]);
                                    yii::debug($debe[$co]);
                                } else
                                    if ($co == $i - 1) {
                                        $asi->updateAttributes(['debit' => $d["iva"]]);
                                        yii::debug($suma);
                                        $asi->updateAttributes(['credit' => 0]);
                                        $asi->updateAttributes(['chart_account_id' => $debe[$co]]);

                                    } else
                                        if ($co == $i) {
                                            $asi->updateAttributes(['debit' => 0]);
                                            $asi->updateAttributes(['credit' => $d["total"]]);
                                            $asi->updateAttributes(['chart_account_id' => $accou_c]);
                                        }

                                if ($co > $i) {
                                    $asi->delete();
                                }
                                $co = $co + 1;
                            }


                        }
                        if ($i > $cana) {
                            $co = 0;

                            foreach ($asientos as $asi) {
                                if ($co < count($asientos)-1) {


                                        $asi->updateAttributes(['debit' => $suma[$co]]);
                                        $asi->updateAttributes(['credit' => 0]);
                                        $asi->updateAttributes(['chart_account_id' => $debe[$co]]);


                                }

                                $co = $co + 1;
                            }
                            for ($k = $co; $k <= $i; $k++) {
                                if ($k < $i) {
                                    $accounting_seats_details = new AccountingSeatsDetails;
                                    $accounting_seats_details->accounting_seat_id = $f;
                                    $accounting_seats_details->chart_account_id = $debe[$k-1];
                                    $accounting_seats_details->debit = $suma[$k-1];
                                    $accounting_seats_details->credit = 0;
                                    $accounting_seats_details->cost_center_id = 1;
                                    $accounting_seats_details->status = true;
                                    $accounting_seats_details->save();
                                } else
                                    if ($k == $i ) {
                                        $accounting_seats_details = new AccountingSeatsDetails;
                                        $accounting_seats_details->accounting_seat_id = $f;
                                        $accounting_seats_details->chart_account_id = $debe[$k-1];
                                        $accounting_seats_details->debit = $d["iva"];
                                        $accounting_seats_details->credit = 0;
                                        $accounting_seats_details->cost_center_id = 1;
                                        $accounting_seats_details->status = true;
                                        $accounting_seats_details->save();
                                        $account1 = AccountingSeats::find()->where(["head_fact" => $model->n_documentos])->andWhere(["type" => "ingresos"])->asArray()->one();
                                        $f = $account1["id"];
                                        foreach ($account as $aco) {
                                            $asientos = AccountingSeatsDetails::find()->where(["accounting_seat_id" => $aco->id])->andWhere(["debit"=>0])->all();
                                        }
                                        foreach($asientos as $asi){
                                          $asi->delete();
                                        }
                                        $accounting_seats_details = new AccountingSeatsDetails;
                                        $accounting_seats_details->accounting_seat_id = $f;
                                        $accounting_seats_details->chart_account_id = $accou_c;
                                        $accounting_seats_details->debit = 0;
                                        $accounting_seats_details->credit = $d["total"];
                                        $accounting_seats_details->cost_center_id = 1;
                                        $accounting_seats_details->status = true;
                                        $accounting_seats_details->save();
                                        break;

                                    }
                                    else

                                    {
                                        break;

                                    }


                            }


                        }
                    }
                }


                $this->redirect("index?tipos=Cliente");


                return $this->render('editar', [
                    'head_fact' => $head_fact, 'model' => $model, "dbo" => $dbody, "dfin" => $dfin, "ven" => $persona, "model2" => $model2, "produc" => $pro, "precio" => $precio, "query" => $query, 'model3' => $facturafin, 'modeltype' => $model_tipo, 'produ' => $productos, "providers" => $providers

                ]);
            }

            return $this->render('editar', [
                'head_fact' => $head_fact, 'providers' => $providers, 'model' => $model, "dbo" => $dbody, "dfin" => $dfin, "ven" => $persona, "model2" => $model2, "produc" => $pro, "precio" => $precio, "query" => $query, 'model3' => $facturafin, 'modeltype' => $model_tipo, 'produ' => $productos, "providers" => $providers

            ]);
        }
        else{
            Yii::$app->session->addFlash("negative", "My flash message");
            return Yii::$app->getResponse()->redirect(['cliente/index?tipos=Cliente']);
            Yii::$app->end();
        }
    }
    public function actionSync(){
    return $this->render('sync');
    }
}
