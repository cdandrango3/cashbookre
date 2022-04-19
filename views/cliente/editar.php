<?php


use app\models\Person;
use app\models\Product;
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
$producto=New Product;
$cast=[];

foreach($dbo as $do){
$pro = $producto::findOne($do->id_producto);
$cast[$pro->id]=$pro->name;
}

$listData=ArrayHelper::map($ven,"name","name");
$listProduct=ArrayHelper::map($produc,"name","name");
$listPrecio=ArrayHelper::map($precio,"name","precio");
$listIva=ArrayHelper::map($precio,"name","product_iva_id");
$listpbody=ArrayHelper::map($dbo,"id","cant");
$listdbo=\yii\helpers\Json::encode($dbo);
yii::debug($listdbo);
$listcosto=ArrayHelper::map($precio,"name","costo");
$listtypepro=ArrayHelper::map($modeltype,"name","name");
if($head_fact->tipo_de_documento=="Cliente") {
    $listruc = ArrayHelper::map($query, "id", "name");
}
else{
    $listruc = ArrayHelper::map($providers, "id", "name");
}
$prelist=\yii\helpers\Json::encode($listPrecio);
$prolist=\yii\helpers\Json::encode($listProduct);
$lcosto=\yii\helpers\Json::encode($listcosto);
$liva=\yii\helpers\Json::encode($listIva);
$n_pro=\yii\helpers\Json::encode($cast);
yii::debug($n_pro);
$authItemChild = Yii::$app->request->post('Person');
$auth = Yii::$app->request->post('HeadFact');
$request=Yii::$app->request->post('FacturaBody');
?>

<div class="cliente-factura">
    <?php $form = ActiveForm::begin(); ?>
    <div class="container">
        <div class="card ">
            <div class="card-head bg-primary p-4">
               <h4 >Datos de la factura</h4>
            </div>
            <div class="card-body p-4">
    <div class="row">
        <div class="col-6">

            <?php $model->f_timestamp=$head_fact->f_timestamp?>
            <?= Html::label('Fecha de la emision')?>
            <?= DatePicker::widget([
                'model'=>$model,
                'attribute' => 'f_timestamp',
                'name' => 'check_issue_date',
                'value' => $head_fact->f_timestamp,
                'options' => ['placeholder' => 'Select issue date ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true
                ]
            ])?>;

            <?=$form->field($ven[0],"id")->dropDownList($listruc,['prompt'=>'Select...',"id"=>"dop1",'options' => [$head_fact->id_personas => ['selected'=>true]]])->label("Persona");?>

            <?=$form->field($ven[0],"name")->dropDownList($listData,['prompt'=>'Select...',"id"=>"vendedor"])->label("vendedor");?>
            <?= $form->field($model, 'Entregado')->checkBox(['checked' => $head_fact->Entregado,'label' => 'entregado']);  ?>

        </div>

        <div class="col-6">
            <?= $form->field($model, 'n_documentos')->textInput(["id"=>"ndocu","value"=>$head_fact->n_documentos])?>
            <?= $form->field($model, 'referencia')->textInput(["id"=>"ndocu","value"=>$head_fact->referencia]) ?>
            <?= $form->field($model, 'orden_cv')->textInput(["id"=>"ndocu","value"=>$head_fact->orden_cv]) ?>
            <?= $form->field($model, 'autorizacion')->textInput(["id"=>"ndocu","value"=>$head_fact->autorizacion]) ?>
            <?= $form->field($model, 'tipo_de_documento')->dropDownList(
                ['Cliente' => 'Cliente', 'Proveedor' => 'Proveedor'],["id" =>"tipodocu",'onchange'=>'
            $.post( "'.urldecode(Yii::$app->urlManager->createUrl('cliente/getdata?data=')).'"+$(this).val(), function( data ) {
              $( "select#dop1" ).html( data );
              console.log(data)
            });
        ','options' => [$head_fact->tipo_de_documento => ['selected'=>true]]])?>
       </div>
        </div>
    </div>
        </div>
        <div class="form-group">

        </div>
    </div>
</div>
<?php echo HTML::tag("a", "añadir detalle", ["value" => "ff", "id" => "añadir", "class" => "btn btn-primary float-right mr-4"]); ?>
<table class="table table-dark">
    <thead>
    <th>Cantidad</th>
    <th> Producto </th>
    <th> Valor unitario </th>
    <th> Descuento</th>
    <th> Valor final </th>
    <th> Eliminar </th>
    </thead>
    <tbody id="nuevo">


    </tbody>
</table>
<div class="row">
    <div class="col-7">

    </div>
    <div class="col-5">
        <?= $form->field($produ, 'costo')->label("subtotal")->textInput(['value' =>"" ,"id"=>"pre",'type'=>"hidden"]) ?>
        <td><?= $form->field($model3, 'subtotal12')->label("subtotal 12%")->textInput(['readonly' => true ,'value' =>$dfin->subtotal12 ,"id"=>"sub"]) ?></td>
        <td><?= $form->field($model3, 'subtotal0')->label("subtotal 0%")->textInput(['readonly' => true ,'value' =>$dfin->subtotal0 ,"id"=>"sub0"]) ?></td>
        <td><?= $form->field($model3, 'descuento')->label("descuento")->textInput(['readonly' => true ,'value' =>$dfin->descuento ,"id"=>"desc"]) ?></td>
        <td><?= $form->field($model3, 'iva')->label("iva")->textInput(['readonly' => true ,'value' =>$dfin->iva ,"id"=>"iva"]) ?></td>
        <td><?= $form->field($model3, 'total')->label("total")->textInput(['readonly' => true ,'value' =>$dfin->total ,"id"=>"total"]) ?></td>

    </div>
    <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary mb-5 float-right','id'=>"buttonsubmit",'data' => [

    ]]) ?>
</div>




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

?>



<script type="text/javascript">
    var count=0;
    cov=[]

    $(document).ready(function(){
        tip= $('#tipodocu').val();
        console.log(tip)
        if(tip=="Cliente"){
            f=JSON.parse('<?php echo $prelist?>');
            $("#vendedor").show()
            $("#ven").show()

        }
        else {
            if (tip == "Proveedor") {
                f = JSON.parse('<?php echo $lcosto?>');
                $("#vendedor").hide()
                $("#vendedor").val("")
                $("#ven").hide()
            }
        }





        $('#personm').append('<a id="buscar" class="btn btn-primary">buscar</a>')
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

                console.log(c)
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

            console.log(sumn);
            console.log(sumiv);
            iva=sumiv*0.12;
            des=0;
            total=sumiv+sumn+iva+des;
            $('#iva').val(round(iva))
            $('#des').val(round(iva))
            $('#total').val(round(total))
            console.log(h)

        })
        $(document).on('click','.remove',function(){
            id=$(this).attr("id");


            $("#int"+id).remove();
            sum=0;
            c=0;
            sumiv=0;
            sumn=0;
            iva=JSON.parse('<?php echo $liva?>');
            item=[];
            $('.la').each(function(){
                item.push($(this).val())
            })
            console.log(item[0]);
            $('.g').each(function(){

                console.log(c)
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

            console.log(sumn);
            console.log(sumiv);
            iva=sumiv*0.12;
            des=0;
            total=sumiv+sumn+iva+des;
            $('#iva').val(iva)
            $('#des').val(iva)
            $('#total').val(total)
        })

    })
    $(document).on('keyup','.desc',function(){
        te=$(this).attr("id");
        h=te.substring(4);
        console.log(h);

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
        console.log(val);
    })
    list=JSON.parse('<?php echo $listdbo?>')
    npro=JSON.parse('<?php echo $n_pro?>')
    countr=list.length;
    console.log(countr)
    for (k = 0; k <countr; k++) {
        pro='<?php echo $prolist ?>'
        var obj = list[k];
        console.log(obj)
        dapro=JSON.parse(pro)

        count=count+1;
        var c='<tr id="int'+count+'">'
        c+='<td>'
        c+='<div class="form-group field-can"> <label class="control-label" for="facturabody-'+count+'-cant"></label><input type="text" id="can'+count+'" class="form-control cant" name="FacturaBody['+count+'][cant]" value="'+obj['cant']+'" onkey="javascript:fields2()">'
        c+='</td>'
        c+='<td>'
        c+='<div class="form-group field-valo"><label class="control-label" for="valo"></label><select id="'+count+'" class="form-control la" name="Product['+count+'][name]"> <option value="">Select ...</option>'
        for(i in dapro){
            c+='<option class="s" value="'+i+'">"'+i+'"</option>'
        }

        c+='</select></div>'
        c+='</td>'
        c+='<td>'
        c+='<div class="form-group field-idn"><label class="control-label" for="facturabody-'+count+'-precio_u"></label><input type="text" id="idn'+count+'" class="form-control preu" name="FacturaBody['+count+'][precio_u]" value="'+obj['precio_u']+'"><div class="help-block"></div> </div> '
        c+='</td>'
        c+='<td>'
        c+='<div class="form-group field-desc"><label class="control-label" for="facturabody-+count+-desc"></label><input type="text" id="desc'+count+'" class="form-control desc" name="FacturaBody['+count+'][desc]" value="">'
        c+='</td>'
        c+='<td>'
        c+='<div class="form-group field-valtotal"><label class="control-label" for="facturabody-+count+-precio_total"></label><input type="text" id="valtotal'+count+'" class="form-control g" name="FacturaBody['+count+'][precio_total]" value="'+obj['precio_total']+'" readonly>'
        c+='</td>'
        c+='<td>'
        c+='<button class="btn btn-danger ft-3 remove" id="'+count+'">Eliminar</button>'
        c+='</td>'
        c+='</tr>'
        console.log("aqui estoy")

        $('#nuevo').append(c);
        val1=npro[obj['id_producto']]
        console.log(val1)
        $('#'+count+' option[value="'+val1+'"]').attr("selected",true);
    }


    $('#tipodocu').change(function(){
        tipo=$(this).val()
        if (tipo=="Proveedor") {
            $("#vendedor").hide()
                $(".preu").val("");
        }
        else{
            $("#vendedor").show()
            $(".preu").val("");
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


    dapro=JSON.parse(pro)
     var c='<tr id="int'+count+'">'
         c+='<td>'
       c+='<div class="form-group field-can"> <label class="control-label" for="facturabody-'+count+'-cant"></label><input type="text" id="can'+count+'" class="form-control cant" name="FacturaBody['+count+'][cant]" value="" onkey="javascript:fields2()">'
      c+='</td>'
     c+='<td>'
    c+='<div class="form-group field-valo"><label class="control-label" for="valo"></label><select id="'+count+'" class="form-control la" name="Product['+count+'][name]"> <option value="">Select...</option>'
        for(i in dapro){
         c+='<option class="s" value="'+i+'">"'+i+'"</option>'
        }
    c+='</select></div>'
    c+='</td>'
    c+='<td>'
    c+='<div class="form-group field-idn"><label class="control-label" for="facturabody-'+count+'-precio_u"></label><input type="text" id="idn'+count+'" class="form-control preu" name="FacturaBody['+count+'][precio_u]" value=""><div class="help-block"></div> </div> '
    c+='</td>'
    c+='<td>'
    c+='<div class="form-group field-desc"><label class="control-label" for="facturabody-+count+-desc"></label><input type="text" id="desc'+count+'" class="form-control desc" name="FacturaBody['+count+'][desc]" value="">'
    c+='</td>'
    c+='<td>'
    c+='<div class="form-group field-valtotal"><label class="control-label" for="facturabody-+count+-precio_total"></label><input type="text" id="valtotal'+count+'" class="form-control g" name="FacturaBody['+count+'][precio_total]" value="" readonly>'
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

        console.log(c)
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

    console.log(sumn);
    console.log(sumiv);
    iva=sumiv*0.12;
    des=0;
    total=sumiv+sumn+iva+des;
    $('#iva').val(round(iva))
    $('#des').val(round(iva))
    $('#total').val(round(total))
    console.log(h)

})

    $(document).on('click','.remove',function(){
        id=$(this).attr("id");


        $("#int"+id).remove();
        sum=0;
        c=0;
        sumiv=0;
        sumn=0;
        iva=JSON.parse('<?php echo $liva?>');
        item=[];
        $('.la').each(function(){
            item.push($(this).val())
        })
        console.log(item[0]);
        $('.g').each(function(){

            console.log(c)
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

        console.log(sumn);
        console.log(sumiv);
        iva=sumiv*0.12;
        des=0;
        total=sumiv+sumn+iva+des;
        $('#iva').val(iva)
        $('#des').val(iva)
        $('#total').val(total)
    })

    $('#nuevo').append(c);
})
    $('body').on('beforeSubmit', 'form#dynamic-form111', function () {
        var form = $(this);
        // return false if form still have some validation errors
        if (form.find('.has-error').length)
        {
            return false;
        }
        // submit form
        $.ajax({
            url    : form.attr('action'),
            type   : 'get',
            data   : form.serialize(),
            success: function (response)
            {
                var getupdatedata = $(response).find('#filter_id_test');
                // $.pjax.reload('#note_update_id'); for pjax update
                $('#yiiikap').html(getupdatedata);
                //console.log(getupdatedata);
            },
            error  : function ()
            {
                console.log('internal server error');
            }
        });
        return false;
    });
function calcular(){
    tip= $('#tipodocu').val();
    console.log(tip)
    if(tip=="Cliente"){
        f=JSON.parse('<?php echo $prelist?>');

    }
    else {
        if (tip == "Proveedor") {
            f = JSON.parse('<?php echo $lcosto?>');

        }
    }

    cost=JSON.parse('<?php echo $lcosto?>');
    console.log(cost);
    $('#idn'+h+'').val(f[d]);
    co=cost[d]
    cost=$('#can'+h).val()*co
    suma=0;
    cov.push(cost);
    console.log(cov)
    for (const element of cov){
        suma=suma+parseFloat(element)
        cost=JSON.parse('<?php echo $lcosto?>');
    }
    console.log(suma)
    $('#pre').val(suma)
    re=($('#can'+h).val())*($('#idn'+h).val())
    $('#valtotal'+h).val(re);
    console.log(cost)
    sum=0;
    c=0;
    sumiv=0;
    sumn=0;
    iva=JSON.parse('<?php echo $liva?>');
    item=[];
    $('.la').each(function(){
        item.push($(this).val())
    })
    console.log(item[0]);
    $('.g').each(function(){

        console.log(c)
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
    console.log(round(sumn));
    console.log(round(sumiv));
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

            console.log(c)
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

        console.log(sumn);
        console.log(sumiv);
        iva=sumiv*0.12;
        des=0;
        total=sumiv+sumn+iva+des;
        $('#iva').val(round(iva))
        $('#des').val(round(iva))
        $('#total').val(round(total))
        console.log(h)
    })
    $(document).on('keyup','.desc',function(){
        te=$(this).attr("id");
        h=te.substring(4);
        console.log(h);

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
        console.log(val);
    })
    $('#añadir')
$('#buttonsubmit').click(function(){
cantidad=[];
    preciou=[];
    pro=[];
    preciot=[];

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
    n_docu= $('#ndocu').val();
    id_ac='<?php echo $_GET['id']?>';
    $.ajax({
        method: "POST",
        data: { cantidad:cantidad,produc:pro,preciou:preciou,precioto:preciot,ndocumento:n_docu,nant:id_ac },
        url: '<?php echo Yii::$app->request->baseUrl. '/cliente/editarproceso' ?>',
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

    })

})
    function round(num) {
        var m = Number((Math.abs(num) * 100).toPrecision(15));
        return Math.round(m) / 100 * Math.sign(num);
    }
</script>
