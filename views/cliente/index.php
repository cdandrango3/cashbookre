<?php
    use app\models\Facturafin;
    use yii\grid\GridView;
    use yii\helpers\Html;
    use app\models\Person;
    use yii\helpers\Url;
    use yii\widgets\LinkPager;
    use kartik\dialog\Dialog;
    /* @var $modelhead app\models\head_fact*/
    $f=New Facturafin;
    $this->title = 'Consultar Documento Físico';
    $this->params['breadcrumbs'][] = $this->title;
    $c=$_GET['tipos'];
if(Yii::$app->session->hasFlash("error")) {
    $ca = Yii::$app->session->getFlash('error');
    foreach ($ca as $casif) {

        echo '<div class="alert alert-danger">' . $casif . '</div>';
    }
}
?>
    <a href='<?=Url::to(['cliente/factura'])?>' class="btn btn-success float-right" title="Crear Factura" data-toggle="tooltip"> <i class="fas fa-plus"></i></a>
    <br>
    <div class="container m-4">
        <div class="card">
            <div class="card-header bg-primary">
                Buscar
            </div>
            <div class="card-body mr-4"/>
                <?= $this->render('_formsearch', ['modelhead' => $modelhead]) ?>
            </div>
        </div>
    </div>

    <div class="container">
        <table class="table">
            <thead class="table table-dark">
                <tr>
                    <td>Acciones</td>
                    <td>Emision</td>
                    <td>Documento</td>
                    <td>Neto</td>
                    <td>Iva</td>
                    <td>Total</td>
                </tr>
            </thead>
            <tbody class="table table-light" id="ver">
                <?php foreach($headfac as $fac):?>
                <?php $total=$f::findOne(['id_head'=>$fac->n_documentos])?>
                <?php if(!is_null($total)):?>
                <tr>
                    <td>
                        <div class="row">
                            <div class="col-4">
                                <a class="btn btn-primary" id="edit" title="Editar" data-request-method = "POST" data-toggle="tooltip"
                                data-confirm-title = "Estas seguro" data-confirm-message = "Estas seguro de querer eliminar completamente este asiento"  role = "modal-remote" href='<?=Url::to(['cliente/editar','id' => $fac->n_documentos])?>'> <i class="fas fa-edit"></i></a>
                            </div>
                            <div class="col-4">
                                <a style="color:white; important!"
                                    role = "modal-remote" title = "Eliminar asiento" data-request-method = "POST" data-toggle = "tooltip"
                                    data-confirm-title = "Estas seguro" data-confirm-message = "Estas seguro de querer eliminar completamente este asiento"
                                    class="btn btn-danger btn" onclick="return confirm('¿Estas seguro que quieres eliminar esta factura')"  href="<?= Url::to(['cliente/delete','id' => $fac->n_documentos]) ?>">
                                    <span class="fas fa-trash"></span>
                                </a>
                            </div>
                        </div>
                    </td>
                    <td><?=Yii::$app->formatter->asDate($fac->f_timestamp, 'php:Y-m-d');?></td>
                    <td>
                        <?= HTML::a("Fac " .  $fac->n_documentos,Url::to(['cliente/viewf', 'id' => $fac->n_documentos]))?>
                    </td>
                    <td>
                        <?= $total->subtotal12?>
                    </td>
                    <td><?= $total->iva?></td>
                    <td><?= $total->total?></td>
                </tr>
                <?php endif?>
                <?php endforeach ?>
            </tbody>
        </table>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <?php echo LinkPager::widget(['pagination'=>$pages])?>
            </ul>
        </nav>
    </div>
    <?php
        $js = <<< JS
        $('#Tipo').change(function(){
        tipo=$(this).val();
        c=$("#nfac").val();
        p=$("#personas").val();
        ajax(c,p,tipo)
        });
        $('#nfac').keyup(function(){
        c=$(this).val();
        p=$("#personas").val();
        tipo=$("#Tipo").val();
        ajax(c,p,tipo)
        });
        $('#personas').change(function(){
        c=$(this).val();
        p=$("#nfac").val();
        tipo=$("#Tipo").val();
        ajax(p,c,tipo)
        });
        function ajax(c,p,t){
        $.ajax({
              method: "POST",             
               url: '/web/cliente/buscarf?fil='+c+'&&per='+p+'&&tipo='+t,
               data: { tipo:$('#nfac').val() },
            success: function(data) {
                $("#ver").html(data)        
            }
        })
        }
        JS;
        $this->registerJs($js)
    ?>