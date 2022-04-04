<?php

namespace app\controllers;

use app\models\AccountingSeats;
use app\models\AccountingSeatsDetails;
use app\models\BankDetails;
use app\models\Charges;
use app\models\ChargesDetail;
use app\models\ChartAccounts;
use app\models\Facturafin;
use app\models\HeadFact;
use app\models\Institution;
use app\models\Person;
use DateTime;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
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
                            $charges_detail->balance = $body->total;
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
                                if($chargem->type_charge=="Pago") {
                                    if ($charges_detail->type_transaccion == "Caja") {

                                        $this->asientoscreate($gr, 13234, $charges_detail->chart_account, $charges_detail->amount,$head->n_document,$charges_detail->Description);
                                    } else {

                                        if ($charges_detail->type_transaccion == "Transferencia" || $charges_detail->type_transaccion == "Cheque") {
                                            $this->asientoscreate($gr, 13234, $charges_detail->chart_account, $charges_detail->amount,$head->n_document,$charges_detail->Description);
                                        }
                                    }
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
                                $charges_detail->balance = $body->total;
                                $charges_detail->saldo = $body->total;
                                $charges_detail->save();
                                if ($charges_detail->save()) {
                                    $charges_detail->updateAttributes(['saldo' => ($body->total) - ($charges_detail->amount)]);
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
        return $this->render("index",["chargem"=>$chargem,"charguesd"=>$charges_detail,"Person"=>$persona,"body"=>$body,"header"=>$header,"upt"=>$upt,"bank"=>$bank_details]);
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