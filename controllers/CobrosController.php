<?php

namespace app\controllers;

use app\models\AccountingSeats;
use app\models\AccountingSeatsDetails;
use app\models\BankDetails;
use app\models\Charges;
use app\models\ChargesDetail;
use app\models\ChartAccounts;
use app\models\FacturaBody;
use app\models\Facturafin;
use app\models\HeadFact;
use app\models\Institution;
use app\models\Person;
use app\models\Retention;
use DateTime;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;

class CobrosController extends Controller
{
    public function actionCobros($id){
        $chargem=New charges;
        $charges_detail=New ChargesDetail;
        $bank_details=New BankDetails;
        $Persona=New Person;
        $Header=New HeadFact;
        $id=$_GET['id'];
        $body=Facturafin::findOne(["id_head"=>$id]);
        $sum=0;
        $facbod=FacturaBody::find()->where(["id_head"=>$id])->all();
        foreach ($facbod as $fac){
            if(!is_null($fac->retencion_imp)){
                $retencion=Retention::findOne($fac->retencion_imp);
                $base=$fac->precio_total;
                $porcentaje=$retencion->percentage;
                $sum+=$base*$porcentaje/100;
            }
            if(!is_null($fac->retencion_iva)){
                $retencion=Retention::findOne($fac->retencion_iva);
                $base=($fac->precio_total*12)/100;
                $porcentaje=$retencion->percentage;
                $sum+=$base*$porcentaje/100;
            }

        }
        $header=$Header->findOne(["n_documentos"=>$id]);
        $persona=$Persona::findOne(["id"=>$header->id_personas]);
        $upt=$chargem::find()->where(["n_document"=>$header->n_documentos])->exists();
        yii::debug($upt);
        if($chargem->load(Yii::$app->request->post())) {
            $chargem->id = $this->getid();
            if($chargem->validate()){
                if ($chargem->validate());
                $up=$chargem::find()->where(["n_document"=>$header->n_documentos])->exists();
                if ($up==True){
                    $ac=$chargem::findOne(["n_document"=>$header->n_documentos]);

                    $li=ChargesDetail::find()->orderBy([
                        'date' => SORT_DESC
                    ])->where(["id_charge"=>$ac->id])->asArray()->one();
                    Yii::debug($li);
                    $saldo_anterior=$li["saldo"];
                    if($charges_detail->load(Yii::$app->request->post())) {
                        if($charges_detail->amount>$saldo_anterior){
                            throw new HttpException(404, Yii::t('app','Ha ingresado una cantidad invalida vuelva a intentarlo'));
                        }
                        else{
                            $saldo_nuevo=$saldo_anterior-$charges_detail->amount;
                            $charges_detail->id_charge = $ac->id;
                            $charges_detail->balance = $body->total-$sum;
                            $charges_detail->saldo = $saldo_nuevo;
                            $charges_detail->save();
                            if($charges_detail->save()){
                                $head=Charges::findOne($charges_detail->id_charge);
                                $gr = rand(1, 100090000233243);
                                $charges_detail->updateAttributes(['id_asiento' => $gr]);
                                if($chargem->type_charge=="Cobro") {
                                    if ($charges_detail->type_transaccion == "Caja") {
                                        $this->asientoscreate($gr, $charges_detail->chart_account, 13133, $charges_detail->amount,$head->n_document,$charges_detail->Description);
                                    } else {
                                        if ($charges_detail->type_transaccion == "Transferencia" || $chargem->type_charge == "Cheque") {
                                            $this->asientoscreate($gr, $charges_detail->chart_account, 13133, $charges_detail->amount,$head->n_document,$charges_detail->Description);
                                        }
                                    }
                                }
                                //aqui empieza pagos//
                                yii::debug($chargem->n_document);
                                $charse=ChargesDetail::findOne(["id"=>$charges_detail->id]);
                                if($chargem->type_charge=="Pago") {
                                    if ($charges_detail->type_transaccion == "Caja") {

                                        $this->asientoscreate($gr, 13234, $charges_detail->chart_account, $charges_detail->amount,$head->n_document,$charges_detail->Description);
                                    } else {

                                        if ($charges_detail->type_transaccion == "Transferencia" || $charges_detail->type_transaccion == "Cheque") {
                                            $this->asientoscreate($gr, 13234, $charges_detail->chart_account, $charges_detail->amount,$head->n_document,$charges_detail->Description);
                                        }
                                    }

                                    $postdata = http_build_query(
                                        array(
                                            'Comprobante' => $charges_detail->comprobante,
                                            'CuentaUid' => 'xyudisiiudsuis',
                                            'Descripcion' => $charges_detail->Description,
                                            'Fecha' => strval($charse->date),
                                            'Proveedor' => 'XVYYEdCrgnmlkM0YFhpp',
                                            'Rubro' =>'fact1',
                                            'SubRubro' => 'fact1',
                                            'Valor' => $charges_detail->amount,

                                        )
                                    );

                                    $opts = array('http' =>
                                        array(
                                            'ignore_errors' => true,
                                            'method' => 'POST',
                                            'header' => 'Content-Type: application/x-www-form-urlencoded',
                                            'content' => $postdata
                                        )
                                    );

                                    $context = stream_context_create($opts);
                                    yii::debug($context);
                                    file_get_contents('http://backendphp23.herokuapp.com/web/egresos', false, $context);
                                }
                            }
                        }
                    }

                }
                else {
                    if ($charges_detail->load(Yii::$app->request->post())) {
                        if ($charges_detail->amount <= $body->total) {
                            $chargem->n_document = $header->n_documentos;
                            $chargem->person_id = $persona->id;
                            $chargem->save();
                            if ($chargem->save()) {

                                $charges_detail->id_charge = $chargem->id;
                                $charges_detail->balance = $body->total-$sum;
                                $charges_detail->saldo = $body->total-$sum;
                                $charges_detail->save();
                                if ($charges_detail->save()) {
                                    $charges_detail->updateAttributes(['saldo' => ($body->total-$sum) - ($charges_detail->amount)]);
                                }

                                $gr = rand(1, 100090000);
                                $charges_detail->updateAttributes(['id_asiento' => $gr]);
                                if ($chargem->type_charge == "Cobro") {
                                    if ($charges_detail->type_transaccion == "Caja") {
                                        $this->asientoscreate($gr, $charges_detail->chart_account, 13133, $charges_detail->amount, $chargem->n_document, $charges_detail->Description);

                                    } else {
                                        if ($charges_detail->type_transaccion == "Transferencia" || $chargem->type_charge == "Cheque") {
                                            $this->asientoscreate($gr, $charges_detail->chart_account, 13133, $charges_detail->amount, $chargem->n_document, $charges_detail->Description);
                                        }
                                    }

                                }
                                //aqui empieza pagos//
                                if ($chargem->type_charge == "Pago") {
                                    if ($charges_detail->type_transaccion == "Caja") {

                                        $this->asientoscreate($gr, 13234, $charges_detail->chart_account, $charges_detail->amount, $chargem->n_document, $charges_detail->Description);


                                    } else {

                                        if ($charges_detail->type_transaccion == "Transferencia" || $charges_detail->type_transaccion == "Cheque") {
                                            $this->asientoscreate($gr, 13234, $charges_detail->chart_account, $charges_detail->amount, $chargem->n_document, $charges_detail->Description);
                                        }
                                    }
                                    $charse=ChargesDetail::findOne(["id"=>$charges_detail->id]);
                                    $postdata = http_build_query(
                                        array(
                                            'Comprobante' => $charges_detail->comprobante,
                                            'CuentaUid' => 'XVYYEdCrgnmlkM0YFhpp',
                                            'Descripcion' => $charges_detail->Description,
                                            'Fecha' => strval($charse->date),
                                            'Proveedor' => 'XVYYEdCrgnmlkM0YFhpp',
                                            'Rubro' =>'fact1',
                                            'SubRubro' => 'fact1',
                                            'Valor' => $charges_detail->amount,

                                        )
                                    );

                                    $opts = array('http' =>
                                        array(
                                            'ignore_errors' => true,
                                            'method' => 'POST',
                                            'header' => 'Content-Type: application/x-www-form-urlencoded',
                                            'content' => $postdata
                                        )
                                    );

                                    $context = stream_context_create($opts);
                                    yii::debug($context);
                                    file_get_contents('http://backendphp23.herokuapp.com/web/egresos', false, $context);
                                }
                            }
                        }
                        else{
                            throw new HttpException(404,"El valor ingresado es mas grande");
                        }
                    }

                }
            }
            $url = $_SERVER['HTTP_REFERER'];
            $this->redirect($url);
        }
        return $this->render("index",["chargem"=>$chargem,"charguesd"=>$charges_detail,"Person"=>$persona,"body"=>$body,"header"=>$header,"upt"=>$upt,"bank"=>$bank_details,"sumret"=>$sum]);
    }
    public function actionView(){
        $id_ins=Institution::findOne(['users_id'=>Yii::$app->user->identity->id]);
        $model=ChargesDetail::find()->innerJoin("charges","charges_detail.id_charge=charges.id")->innerJoin("person","charges.person_id=person.id")->where(["person.institution_id"=>$id_ins->id])->orderBy(["date"=>SORT_ASC])->all();
        $model2=New Charges;
        return $this->render('view', [
            'transaccion'=>$model,"model"=>$model2
        ]);
    }
    public function actionDetail($id){
        $model=ChargesDetail::findOne(["serial"=>$id]);
        return $this->render('detail', [
            'transaccion'=>$model
        ]);
    }
    public function actionPdfview($id){
        $modelfin=New ChargesDetail;
        $persona=New Person;
        $modelo= ChargesDetail::findOne(["serial"=>$id]);
        Yii::debug($modelo);
        $modelo2=Charges::findOne(["id"=>$modelo->id_charge]);
        yii::debug($modelo->id_asiento);
        $accounting_sea=AccountingSeats::findOne([["id"=>$modelo->id_asiento]]);
        $content = $this->renderPartial('pdfview', [
            "modelo"=>$accounting_sea,"model2"=>$modelo2,"charge"=>$modelo]);
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
                'SetHeader' => ['<br> <br> <br> <br>' ],
                'SetFooter' => ['|Page {PAGENO}|'],
            ]
        ]);
        return $pdf->render();
    }
    public function actionGetdata($get){
        $model2=ChargesDetail::find()->andFilterWhere(['like', 'comprobante','%'. $get. '%' , false])->all();
        foreach($model2 as $mo){
            $tipo=\app\models\Charges::findOne($mo->id_charge);
            $person=\app\models\Person::findOne($tipo->person_id);

            $chart=\app\models\ChartAccounts::find()->where(["id"=>$mo->chart_account])->andwhere(["institution_id"=>1])->one();
            yii::debug($mo->chart_account);
            echo '<tr>'.'<td>'.$mo->date.'</td>'.
                '<td>'. HTML::a($mo->comprobante,Url::to(["detail", "id"=>$mo->serial])).'</td>'.
                '<td>'.$person->name.'</td>'.
                '<td>'. $tipo->type_charge.'</td>'.

                '<td>'. $chart->code." ".$chart->slug .'</td>'.
                '<td>'. $mo->amount.'</td>
    </tr>';


        }
    }
    public function actionGetper($ge){
        $per=Person::findOne($ge);
        $model2=Charges::find()->where(["person_id"=>$ge])->all();
        yii::debug($model2);
        foreach($model2 as $mo){
            $tipo=\app\models\ChargesDetail::find()->where(["id_charge"=>$mo->id])->all();
            foreach($tipo as $n){
                $chart = \app\models\ChartAccounts::find()->where(["id"=>$mo->chart_account])->andwhere(["institution_id"=>1])->one();

                echo '<tr>' . '<td>' . $n->date . '</td>' .
                    '<td>' . HTML::a($n->comprobante, Url::to(["detail", "id" => $n->serial])) . '</td>' .
                    '<td>' . $per->name . '</td>' .
                    '<td>' . $mo->type_charge . '</td>' .

                    '<td>' . $chart->code . " " . $chart->slug . '</td>' .
                    '<td>' . $n->amount . '</td>
    </tr>';
                yii::debug($n);
            }
        }
    }
    public function getid(){
        $c = rand(1, 100000);
        $fecha = new DateTime();
        $f=$fecha->getTimestamp();
        $id= $f+$c;
        return $id;
    }
    public function asientoscreate($gr,$debe,$haber,$body,$id_head,$description){
        $id_ins=Institution::findOne(['users_id'=>Yii::$app->user->identity->id]);

        $accounting_sea=new AccountingSeats;
        $accounting_sea->id= $gr;
        $accounting_sea->institution_id=$id_ins->id;
        $accounting_sea->description=$description;
        $accounting_sea->head_fact=$id_head;
        $accounting_sea->nodeductible=false;
        $accounting_sea->status=true;
        if($accounting_sea->save()) {

            $debe = $debe;
            $haber = $haber;

            $accounting_seats_details = new AccountingSeatsDetails;
            $accounting_seats_details->accounting_seat_id = $accounting_sea->id;
            $accounting_seats_details->chart_account_id = $debe;
            $accounting_seats_details->debit = $body;
            $accounting_seats_details->credit = 0;
            $accounting_seats_details->cost_center_id = 1;
            $accounting_seats_details->status = true;
            $accounting_seats_details->save();
            $accounting_seats_details = new AccountingSeatsDetails;
            $accounting_seats_details->accounting_seat_id = $accounting_sea->id;
            $accounting_seats_details->chart_account_id = $haber;
            $accounting_seats_details->debit = 0;
            $accounting_seats_details->credit = $body;
            $accounting_seats_details->cost_center_id = 1;
            $accounting_seats_details->status = true;
            $accounting_seats_details->save();
        }
    }
    public function actionSubcat($data){
        $id_ins=Institution::findOne(['users_id'=>Yii::$app->user->identity->id]);
        if ($data=="Transferencia"){
            $chart_account=\app\models\BankDetails::find()
                ->innerJoin("chart_accounts","bank_details.chart_account_id=chart_accounts.id")->where(['chart_accounts.institution_id' =>$id_ins->id])->all();
            foreach($chart_account as $co){
                echo "<option value='$co->chart_account_id'>$co->name</option>";
            }
        }
        else{
            if ($data=="Caja" || $data=="Cheque" ){
                $chart_account=\app\models\ChartAccounts::find()
                    ->where(['parent_id'=>13123])->andWhere(['institution_id' =>$id_ins->id])->all();;
                foreach($chart_account as $co){
                    echo "<option value='$co->id'>$co->code $co->slug</option>";
                }
            }
        }
    }
}