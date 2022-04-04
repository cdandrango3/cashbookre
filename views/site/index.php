
<?php
use kartik\select2\Select2;
use yii\helpers\Url;

/* @var $this yii\web\View */
//$this->params['breadcrumbs'][] = ['label' => $this->title, 'active' => true];
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="librerias/bootstrap/css/bootstrap.css">
	<script src="<?= Yii::getAlias('@web') . "/librerias/jquery-3.3.1.min.js" ?>"></script>
	
	<script src="<?= Yii::getAlias('@web') . "/librerias/plotly-latest.min.js" ?>"></script>
</head>
<body>
<center><b><h3>Bienvenidos a Casbook, ¿Qué deseas hacer?</h3></b></center>

<br>
<div class="container-fluid">

        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-white">
              <a href= "<?= Yii::getAlias('@web') . "/product/create" ?>" class="small-box-footer" >
			   <br>
			   <img src="<?= Yii::getAlias('@web') . "/images/crearservicio.png"; ?>" width="90" class="img-circle elevation-2" alt="User Image">
			   <br><br>
			 <h5> Crear un Servicio/Producto</h5>
			  </a>
            </div>
          </div>
       
	   <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-white">
              <a href= "<?= Yii::getAlias('@web') . "/person/index" ?>" class="small-box-footer" >
			   <br>
			   <img src="<?= Yii::getAlias('@web') . "/images/grupo.png"; ?>" width="90" class="img-circle elevation-2" alt="User Image">
			   <br><br>
			  <h5>Registrar personas</h5>
			  </a>
            </div>
          </div>
		  <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-white">
              <a href= "<?= Yii::getAlias('@web') . "/cliente/index?tipos=Cliente" ?>" class="small-box-footer" >
			   <br>
			   <img src="<?= Yii::getAlias('@web') . "/images/cobro.png"; ?>" width="90" class="img-circle elevation-2" alt="User Image">
			   <br><br>
			  <h5>Registrar un cobro</h5>
			  </a>
            </div>
          </div>
		 <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-white">
              <a href= "<?= Yii::getAlias('@web') . "/cliente/index?tipos=Proveedor" ?>" class="small-box-footer" >
			   <br>
			   <img src="<?= Yii::getAlias('@web') . "/images/servicioproducto.png"; ?>" width="90" class="img-circle elevation-2" alt="User Image">
			   <br><br>
			  <h5>Registrar una venta/compra</h5>
			  </a>
            </div>
          </div>
		  
        </div>
        
          
          
      </div>
	 


<?php

$query = new yii\db\Query();
$enero_in = $query->select('sum(total) as ingresos')->from('head_fact , facturafin')
->where('id_head = n_documentos')
->andWhere(["tipo_de_documento" => "Cliente"])
->andWhere([">=","f_timestamp" , "2022-01-01"])
->andWhere(["<=","f_timestamp", "2022-01-31"])
->all();
if ($enero_in) {
    foreach ($enero_in as $row) 
    { 
        $enero_i = $row['ingresos'];    
    }
}
$febrero_in = $query->select('sum(total) as ingresos')->from('head_fact , facturafin')
->where('id_head = n_documentos')
->andWhere(["tipo_de_documento" => "Cliente"])
->andWhere([">=","f_timestamp" , "2022-02-01"])
->andWhere(["<=","f_timestamp", "2022-02-28"])
->all();
if ($febrero_in) {
    foreach ($febrero_in as $row) 
    { 
        $febrero_i = $row['ingresos'];    
    }
}
$marzo_in = $query->select('sum(total) as ingresos')->from('head_fact , facturafin')
->where('id_head = n_documentos')
->andWhere(["tipo_de_documento" => "Cliente"])
->andWhere([">=","f_timestamp" , "2022-03-01"])
->andWhere(["<=","f_timestamp", "2022-03-31"])
->all();
if ($marzo_in) {
    foreach ($marzo_in as $row) 
    { 
        $marzo_i = $row['ingresos'];    
    }
}

$abril_in = $query->select('sum(total) as ingresos')->from('head_fact , facturafin')
->where('id_head = n_documentos')
->andWhere(["tipo_de_documento" => "Cliente"])
->andWhere([">=","f_timestamp" , "2022-04-01"])
->andWhere(["<=","f_timestamp", "2022-04-30"])
->all();
if ($abril_in) {
    foreach ($abril_in as $row) 
    { 
        $abril_i = $row['ingresos'];    
    }
}

$mayo_in = $query->select('sum(total) as ingresos')->from('head_fact , facturafin')
->where('id_head = n_documentos')
->andWhere(["tipo_de_documento" => "Cliente"])
->andWhere([">=","f_timestamp" , "2022-05-01"])
->andWhere(["<=","f_timestamp", "2022-05-31"])
->all();
if ($mayo_in) {
    foreach ($mayo_in as $row) 
    { 
        $mayo_i = $row['ingresos'];    
    }
}

$junio_in = $query->select('sum(total) as ingresos')->from('head_fact , facturafin')
->where('id_head = n_documentos')
->andWhere(["tipo_de_documento" => "Cliente"])
->andWhere([">=","f_timestamp" , "2022-06-01"])
->andWhere(["<=","f_timestamp", "2022-06-30"])
->all();
if ($junio_in) {
    foreach ($junio_in as $row) 
    { 
        $junio_i = $row['ingresos'];    
    }
}

$julio_in = $query->select('sum(total) as ingresos')->from('head_fact , facturafin')
->where('id_head = n_documentos')
->andWhere(["tipo_de_documento" => "Cliente"])
->andWhere([">=","f_timestamp" , "2022-07-01"])
->andWhere(["<=","f_timestamp", "2022-07-31"])
->all();
if ($julio_in) {
    foreach ($julio_in as $row) 
    { 
        $julio_i = $row['ingresos'];    
    }
}

$agosto_in = $query->select('sum(total) as ingresos')->from('head_fact , facturafin')
->where('id_head = n_documentos')
->andWhere(["tipo_de_documento" => "Cliente"])
->andWhere([">=","f_timestamp" , "2022-08-01"])
->andWhere(["<=","f_timestamp", "2022-08-31"])
->all();
if ($agosto_in) {
    foreach ($agosto_in as $row) 
    { 
        $agosto_i = $row['ingresos'];    
    }
}

$septiembre_in = $query->select('sum(total) as ingresos')->from('head_fact , facturafin')
->where('id_head = n_documentos')
->andWhere(["tipo_de_documento" => "Cliente"])
->andWhere([">=","f_timestamp" , "2022-09-01"])
->andWhere(["<=","f_timestamp", "2022-09-30"])
->all();
if ($septiembre_in) {
    foreach ($septiembre_in as $row) 
    { 
        $septiembre_i = $row['ingresos'];    
    }
}

$octubre_in = $query->select('sum(total) as ingresos')->from('head_fact , facturafin')
->where('id_head = n_documentos')
->andWhere(["tipo_de_documento" => "Cliente"])
->andWhere([">=","f_timestamp" , "2022-10-01"])
->andWhere(["<=","f_timestamp", "2022-10-31"])
->all();
if ($octubre_in) {
    foreach ($octubre_in as $row) 
    { 
        $octubre_i = $row['ingresos'];    
    }
}

$noviembre_in = $query->select('sum(total) as ingresos')->from('head_fact , facturafin')
->where('id_head = n_documentos')
->andWhere(["tipo_de_documento" => "Cliente"])
->andWhere([">=","f_timestamp" , "2022-11-01"])
->andWhere(["<=","f_timestamp", "2022-11-30"])
->all();
if ($noviembre_in) {
    foreach ($noviembre_in as $row) 
    { 
        $noviembre_i = $row['ingresos'];    
    }
}

$diciembre_in = $query->select('sum(total) as ingresos')->from('head_fact , facturafin')
->where('id_head = n_documentos')
->andWhere(["tipo_de_documento" => "Cliente"])
->andWhere([">=","f_timestamp" , "2022-12-01"])
->andWhere(["<=","f_timestamp", "2022-12-31"])
->all();
if ($diciembre_in) {
    foreach ($diciembre_in as $row) 
    { 
        $diciembre_i = $row['ingresos'];    
    }
}

//Egresos


$query = new yii\db\Query();
$enero_eg = $query->select('sum(total) as egresos')->from('head_fact , facturafin')
->where('id_head = n_documentos')
->andWhere(["tipo_de_documento" => "Proveedor"])
->andWhere([">=","f_timestamp" , "2022-01-01"])
->andWhere(["<=","f_timestamp", "2022-01-31"])
->all();
if ($enero_eg) {
    foreach ($enero_eg as $row) 
    { 
        $enero_e = $row['egresos'];    
    }
}
$febrero_eg = $query->select('sum(total) as egresos')->from('head_fact , facturafin')
->where('id_head = n_documentos')
->andWhere(["tipo_de_documento" => "Proveedor"])
->andWhere([">=","f_timestamp" , "2022-02-01"])
->andWhere(["<=","f_timestamp", "2022-02-28"])
->all();
if ($febrero_eg) {
    foreach ($febrero_eg as $row) 
    { 
        $febrero_e = $row['egresos'];    
    }
}
$marzo_eg = $query->select('sum(total) as egresos')->from('head_fact , facturafin')
->where('id_head = n_documentos')
->andWhere(["tipo_de_documento" => "Proveedor"])
->andWhere([">=","f_timestamp" , "2022-03-01"])
->andWhere(["<=","f_timestamp", "2022-03-31"])
->all();
if ($marzo_eg) {
    foreach ($marzo_eg as $row) 
    { 
        $marzo_e = $row['egresos'];    
    }
}

$abril_eg = $query->select('sum(total) as egresos')->from('head_fact , facturafin')
->where('id_head = n_documentos')
->andWhere(["tipo_de_documento" => "Proveedor"])
->andWhere([">=","f_timestamp" , "2022-04-01"])
->andWhere(["<=","f_timestamp", "2022-04-30"])
->all();
if ($abril_eg) {
    foreach ($abril_eg as $row) 
    { 
        $abril_e = $row['egresos'];    
    }
}

$mayo_eg = $query->select('sum(total) as egresos')->from('head_fact , facturafin')
->where('id_head = n_documentos')
->andWhere(["tipo_de_documento" => "Proveedor"])
->andWhere([">=","f_timestamp" , "2022-05-01"])
->andWhere(["<=","f_timestamp", "2022-05-31"])
->all();
if ($mayo_eg) {
    foreach ($mayo_eg as $row) 
    { 
        $mayo_e = $row['egresos'];    
    }
}

$junio_eg = $query->select('sum(total) as egresos')->from('head_fact , facturafin')
->where('id_head = n_documentos')
->andWhere(["tipo_de_documento" => "Proveedor"])
->andWhere([">=","f_timestamp" , "2022-06-01"])
->andWhere(["<=","f_timestamp", "2022-06-30"])
->all();
if ($junio_eg) {
    foreach ($junio_eg as $row) 
    { 
        $junio_e = $row['egresos'];    
    }
}

$julio_eg = $query->select('sum(total) as egresos')->from('head_fact , facturafin')
->where('id_head = n_documentos')
->andWhere(["tipo_de_documento" => "Proveedor"])
->andWhere([">=","f_timestamp" , "2022-07-01"])
->andWhere(["<=","f_timestamp", "2022-07-31"])
->all();
if ($julio_eg) {
    foreach ($julio_eg as $row) 
    { 
        $julio_e = $row['egresos'];    
    }
}

$agosto_eg = $query->select('sum(total) as egresos')->from('head_fact , facturafin')
->where('id_head = n_documentos')
->andWhere(["tipo_de_documento" => "Proveedor"])
->andWhere([">=","f_timestamp" , "2022-08-01"])
->andWhere(["<=","f_timestamp", "2022-08-31"])
->all();
if ($agosto_eg) {
    foreach ($agosto_eg as $row) 
    { 
        $agosto_e = $row['egresos'];    
    }
}

$septiembre_eg = $query->select('sum(total) as egresos')->from('head_fact , facturafin')
->where('id_head = n_documentos')
->andWhere(["tipo_de_documento" => "Proveedor"])
->andWhere([">=","f_timestamp" , "2022-09-01"])
->andWhere(["<=","f_timestamp", "2022-09-30"])
->all();
if ($septiembre_eg) {
    foreach ($septiembre_eg as $row) 
    { 
        $septiembre_e = $row['egresos'];    
    }
}

$octubre_eg = $query->select('sum(total) as egresos')->from('head_fact , facturafin')
->where('id_head = n_documentos')
->andWhere(["tipo_de_documento" => "Proveedor"])
->andWhere([">=","f_timestamp" , "2022-10-01"])
->andWhere(["<=","f_timestamp", "2022-10-31"])
->all();
if ($octubre_eg) {
    foreach ($octubre_eg as $row) 
    { 
        $octubre_e = $row['egresos'];    
    }
}

$noviembre_eg = $query->select('sum(total) as egresos')->from('head_fact , facturafin')
->where('id_head = n_documentos')
->andWhere(["tipo_de_documento" => "Proveedor"])
->andWhere([">=","f_timestamp" , "2022-11-01"])
->andWhere(["<=","f_timestamp", "2022-11-30"])
->all();
if ($noviembre_eg) {
    foreach ($noviembre_eg as $row) 
    { 
        $noviembre_e = $row['egresos'];    
    }
}

$diciembre_eg = $query->select('sum(total) as egresos')->from('head_fact , facturafin')
->where('id_head = n_documentos')
->andWhere(["tipo_de_documento" => "Proveedor"])
->andWhere([">=","f_timestamp" , "2022-12-01"])
->andWhere(["<=","f_timestamp", "2022-12-31"])
->all();
if ($diciembre_eg) {
    foreach ($diciembre_eg as $row) 
    { 
        $diciembre_e = $row['egresos'];    
    }
}

	//Ingresos
	$valoresY=array($enero_i,$febrero_i,$marzo_i,$abril_i,$mayo_i,$junio_i, $julio_i,$agosto_i,$septiembre_i, $octubre_i, $noviembre_i, $diciembre_i);//montos
	$valoresX=array('Enero','Febrero','Marzo','Abril','Mayo','Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');//fechas
	$datosX=json_encode($valoresX);
	$datosY=json_encode($valoresY);
	//Egresos
	$valoresY1=array($enero_e,$febrero_e,$marzo_e,$abril_e,$mayo_e,$junio_e, $julio_e,$agosto_e,$septiembre_e, $octubre_e, $noviembre_e, $diciembre_e);//montos
	$valoresX1=array('Enero','Febrero','Marzo','Abril','Mayo','Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');//fechas
	$datosX1=json_encode($valoresX1);
	$datosY1=json_encode($valoresY1);

 ?>
 <center>
<table>
<tr><td style="width:450px;"> <center><h3>Ingresos</h3></center> </td><td></td><td> <center><h3>Costos/Gastos</h3></center></td><tr>
<tr><td>
<div id="graficaLineal">

<script type="text/javascript">
	function crearCadenaLineal(json){
		var parsed = JSON.parse(json);
		var arr = [];
		for(var x in parsed){
			arr.push(parsed[x]);
		}
		return arr;
	}
	datosX=crearCadenaLineal('<?php echo $datosX ?>');
	datosY=crearCadenaLineal('<?php echo $datosY ?>');
	var trace1 = {
		x: datosX,
		y: datosY,
		type: 'scatter'
	};
	var data = [trace1];
	Plotly.newPlot('graficaLineal', data);
</script>
</div>

</td>
<td style="width:50px;"></td><td style="width:450px;">


<div id="graficaLineal1"></div>

<script type="text/javascript">
	function crearCadenaLineal(json){
		var parsed = JSON.parse(json);
		var arr = [];
		for(var x in parsed){
			arr.push(parsed[x]);
		}
		return arr;
	}
	datosX=crearCadenaLineal('<?php echo $datosX1 ?>');
	datosY=crearCadenaLineal('<?php echo $datosY1 ?>');
	var trace1 = {
		x: datosX,
		y: datosY,
		type: 'scatter'
	};
	var data = [trace1];
	Plotly.newPlot('graficaLineal1', data);
</script>


</td>
</tr>
</table>
</center>
</body>
</html>