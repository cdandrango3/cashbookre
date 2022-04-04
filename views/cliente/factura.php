<?php


use app\models\Person;
use kartik\date\DatePicker;
use yii\Bootstrap4;
use yii\bootstrap4\Modal;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\HeadFact */
/* @var $form ActiveForm */
$listData=ArrayHelper::map($ven,"id","name");
$listProduct=ArrayHelper::map($produc,"name","name");
$listPrecio=ArrayHelper::map($precio,"name","precio");
$listIva=ArrayHelper::map($precio,"name","product_iva_id");
$reteiva=\yii\helpers\Json::encode(ArrayHelper::map($retiva,"concat","id"));
$reteimp=\yii\helpers\Json::encode(ArrayHelper::map($retimp,"concat","id"));
$listcosto=ArrayHelper::map($precio,"name","costo");
$sales=ArrayHelper::map($salesman,"id","name");
$listtypepro=ArrayHelper::map($modeltype,"name","name");
$listruc=ArrayHelper::map($query,"id","name");
$prelist=\yii\helpers\Json::encode($listPrecio);
$prolist=\yii\helpers\Json::encode($listProduct);
$lcosto=\yii\helpers\Json::encode($listcosto);
$liva=\yii\helpers\Json::encode($listIva);
$authItemChild = Yii::$app->request->post('Person');
$auth = Yii::$app->request->post('HeadFact');
$request=Yii::$app->request->post('FacturaBody');
$model->f_timestamp=date("Y-m-d");
$account = \yii\helpers\Json::encode(ArrayHelper::map(\app\models\ChartAccounts::find()
    ->Select(["id,concat(code,' ',slug) as name"])
    ->alias('t')
    ->where(['(select count(*) from chart_accounts t2 where t2.parent_id=t.id)'=>0])->andWhere(['parent_id'=>13252])->andWhere(['institution_id'=>1])->asArray()->all(),'id', 'name'));
$codeiva= \yii\helpers\Json::encode(ArrayHelper::map(\app\models\ChartAccounts::find()
    ->Select(["id,concat(code,' ',slug) as name"])
    ->alias('t')
    ->where(['(select count(*) from chart_accounts t2 where t2.parent_id=t.id)'=>0])->andWhere(['parent_id'=>13264])->andWhere(['institution_id'=>1])->asArray()->all(),'id', 'name'));
?>


<?=
$this->registerCssFile('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', [
    'depends' => [\yii\web\JqueryAsset::className()]
])?>
<?=
$this->registerCssFile('https://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css', [
    'depends' => [\yii\web\JqueryAsset::className()]
])?>

<?php
$this->registerCss('
     .select12
     {
    width: 200px !important;
     }
     .select2{
  margin-top:16px;
}
');

?>
<div class="cliente-factura">
    <?php

    if(Yii::$app->session->hasFlash("error")){
        $c=Yii::$app->session->getFlash('error');
        foreach ($c as  $message) {
            foreach ($message as  $messa) {
                echo '<div class="alert alert-danger">' . $messa . '</div>';

            }

        }
    }
    ?>
    <?php $form = ActiveForm::begin(); ?>
    <div class="container">
        <div class="card ">
            <div class="card-head bg-primary p-4">
               <h4 >Datos de la factura</h4>
            </div>
            <div class="card-body p-4">
    <div class="row">
        <div class="col-6">

            <?= HTML::tag("label","Fecha de emisión")?>
            <?= DatePicker::widget([
                    'model'=>$model,
            'attribute' => 'f_timestamp',
            'name' => 'check_issue_date',
            'value' => $model->f_timestamp,
            'options' => ['placeholder' => 'Select issue date ...'],
            'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true
            ]
            ])?>
            <br>
            <?= $form->field($model, 'tipo_de_documento')->dropDownList(
                ['Cliente' => 'Cliente', 'Proveedor' => 'Proveedor'],["id" =>"tipodocu",'onchange'=>'
            $.post( "'.urldecode(Yii::$app->urlManager->createUrl('cliente/getdata?data=')).'"+$(this).val(), function( data ) {
              $( "select#dop1" ).html( data );
              console.log(data)
            });
        '])?>
            <?=$form->field($ven[0],"id")->dropDownList($listruc,['prompt'=>'Select...',"id"=>"dop1"])->label("Persona");?>
            <?= HTML::label("Vendedor","d",["id"=>"ven"])?>
            <?=$salesman?$form->field($salesman[0],"id_ven")->dropDownList($sales,['prompt'=>'Select...',"id"=>"vendedor"])->label(false):""?>

            <?= $form->field($model, 'Entregado')->checkBox(['label' => 'Entregado']);  ?>

        </div>

        <div class="col-6">
            <?= $form->field($model, 'n_documentos')->textInput(["id"=>"ndocu"])?>
            <?= $form->field($model, 'referencia') ?>
            <?= $form->field($model, 'orden_cv') ?>
            <?= $form->field($model, 'autorizacion') ?>

       </div>
        </div>
    </div>
        </div>
        <div class="form-group">

        </div>
    </div>
</div>
<div id="tabs">

    <ul>
        <li><a href="#tabs-1">Factura</a></li>
        <li><a href="#tabs-2">Retencion</a></li>
    </ul>
    <div id="tabs-1">
        <?php echo HTML::tag("a", "añadir detalle", ["value" => "ff", "id" => "añadir", "class" => "btn btn-primary text-white float-right mr-4"]); ?>
<table class="table table-dark">
    <thead>
    <th>Cantidad</th>
    <th> Producto </th>
    <th> Valor unitario </th>
    <th> Ret.IMP </th>
    <th> Ret.IVA </th>
    <th> Descuento %</th>
    <th> Valor final </th>
    <th> Eliminar </th>
    </thead>
    <tbody id="nuevo">
    </tbody>
</table>
    </div>
    <div id="tabs-2">
        <table class="table table-dark">
            <?= $form->field($retention, 'n_retencion')->textInput(["id"=>"ndocure"])->label("N` de Documento")?>
            <?= $form->field($retention, 'autorizacion')->textInput(["id"=>"autorirete"])->label("Autorizacion")?>

            <thead>
            <th>Retencion</th>
            <th>Tipo</th>
            <th>Codigo sri</th>
            <th>Base</th>
            <th>%</th>
            <th>Valor</th>
            </thead>
            <tbody id="ret">
            </tbody>
        </table>
    </div>
</div>
<br><br>
<div class="row">
    <div class="col-7">
        <td><?= $form->field($model3, 'description')->label("Descripcion")->textarea(['rows' => '6'])?></td>
    </div>
    <div class="col-5">
        <td><?= $form->field($model3, 'subtotal12')->label("subtotal 12%")->textInput(['readonly' => true ,'value' =>"" ,"id"=>"sub"]) ?></td>
        <td><?= $form->field($model3, 'subtotal0')->label("subtotal 0%")->textInput(['readonly' => true ,'value' =>"" ,"id"=>"sub0"]) ?></td>
        <td><?= $form->field($model3, 'descuento')->label("descuento")->textInput(['readonly' => true ,'value' =>"" ,"id"=>"desc"]) ?></td>
        <td><?= $form->field($model3, 'iva')->label("iva")->textInput(['readonly' => true ,'value' =>"" ,"id"=>"iva"]) ?></td>
        <td><?= $form->field($model3, 'total')->label("total")->textInput(['readonly' => true ,'value' =>"" ,"id"=>"total"]) ?></td>
    </div>
</div>
<?= Html::submitButton('Guardar', ['class' => 'btn btn-success float-right ','id'=>"buttonsubmit"]) ?>
<br>
<br>
</div><!-- cliente-factura -->
<?php ActiveForm::end(); ?>


<?php

Modal::begin([
    'title'=>'<h1 class="text-primary">Escoger Persona</h1>',
    'id'=>'modal2',
    'size'=>'modal-lg',

]);

Modal::end();

?>
<?php

Modal::begin([
    'title'=>'<h1 class="text-primary">Escoger Persona</h1>',
    'id'=>'modal',
    'size'=>'modal-lg',

]);

$models=New Person;
$model=$models::find()->select("ruc")->all();
echo $this->renderAjax("formclientrender",compact('model'));

Modal::end();
$this->registerJsFile('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('https://code.jquery.com/ui/1.11.3/jquery-ui.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
</div>
</div>
<script type="text/javascript">
    var count=0;
    cov=[]

    $(document).ready(function(){
        $('#personm').append('<a id="buscar" class="btn btn-primary">buscar</a>')
        $( "#tabs" ).tabs();
    })

    $('#buscar').click(function() {
        $('#modal').modal('show')
            .find('#modalContent')
    })
    $('#ndocu')
        .keypress(function (event) {
            if (event.which < 48 || event.which > 57 || this.value.length === 17) {
                return false;
            }
        });
    $('#ndocure')
        .keypress(function (event) {
            if (event.which < 48 || event.which > 57 || this.value.length === 17) {
                return false;
            }
        });
    var flag1 = true;
    $('#ndocu').keypress(function(e){
        if($(this).val().length == 3 || $(this).val().length == 7 && flag1) {
            $(this).val($(this).val()+"-");
            flag1 = true;
        }
    });
    $('#ndocure').keypress(function(e){
        if($(this).val().length == 3 || $(this).val().length == 7 && flag1) {
            $(this).val($(this).val()+"-");
            flag1 = true;
        }
    });

    $('#tipodocu').change(function(){
        tipo=$(this).val()
        if (tipo=="Proveedor") {
            $("#vendedor").hide()
            $("#vendedor").val("")
            $("#ven").hide()
                $(".preu").val("");
        }
        else{
            $("#vendedor").show()
            $(".preu").val("");
            $("#ven").show()
        }


        $.get('<?php echo Yii::$app->request->baseUrl. '/cliente/getdata' ?>',{data:tipo},function(data){

           datos=data;




        });
    })
    $('#listtype').change(function(){
        c=$(this).val();

            pro = '<?php echo $prolist ?>'
            dapro = JSON.parse(pro)
            $('.s').remove();
            for (i in dapro) {
                var c = '<option class="s" value="' + i + '">"' + i + '"</option>'
                $('.la').append(c);

        }
        })
$(añadir).click(function(){
count=count+1

      pro='<?php echo $prolist ?>'
    reteiva= '<?php echo $reteiva?>'
    reteimp= '<?php echo $reteimp?>'
    codeimp='<?php echo $account?>'
    codeiva='<?php echo $codeiva?>'
    console.log(reteimp)
    var reimp=JSON.parse(reteimp)
   var reiva=JSON.parse(reteiva)
    var codeim=JSON.parse(codeimp)
    var codeiv=JSON.parse(codeiva)
    dapro=JSON.parse(pro)
     var c='<tr id="int'+count+'">'
         c+='<td>'
       c+='<div class="form-group field-can"> <label class="control-label" for="facturabody-'+count+'-cant"></label><input style="width:40px"type="text" id="can'+count+'" class="form-control cant" name="FacturaBody['+count+'][cant]" value="" onkey="javascript:fields2()">'
      c+='</td>'
     c+='<td>'
    c+='<div class="form-group field-valo"><label class="control-label" for="valo"></label><select style="width: 100px;height: 100%"  id="'+count+'" class="la form-control" name="Product['+count+'][name]"> <option value="">Select...</option>'
        for(i in dapro){
         c+='<option class="s" value="'+i+'">"'+i+'"</option>'
        }
    c+='</td>'
    c+='<td>'
    c+='<div class="form-group field-idn"><label class="control-label" for="facturabody-'+count+'-precio_u"></label><input type="text" id="idn'+count+'" class="form-control preu" name="FacturaBody['+count+'][precio_u]" value=""><div class="help-block"></div> </div> '
    c+='</td>'
    c+='<td>'
    c+= '<div class="form-group field-idn"><select id="retimp-'+count+'" class="form-control js-retimp m-5" name="state"><option value="">Select...</option>'
    for(i in reimp){
        c+='<option class="s" value="'+reimp[i]+'">"'+i+'"</option>'
    }
    c+='</td>'
    c+='<td>'
    c+= '<div class="form-group field-idn"><select id="retiva-'+count+'" class="js-retiva m-5" name="state"><option value="">Select...</option>'
    for(i in reiva){
        c+='<option class="s" value="'+reiva[i]+'">"'+i+'"</option>'
    }
    c+='</td>'
    c+='<td>'
    c+='<div class="form-group field-desc"><label class="control-label" for="facturabody-+count+-desc"></label><input type="text" id="desc'+count+'" class=" form-control desc" name="FacturaBody['+count+'][desc]" value="">'
    c+='</td>'
    c+='<td>'
    c+='<div class="form-group field-valtotal"><label class="control-label" for="facturabody-+count+-precio_total"></label><input type="text" id="valtotal'+count+'" class="form-control  g" name="FacturaBody['+count+'][precio_total]" value="" readonly>'
    c+='</td>'
    c+='<td>'
    c+='<button class="btn btn-danger mdwsdsdsft-3 remove" id="'+count+'">Eliminar</button>'
    c+='</td>'
    c+='</tr>'
    $('#hola').click(function(){

    });

    $('#actionmodal').click(function(){
        $('#modal2').show()
    })

$(document).on('keyup','.preu',function(){
    precio=$(this).val()
    te=$(this).attr("id");
    h=te.substring(3);
    cant=$('#can'+h).val();
    $('#valtotal'+h).val(cant*precio);
    sum=0;
    c=0;
    sumiv=0;
    sumn=0;
    iva=JSON.parse('<?php echo $liva?>');
    item=[];
    $('.la').each(function(){
        item.push($(this).val())
    })
    $('.g').each(function(){
        if(iva[item[c]]==12){
            sumiv=sumiv+parseFloat($(this).val());
        }
        if(iva[item[c]]==0){
            sumn=sumn+parseFloat($(this).val());
        }
        c=c+1;
    })
    $('#sub0').val(round(sumn))
    $('#sub').val(round(sumiv))
    iva=sumiv*0.12;
    des=0;
    total=sumiv+sumn+iva+des;
    $('#iva').val(round(iva))
    $('#des').val(round(iva))
    $('#total').val(round(total))

})

    $(document).on('click','.remove',function(){
        id=$(this).attr("id");
        $("#int"+id).remove();
        if ($("#ret-"+id).length > 0) {
            $("#ret-"+id).remove();

        }
        if ($("#reti-"+id).length > 0) {
            $("#reti-"+id).remove();

        }

        sum=0;
        c=0;
        sumiv=0;
        sumn=0;
        iva=JSON.parse('<?php echo $liva?>');
        item=[];
        $('.la').each(function(){
            item.push($(this).val())
        })
        $('.g').each(function(){
            if(iva[item[c]]==12){
                sumiv=sumiv+parseFloat($(this).val());
            }
            if(iva[item[c]]==0){
                sumn=sumn+parseFloat($(this).val());
            }
            c=c+1;
        })
        $('#sub0').val(sumn)
        $('#sub').val(sumiv)
        iva=sumiv*0.12;
        des=0;
        total=sumiv+sumn+iva+des;
        $('#iva').val(iva)
        $('#des').val(iva)
        $('#total').val(total)
    })
    $('#nuevo').append(c);
    $('.js-retimp').select2({width:'100px',dropdownCssClass : 'select12'});

    $('.js-retimp').on('change', function() {
        datas= $(this).attr('id').split('-')[1];
        console.log(datas)
        single= $(this).val();
        $.ajax({
            method: "POST",
            data: {single:single},
            url: '<?php echo Yii::$app->request->baseUrl. '/cliente/getretention'?>',
            success: function (data) {
                slug=JSON.parse(data)
                console.log(slug)
                var tab='<tr id="ret-'+datas+'" class="tr1">'
                tab+='<td id="tipo-'+datas+'">'
                tab+= '<div class="form-group field-idn"><select id="timp-'+datas+'" class="js-rimp js-t" name="state"><option value="">Select...</option>'
                for(i in codeim){
                    tab+='<option class="s" value="'+i+'">"'+codeim[i]+'"</option>'
                }
                tab+='</td>'
                tab+='<td id="sri-'+datas+'">'
                tab+='Impuesto a la renta'
                tab+='</td>'
                tab+='<td id="tipo-'+datas+'">'
                tab+=slug.codesri
                tab+='</td>'
                tab+='<td id="base-'+datas+'">'
                tab+= `<input id="bases-${datas}" type="text" value=${($("#valtotal"+datas).val())|| 0} readonly />`
                tab+='</td>'
                tab+='<td id="%-'+datas+'">'
                tab+= `<input id="porcen-${datas}" type="text" value=${slug.percentage|| 0} readonly />`
                tab+='</td>'
                tab+='<td id="valor-'+datas+'">'
                tab+= `<input class="js-val" id="val-${datas}" type="text" value=${($("#valtotal"+datas).val()*parseFloat(slug.percentage))/100 || 0} readonly />`
                tab+='</td>'
                tab+='</td>'
                tab+='</tr>'
                if ($("#ret-"+datas).length > 0) {
                    $("#ret-"+datas).remove();
                    $("#ret").append(tab)

                }
                else{
                    $("#ret").append(tab)
                }
                $(".js-rimp").select2({width: '100%',height:"100%"});
                $("#timp-"+datas).val(slug.id_chart).trigger('change');
            },
            error: function (err) {

                //do something else
                console.log(err);
                if(err){
                    alert('It works!');
                }

            }

        })
    })
    $('.js-retiva').select2({width: '100px',dropdownCssClass:'select2'});
    $('.js-retiva').on('change', function() {
        datas= $(this).attr('id').split('-')[1];
        console.log(datas)
        single= $(this).val();
        $.ajax({
            method: "POST",
            data: {single:single},
            url: '<?php echo Yii::$app->request->baseUrl. '/cliente/getretention'?>',
            success: function (data) {
                slug=JSON.parse(data)
                iva=$("#valtotal"+datas).val()*parseFloat(12)/100 || 0
                console.log(slug)
                var tab='<tr id="reti-'+datas+'" class="tr1">'
                tab+='<td id="tipo-'+datas+'">'
                tab+= '<div class="form-group field-idn"><select id="tiva-'+datas+'" class="js-riva js-t" name="state"><option value="">Select...</option>'
                for(i in codeiv){
                    tab+='<option class="s" value="'+i+'">"'+codeiv[i]+'"</option>'
                }
                tab+='</td>'
                tab+='<td id="sri-'+datas+'">'
                tab+='IVA'
                tab+='</td>'
                tab+='<td id="tipo-'+datas+'">'
                tab+=`<p>${slug.codesri}</p>`
                tab+='</td>'
                tab+='<td id="base-'+datas+'">'
                tab+= `<input id="bases-${datas}" type="text" value=${iva || 0} readonly />`
                tab+='</td>'
                tab+='<td id="%-'+datas+'">'
                tab+= `<input id="porcen-${datas}" type="text" value=${slug.percentage|| 0} readonly />`
                tab+='</td>'
                tab+='<td id="valor-'+datas+'">'
                tab+= `<input class="js-val" id="val2-${datas}" type="text" value=${round(iva*parseFloat(slug.percentage)/100) || 0} readonly />`
                tab+='</td>'
                tab+='</td>'
                tab+='</tr>'
                if ($("#reti-"+datas).length > 0) {
                    $("#reti-"+datas).remove();
                    $("#ret").append(tab)

                }
                else{
                    $("#ret").append(tab)
                }
                $(".js-riva").select2({width: '100%',height:"100%"});
                $("#tiva-"+datas).val(slug.id_chart).trigger('change');
            },
            error: function (err) {

                //do something else
                console.log(err);
                if(err){
                    alert('It works!');
                }

            }

        })

    })

})



function calcular(){
    tip= $('#tipodocu').val();
    if(tip=="Cliente"){
        f=JSON.parse('<?php echo $prelist?>');

    }
    else {
        if (tip == "Proveedor") {
            f = JSON.parse('<?php echo $lcosto?>');

        }
    }

    cost=JSON.parse('<?php echo $lcosto?>');
    $('#idn'+h+'').val(f[d]);
    co=cost[d]
    cost=$('#can'+h).val()*co
    suma=0;
    cov.push(cost);
    for (const element of cov){
        suma=suma+parseFloat(element)
        cost=JSON.parse('<?php echo $lcosto?>');
    }
    $('#pre').val(suma)
    re=($('#can'+h).val())*($('#idn'+h).val())
    $('#valtotal'+h).val(re);
    sum=0;
    c=0;
    sumiv=0;
    sumn=0;
    iva=JSON.parse('<?php echo $liva?>');
    item=[];
    $('.la').each(function(){
        item.push($(this).val())
    })

    $('.g').each(function(){
        if(iva[item[c]]==12){
            sumiv=sumiv+parseFloat($(this).val());
        }
        if(iva[item[c]]==0){
            sumn=sumn+parseFloat($(this).val());
        }
        c=c+1;
    })
    $('#sub0').val(round(sumn))
    $('#sub').val(round(sumiv))
    iva=sumiv*0.12;
    des=0;
    total=sumiv+sumn+iva+des;
    $('#iva').val(round(iva))
    $('#des').val(round(iva))
    $('#total').val(round(total))
}
    $(document).on('change','.la',function(){
        h=$(this).attr("id");
        d=$(this).val();
        calcular();
    })
    $(document).on('keyup','.cant',function(){
        precio=$(this).val()
        te=$(this).attr("id");
        h=te.substring(3);
        cant=$('#idn'+h).val();
        $('#valtotal'+h).val(cant*precio);
        sum=0;
        c=0;
        sumiv=0;
        sumn=0;
        iva=JSON.parse('<?php echo $liva?>');
        item=[];
        $('.la').each(function(){
            item.push($(this).val())
        })
        $('.g').each(function(){
            if(iva[item[c]]==12){
                sumiv=sumiv+parseFloat($(this).val());
            }
            if(iva[item[c]]==0){
                sumn=sumn+parseFloat($(this).val());
            }
            c=c+1;
        })
        $('#sub0').val(round(sumn))
        $('#sub').val(round(sumiv))
        iva=sumiv*0.12;
        des=0;
        total=sumiv+sumn+iva+des;
        $('#iva').val(round(iva))
        $('#des').val(round(iva))
        $('#total').val(round(total))
        if ($("#ret-"+h).length > 0) {
           $("#bases-"+h).val($('#valtotal'+h).val());
           base=$("#bases-"+h).val()
            percent=parseFloat($("#porcen-"+h).val())/100
            console.log(base,$("#porcen-"+h).val())
            $("#val-"+h).val(round(base*percent))
        }
    })
    $(document).on('keyup','.desc',function(){
        te=$(this).attr("id");
        h=te.substring(4);

        preciou=$('#idn'+h+'').val();
        cant=$('#can'+h).val();
        $('#valtotal'+h).val(preciou*cant);
        val=$('#valtotal'+h).val();
        desc=val*($(this).val())/100;
        val=$('#valtotal'+h).val();
        valf=val-desc;
        $('#valtotal'+h).val(valf);
        sum=0;

        $('.g').each(function(){
            sum=sum+parseFloat($(this).val());
        })

        $('')
        $('#sub').val(sum)
        iva=sum*0.12;
        des=0;
        total=sum+iva+des;
        $('#iva').val(round(iva))
        $('#desc').val(round(desc))
        $('#total').val(round(total))

    })
    $('#añadir')
$('#buttonsubmit').click(function(){
    var f=false;
    cantidad=[];
    preciou=[];
    pro=[];
    preciot=[];
    retimp=[];
    retinv=[];
    codeimp=[]
    arraysd=[]
    $('.cant').each(function(){
        cantidad.push($(this).val())
    })
    $('.la').each(function(){
    pro.push($(this).val())
    })
    $('.preu').each(function(){
    preciou.push($(this).val())
    })
    $('.g').each(function(){
    preciot.push($(this).val())
    })
    $('.js-retimp').each(function(){
        retimp.push($(this).val() || null)
    })
    $('.js-retiva').each(function(){
        retinv.push($(this).val() || null)
    })
    $('.js-t').each(function(){
        codeimp.push($(this).val() || null)
    })
    $('.js-val').each(function(){
        arraysd.push($(this).val() || null)
    })
    numero=$('#ndocure').val() || null
    autorirete=$('#autorirete').val() || null
    valcodeimp=zip(codeimp,arraysd)

    console.log(valcodeimp)
    n_docu= $('#ndocu').val();
    if (cantidad.length > 0){
    $.ajax({
        method: "POST",
        data: { cantidad:cantidad,produc:pro,preciou:preciou,precioto:preciot,ndocumento:n_docu,retimp:retimp,retinv:retinv,codeimp:JSON.stringify(valcodeimp),nret:numero,autorite:autorirete},
        url: '<?php echo Yii::$app->request->baseUrl. '/cliente/guardarproceso' ?>',
        success: function (data) {
            console.log(data);
        },
        error: function (err) {

            //do something else
            console.log(err);
            if(err){
                alert('It works!');
            }

        }

    })}
    else{
        return false
    }

})
    function round(num) {
        var m = Number((Math.abs(num) * 100).toPrecision(15));
        return Math.round(m) / 100 * Math.sign(num);
    }
    function zip(arr1,arr2,out={}){
        arr1.map( (val,idx)=>{ out[val] = arr2[idx]; } );
        return out;
    }

</script>

