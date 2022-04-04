<?php

use app\models\Product;

$producto=New Product;
$get=$_GET["ischair"];

?>

<table border="0" cellpadding="0" cellspacing="3" style="width:100%;font-family: Arial;font-size:9pt;padding-left: 70px">

    <tr>
        <td>Quito &nbsp; <?=  Yii::$app->formatter->asDatetime($model->f_timestamp,"yyyy-MM-dd")?></td>
    <tr>
        <td><?=$personam->name?></td>
        <td style="padding-left: 210px">0387487328738487</td>
    </tr>
    <tr>
        <td> Yaruqui Barrio la victoria</td>
        <td style="padding-left: 210px"> 0387487328738487 </td>
    </tr>
</table>

<br><br><br>
<div class="container">

    <table border="0" cellpadding="4" cellspacing="0" style="width:100%;font-family: Arial;font-size:9pt">
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
<div style = " position:absolute; z-index: 99999; bottom:0; margin:40px">
    <table style="text-align: right ; width:100%;font-family: Arial;font-size:9pt">

        <tr>
            <td class="fin">
                 <?=$modelfin->subtotal12?> </td>
            </tr>
        <tr>
            <td class="fin">
                <?=$modelfin->subtotal0?:0?> </td>
        </tr>
        <tr>
            <td class="fin">
                <?=$modelfin->iva?> </td>
        </tr>
        <tr>
            <td class="fin">
                <?=$modelfin->total?>
            </td>
        </tr>
    </table>

</div>

