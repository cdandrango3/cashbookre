<?php

use app\models\Charges;
use app\models\ChartAccounts;
use app\models\Person;
use yii\helpers\Html;
use yii\helpers\Url;

$model2=Charges::findOne(["id"=>$transaccion->id_charge]);
$person=Person::findOne(["id"=>$model2->person_id]);
$char=ChartAccounts::findOne(["id"=>$transaccion->chart_account]);

?>
<div class="dropdown show text-right">
    <a class="btn btn-default dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-file-pdf text-red"></i>
    </a>

    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
        <a href='<?=Url::to(["pdfview","id"=>$transaccion->serial,"isbody"=>1])?>'class="btn btn-default" target="_blank" title="Exportar a pdf" data-toggle="tooltip"> Pdf con asiento</a>
        <br>
        <a href='<?=Url::to(["pdfview","id"=>$transaccion->serial,"isbody"=>0])?>'class="btn btn-default" target="_blank" title="Exportar a pdf" data-toggle="tooltip"> Pdf sin asiento</a>
    </div>

</div>
<div class="row">

</div>
<br>
<br>
<br>
<div class="container">
    <div class="card">
        <div class="card-header bg-primary">
            Datos Generales
        </div>
        <div class="card-body">
            <div>
                Tipo de transaccion :   <?=$model2->type_charge?>
            </div>
            <div>
                Fecha de emisión :   <?=Yii::$app->formatter->asDate($transaccion->date,'yyyy-MM-dd')?>
            </div>
            <div>
                Persona :   <?=$person->name?>
            </div>
            <div>
                Cuenta de Cobro :   <?=$char->slug?>
            </div>
            <div>
                Total :  $ <?= sprintf('%.2f', $transaccion->amount)?>
            </div>
            <div>
                Descripcion :   <?=$model2->Description?>
            </div>
        </div>
    </div>

</div>
<br>
<div class="container">
    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-bordered">

                <thead class="table">
                <tr>
                    <td>
                        Documento
                    </td>
                    <td>Fecha de emision</td>
                    <td>Tipo de Documento</td>
                    <td>Valor</td>
                    <td>Saldo A Pagar</td>
                    <td>Valor a pagar</td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?= HTML::a($model2->n_document,Url::to(["/cliente/viewf","id"=>$model2->n_document]))?></td>
                    <td><?= Yii::$app->formatter->asDate($transaccion->date,'yyyy-MM-dd')?></td>
                    <td>Factura</td>
                    <td><?= sprintf('%.2f',$transaccion->balance) ?></td>
                    <td><?= sprintf('%.2f',$transaccion->saldo)?></td>
                    <td><?= sprintf('%.2f',$transaccion->amount)?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
$js=<<< JS
    $('#nfac').keyup(function(){
        c=$(this).val();
        $.ajax({
        method: "POST",
            url: 'getdata?get='+c,
            success: function(data) {
            console.log(data)
                 $('#ui').html(data)   
            }
        })
    });
$('#personas').change(function(){
        c=$(this).val();
        console.log(c)
        $.ajax({
        method: "POST",
            url: 'getper?ge='+c,
            success: function(data) {
            console.log(data)
                 $('#ui').html(data)   
            }
        })
    });
JS;

$this->registerJs($js);