<?php

use app\models\AccountingSeatsDetails;
use app\models\FacturaBody;
use app\models\Person;
use app\models\Product;
use app\models\Retention;
use yii\helpers\Html;
$producto=New Product;
$get=$_GET["ischair"];
yii::debug($get);
$sale=Person::findOne(["id"=>$model->id_saleman]);
$isret=FacturaBody::find()->where(["id_head"=>$model->n_documentos])->andWhere('retencion_imp IS NOT NULL OR retencion_iva IS NOT NULL')->exists();
$ret=FacturaBody::find()->where(["id_head"=>$model->n_documentos])->one();
$sum=0;
        $facbod=FacturaBody::find()->where(["id_head"=>$_GET["id"]])->all();
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
?>
<table border="0" cellpadding="0" cellspacing="0" style="width:100%;font-family: Arial;font-size:9pt">

    <tr>
        <td>
            <div><h4><?=$personam->commercial_name?></h4></div></td>
        </td>
        <td>
            <div align="right"> <?= $model->tipo_de_documento ?></div></td>


    </tr>

    <tr>
        <td>
        </td>
        <td>
            <div align="right">Fecha de impresion:   <?= date('d-m-Y');?></div></td>

    </tr>


</table>
<br>
<br>
<div>

</div>
<h4><div align="center">Comprobante de Compra/Ventas</div></h4>
<br>
<table border="0" cellpadding="0" cellspacing="0" style="width:100%;font-family: Arial;font-size:9pt">

    <tr>
        <td style="width:35%"><b>Fecha de emisión:</b></td>
        <td><?= Yii::$app->formatter->asDatetime($model->f_timestamp,"yyyy-MM-dd")?></td>
    <tr>
        <td style="width:35%"><b>N de Documento:</b></td>
        <td> Fac <?= $model->n_documentos?></td>
    </tr>
    <tr>
        <td style="width:35%"><b>Persona</b></td>
        <td><?=$personam->name?></td>
    </tr>
    <tr>
        <?php if(!is_null($sale)):?>
             <td style="width:35%"><b>Vendedor</b></td>
        <td><?=$sale->name?></td>
        <?php endif?>
    </tr>
    <tr>
        <td style="width:35%"><b>Ruc/Ci</b></td>
        <td><?=$personam->ruc?></td>
    </tr>
    <tr>
        <td style="width:35%"><b>Telefonos</b></td>
        <td><?=$personam->phones?></td>
    </tr>


</table>
<br><br><br>
<div class="container">

            <table border="1" cellpadding="4" cellspacing="0" style="width:100%;font-family: Arial;font-size:9pt">
                <thead>
                <tr>
                <th>Cantidad</th>
                <th> Bien/Servicio</th>
                <th> Valor unitario </th>
                <th> Valor final </th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($model2 as $mbody): ?>
                    <?php $pro=$producto::findOne($mbody->id_producto)?>
                    <tr>
                        <td><?=sprintf('%.2f',$mbody->cant)?></td>
                        <td><?=$pro->name?></td>
                        <td><?=sprintf('%.2f',$mbody->precio_u)?></td>
                        <td><?=sprintf('%.2f',$mbody->precio_total)?></td>

                    </tr>
                <?php endforeach ?>

                </tbody>

            </table>
    <br>
    <br>
<?php if($isret):?>
<div style="text-align:center;font-family: Arial"><h4>Retencion</h4></div>
    <h5><strong>N de documento </strong><?=($ret->n_de_retencion)?:""?></h5>
    <br>
    <table border="1" cellpadding="4" cellspacing="0" style="width:100%;font-family: Arial;font-size:9pt">
        <thead>
        <tr>
            <th>Retencion</th>
            <th> Tipo</th>
            <th> Base</th>
            <th> %</th>
            <th> Total</th>
        </tr>
        <?php foreach($model2 as $mbody): ?>
        <?php if(!is_null($mbody->retencion_imp)):?>
                <?php
                $retencion=Retention::findOne($mbody->retencion_imp);
                $tipo="Impuesto";
                $codesri=$retencion->codesri." ".$retencion->slug;
                $base=$mbody->precio_total;
                $porcentaje=$retencion->percentage;
                $total=$base*$porcentaje/100;
                ?>
                <tr>
                    <td><?=$codesri?></td>
                    <td><?=$tipo?></td>
                    <td><?=sprintf('%.2f',$base)?></td>
                    <td><?=sprintf('%.2f',$porcentaje)?></td>
                    <td><?=sprintf('%.2f',$total)?></td>
                </tr>
            <?php endif?>
        <?php if(!is_null($mbody->retencion_iva)):?>
                <?php
                $retencion=Retention::findOne($mbody->retencion_iva);
                $tipo="Iva";
                $codesri=$retencion->codesri." ".$retencion->slug;
                $base=($mbody->precio_total*12)/100;
                $porcentaje=$retencion->percentage;
                $total=$base*$porcentaje/100;
                ?>
                <tr>
                    <td><?=$codesri?></td>
                    <td><?=$tipo?></td>
                    <td><?=$base?></td>
                    <td><?=$porcentaje?></td>
                    <td><?=$total?></td>
                </tr>
            <?php endif?>
        <?php endforeach ?>
        </thead>

    </table>

<?php endif?>

</div>
<div class="col-3">


</div>
<?php if($get==1) : ?>
<?php $i=0; ?>
<?php foreach($modelas as $modelo):?>
<?= (Yii::debug($modelo))?>
    <?php
        $ifex=\app\models\ChargesDetail::find()->where(["id_asiento"=>$modelo->id])->exists()
        ?>
    <?php if(!$ifex):?>
       <?php if($i==0){?>
        <h5 class="text-center"><b>Asiento Contable</b></h5>
        <?php }else{?>
        <h5 class="text-center"><b>Asiento Inventario</b></h5>
        <?php }?>
        <table border="0" cellpadding="0" cellspacing="0" style="width:100%;font-family: Arial;font-size:9pt">
            <tr>
                <td style="width:35%"><b>Fecha:</b></td>
                <td><?= $modelo->date?></td>
            </tr>
            <tr>
                <td><b>Código:</b></td>
                <td></td>
            </tr>
            <tr>
                <td><b>Glosa:</b></td>
                <td><?= $modelo->description?></td>
            </tr>
            <tr>
                <td><b>Gasto No Deducible:</td>
                <td><?= $modelo->nodeductible?'Si':'No'?></td>
            </tr>
        </table>
        <br>
        <b><u><i> Detalle del Asiento</i><u></b>
        <table border="1" cellpadding="0" cellspacing="0" style="width:100%;font-family: Arial;font-size:10pt">
            <thead>
            <tr>
                <th class="text-center" style="width:100%">Cuenta</th>
                <th class="text-right" style="width:100px;text-align: center;">Debe</th>
                <th class="text-right" style="width:100px;text-align: center;">Haber</th>
                <th class="text-center" style="width:200px">Centro de Costo</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $debit = 0;
            $credit = 0;
            //debe
            $list = AccountingSeatsDetails::find()->where(['accounting_seat_id' => $modelo->id])->andWhere(['>', 'debit', 0])->all();
            foreach ($list as $detail) {
                $debit += $detail->debit;
                ?>
                <tr>
                    <td><?= $detail->chartAccount->code . ' ' . $detail->chartAccount->slug ?></td>
                    <td style="text-align:right;">$<?= sprintf('%.2f', $detail->debit) ?></td>
                    <td style="text-align:right;"></td>


                                <td style="text-align:left;"><?= $detail->costCenter ? $detail->costCenter->name : '' ?></td>
                </tr>
                <?php
            }
            //haber
            $list = AccountingSeatsDetails::find()->where(['accounting_seat_id' => $modelo->id])->andWhere(['>', 'credit', 0])->all();
            foreach ($list as $detail) {
                $credit += $detail->credit;
                ?>
                <tr>
                    <td style="padding-left:60px;"><?= $detail->chartAccount->code . ' ' . $detail->chartAccount->slug ?></td>
                    <td style="text-align:right;"></td>
                    <td style="text-align:right;">$<?= sprintf('%.2f', $detail->credit) ?></td>

                                <td style="text-align:left;"><?= $detail->costCenter ? $detail->costCenter->name : '' ?></td>

                </tr>
                <?php
            }
            ?>
            <tr>
                <td style="text-align: right;">
                    <b>Total</b>
                </td>
                <td class="text-right">$<?= sprintf('%.2f', $debit) ?></td>
                <td class="text-right">$<?= sprintf('%.2f', $credit) ?></td>
                <td></td>
            </tr>
            </tbody>
        </table>
        <br>
        <br>



<?php $i=$i+1; ?>
<?php endif?>
<?php endforeach?>

<?php endif; ?>
<table border="0" cellpadding="5" cellspacing="4" style=" text-align: right ;padding:40px;width:100%;font-family: Arial;font-size:9pt">

    <tr>
        <td>
            <strong>Subtotal 12 %:   </strong> </td> <td> <div class="su"><?= sprintf('%.2f', $modelfin->subtotal12)?></td>
    </tr>
    <tr>
        <td>
            <strong>Subtotal 0%:   </strong> </td> <td> <div class="su"><?= sprintf('%.2f',$modelfin->subtotal0?:0) ?> </td>
    </tr>
    <tr>
        <td><strong>Iva: </strong> </td> <td> <div class="su"> <?=sprintf('%.2f',$modelfin->iva) ?></td></div>
    </tr>
    <?php if(is_null($modelfin->descuento)):?>
        <tr> <td> <strong>Descuento: </strong> </td> <div class="su">  <td><?= 0.00 ?></td></div></tr>
    <?php else:?>
        <tr> <td> <strong>Descuento: </strong> </td> <div class="su">  <td><?=sprintf($modelfin->descuento)?></td></div></tr>
    <?php endif?>
    <tr> <td> <strong>Total: </strong> </td> <td><div class="su"><?=sprintf($modelfin->total)?></td></div></tr>
    <tr> <td> <strong>Saldo: </strong> </td> <td><div class="su"><?=sprintf($modelfin->total-$sum) ?></td></div></tr>
</table>
<br>
<br>
<br>
<br>
<table border="0" cellpadding="0" cellspacing="0" style="width:100%;font-family: Arial;font-size:9pt">
    <tr>
        <td>
            ___________________________________<br><br>
            Elaborado por:<br><br>
            Cédula:
        </td>
        <td>
            ___________________________________<br><br>
            Aprobado por:<br><br>
            Cédula:
        </td>
        <td>
            ___________________________________<br><br>
            Revisado por:<br><br>
            Cédula:
        </td>
    </tr>
</table>
