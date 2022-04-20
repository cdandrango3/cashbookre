<?php

use app\models\FacturaBody;
use app\models\Product;
use app\models\Retention;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Consultar Documento Físico';
$this->params['breadcrumbs'][] = $this->title;
$producto=New Product;
$this->registerCss("");
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


    <div class="row">
        <div class="col text-right " style="margin-right: 60px">
            <div class="btn-group" role="group">
                <a href='<?=Url::to(['cliente/factura'])?>' class="btn btn-default" title="Crear Factura" data-toggle="tooltip"> <i class="fas fa-plus"></i></a>
            <?php if($model->tipo_de_documento=="Cliente"){?>
                <a href='<?=Url::to(['cobros/cobros', 'id' => $model->n_documentos])?>' class="btn btn-default" title="Registro de cobros" data-toggle="tooltip"> <i class="fas fa-donate"></i></a>

            <?php }else{?>
                <a href='<?=Url::to(['cobros/cobros', 'id' => $model->n_documentos])?>' class="btn btn-default" title="Registro de pagos" data-toggle="tooltip"> <i class="fas fa-donate"></i></a>
            <?php }?>
            <div class="dropdown show">
                <a class="btn btn-default dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-file-pdf text-red"></i>
                </a>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a href='<?=Url::to(['cliente/pdfview', 'id' => $model->n_documentos,"ischair"=>true])?>'class="btn btn-default" target="_blank" title="Exportar a pdf" data-toggle="tooltip"> Pdf con asiento</a>
                    <a href='<?=Url::to(['cliente/pdfview', 'id' => $model->n_documentos,"ischair"=>false])?>'class="btn btn-default" target="_blank" title="Exportar a pdf" data-toggle="tooltip"> Pdf sin asiento</a>
                    <a href='<?=Url::to(['cliente/matrixial', 'id' => $model->n_documentos,"ischair"=>false])?>'class="btn btn-default" target="_blank" title="Exportar a pdf" data-toggle="tooltip"> A matricial</a>

                </div>

            </div>
                </div>
        </div>
        </div>


<br>
<div class="container">
    <div class="card">
        <div class="card-head bg-primary p-2">
            <div class="container">
                <h3>Datos de la Factura</h3>
            </div>
        </div>
        <div class="card-body">

            <table border="0" cellpadding="5" cellspacing="4" style=" padding:40px;width:100%;font-family: Arial;font-size:9pt">
                <tr>
                    <td style="width:35%"><b>Fecha de emisión:</b></td>
                    <td><?= Yii::$app->formatter->asDatetime($model->f_timestamp,"yyyy-MM-dd")?></td>
                <tr>
                    <td style="width:35%"><b>N° de Documento:</b></td>
                    <td> Fac <?= $model->n_documentos?></td>
                </tr>
                <tr>
                    <td style="width:35%"><b>Referencia</b></td>
                    <td> <?= $model->referencia?></td>
                </tr>
                <tr>
                    <td style="width:35%"><b>Autorización</b></td>
                    <td> <?= $model->autorizacion?></td>
                </tr>
                <tr>
                    <td style="width:35%"><b>Persona</b></td>
                    <td><?=$personam->name?></td>
                </tr>
                <tr>
                    <?php if(!is_null($salesman)):?>
                        <td style="width:35%"><b>Vendedor</b></td>
                        <td><?=$salesman->name?></td>
                    <?php endif?>
                </tr>
                </table>
    </div>
    </div>
    </div>
<br><br><br>
<div class="container">
    <div class="card">
        <div class="card-body">
          <table class="table table-striped table-bordered">
              <thead >
              <tr>
              <td>Cantidad</td>
              <td> Producto </td>
              <td> Valor unitario </td>
              <td> Valor final </td>
              </tr>
              </thead>
          <tbody>
          <?php foreach($model2 as $mbody): ?>
          <?php $pro=$producto::findOne($mbody->id_producto)?>
          <tr>
              <td><?=$mbody->cant ?></td>
              <td><?=$pro->name?></td>
              <td><?=sprintf('%.2f',$mbody->precio_u)?></td>
              <td><?=sprintf('%.2f',$mbody->precio_total)?></td>

          </tr>
          <?php endforeach ?>
          </tbody>

          </table>
        </div>
    </div>

</div>
<div class="container">
    <div class="card p-2">
    <div class="row">
        <div class="col-7">

        </div>
        <div class="col"></div>
        <table border="0" cellpadding="5" cellspacing="4" style=" padding:40px;width:100%;font-family: Arial;font-size:9pt">
            <tr>
                <td style="width:35%"><b>Descripción:</b></td>
                <td><?= $modelfin->description ?></td>
            <tr>
        </table>
                <table border="0" cellpadding="5" cellspacing="4" style=" padding:40px;width:100%;font-family: Arial;font-size:9pt">

                    <table border="0" cellpadding="5" cellspacing="4" style=" text-align: right ;padding:100px;width:100%;font-family: Arial;font-size:9pt">

                        <tr>
                            <td>
                                <strong>Subtotal 12%:   </strong> </td> <td> <div class="su"><?=sprintf('%.2f',$modelfin->subtotal12)?></td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Subtotal 0%:   </strong> </td> <td> <div class="su"><?=sprintf('%.2f',$modelfin->subtotal0?:0)?> </td>
                        </tr>
                        <tr>
                            <td><strong>Iva: </strong> </td> <td> <div class="su"> <?=sprintf('%.2f',$modelfin->iva )?></td></div>
        </tr>
        <?php if(is_null($modelfin->descuento)):?>
            <tr> <td> <strong>Descuento: </strong> </td> <div class="su">  <td><?=sprintf( '%.2f',0 )?></td></div></tr>
        <?php else:?>
            <tr> <td> <strong>Descuento: </strong> </td> <div class="su">  <td><?=sprintf('%.2f',$modelfin->descuento)?></td></div></tr>
        <?php endif?>
        <tr> <td> <strong>Total: </strong> </td> <td><div class="su"><?=sprintf('%.2f',$modelfin->total) ?></td></div></tr>
    <tr> <td> <strong>Saldo: </strong> </td> <td><div class="su"><?=sprintf('%.2f',$modelfin->total-$sum) ?></td></div></tr>
    </table>

                </table>
            </div>
        </div>
    </div>
</div>
