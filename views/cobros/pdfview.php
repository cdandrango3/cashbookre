<?php


use app\models\AccountingSeatsDetails;
use app\models\ChartAccounts;
use app\models\Person;

$person=Person::findOne(["id"=>$model2->person_id]);
$chart_account=ChartAccounts::findOne(["id"=>$charge->chart_account]);
$isas=$_GET["isbody"];

?>

<table border="0" cellpadding="0" cellspacing="0" style="width:100%;font-family: Arial;font-size:9pt">

    <tr>
        <td>
            <div><h4><?=$person->commercial_name?></h4></div></td>
        </td>
        <td>
            <div align="right"> &nbsp;&nbsp; &nbsp;&nbsp;<?= $model2->type_charge ?></div></td>


    </tr>

    <tr>
        <td>
            &nbsp; &nbsp;        &nbsp;           &nbsp;   &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
        </td>
        <td>
            <div align="right">Fecha de impresion:   <?= date('d-m-Y');?></div></td>

    </tr>


</table>







<br>
<div align="center">Comprobante de Ingreso: #<?= $charge->comprobante ?><h1></h1></div>
<table border="0" cellpadding="0" cellspacing="0" style="width:100%;font-family: Arial;font-size:9pt">
    <tr>
        <td style="width:35%"><b>Fecha de emisión:</b></td>
        <td><?= $modelo->date?></td>
    <tr>
        <td style="width:35%"><b>Persona:</b></td>
        <td><?= $person->name?></td>
    </tr>
    <tr>
        <td style="width:35%"><b>N de Comprobante:</b></td>
        <td><?= $charge->comprobante?></td>
    </tr>
    <tr>
        <td style="width:35%"><b>Cuenta Contable:</b></td>
        <td><?= $chart_account->slug?></td>
    </tr>
    <tr>
        <td style="width:35%"><b>El valor de </b></td>
        <td><?=  sprintf('%.2f', $charge->amount) ?> $</td>
    </tr>


</table>
<br>
<br>
<table border="1" cellpadding="4" cellspacing="0" style="width:100%;font-family: Arial;font-size:10pt">
    <thead>
    <tr>
        <td>Documentos</td>
        <td>Concepto</td>
        <td>Valor Doc</td>
        <td>A Cobrar</td>
        <td>Saldo Act</td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><?= $model2->n_document?></td>
        <td> transfer</td>
        <td><?= $charge->balance ?></td>
        <td><?= $charge->saldo ?></td>
        <td><?= $charge->amount ?></td>
    </tr>
    <tr>
        <td colspan="3"><div align="right">Total</div></td>
        <td><?=$charge->saldo?></td>
        <td><?=$charge->amount?></td>
    </tr>
    </tbody>

</table>
<?php if($isas):?>
    <br>
    <b><u><i> Detalle del Asiento</i><u></b>
    <table border="1" cellpadding="2" cellspacing="0" style="width:100%;font-family: Arial;font-size:10pt">
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


<?php endif?>
<br><br><br><br><br>
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
