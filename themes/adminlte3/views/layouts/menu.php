<?php
use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Users;
use app\models\User;
use app\models\Modulo;
use app\models\Menus;
use yii\db\Query;
use yii\db\Connection;
$query = new yii\db\Query();

?>
<!-- Brand Logo -->
<a href="<?= Url::to(['site/index']) ?>" class="brand-link">
    <img src="<?= Yii::getAlias('@web') . "/images/logos/isotipo.png"; ?>" class="brand-image img-circle elevation-3" alt="Cashbook Logo" style="opacity: .8">
    <span class="brand-text font-weight-light">Cashbook</span>
</a>
<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
    <?php if(!Yii::$app->user->isGuest) { ?> 
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
                 with font-awesome or any other icon font library -->

            <li class="nav-item">
                <a href="<?= Url::to(['site/index']) ?>" class="nav-link">
                    <i class="nav-icon fas fa-home"></i>
                    <p>
                        Inicio
                    </p>
                </a>
            </li>


            <?php if(Yii::$app->user->identity->role_id == 1 || Yii::$app->user->identity->role_id == 4) { ?> 
            <!--Empresas-->
            <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-book"></i>
                <p>             
                    <?php
                        $query = new yii\db\Query();
                        $data = $query->select(['nombre'])->from('modulo')->where('id = 1')->distinct()->all();
                        if ($data) 
                        {
                            foreach ($data as $row) {
                                echo $row['nombre'];
                             
                            }
                        }
                    ?>
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
                <ul class="nav nav-treeview">
				
				 <?php if(Yii::$app->user->identity->role_id == 1 || Yii::$app->user->identity->role_id == 4) { ?> 
                    <li class="nav-item">
                        <a href="<?= Url::to(['institution/index']) ?>" class="nav-link">
                            <p>Empresas</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= Url::to(['users/index']) ?>" class="nav-link">
                            <p>Usuarios</p>
                        </a>
                    </li>
					<li class="nav-item">
                        <a href="<?= Url::to(['modulo/index'], $schema = true) ?>" class="nav-link">
                            <p>Módulos</p>
                        </a>
                    </li>
				 <?php } ?>
					
					 <?php if(Yii::$app->user->identity->role_id == 1 ) { ?> 
                    <li class="nav-item">
                        <a href="<?= Url::to(['role/index'], $schema = true) ?>" class="nav-link">
                            <p>Roles</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= Url::to(['bank/index'], $schema = true) ?>" class="nav-link">
                            <p>Bancos</p>
                        </a>
                    </li>
                    
                   
                    <li class="nav-item has-treeview menu-closed">
                        <a href="#" class="nav-link">
                            <p>Localidades</p>
                            <i class="right fas fa-angle-left"></i>
                        </a>
                        <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="<?= Url::to(['country/index']) ?>" class="nav-link">
                            <p>Países</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= Url::to(['region/index']) ?>" class="nav-link">
                            <p>Regiones</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= Url::to(['province/index']) ?>" class="nav-link">
                            <p>Provincias</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= Url::to(['city/index']) ?>" class="nav-link">
                            <p>Cuidades</p>
                        </a>
                    </li>
                    </ul>
                    </li>
					 <?php } ?>
                </ul>
            </li>
            <?php } ?>


             <?php 
             $agregar=0;
             $consultar=0;
             $_SESSION["user"] = Yii::$app->user->identity->id;
             
             $data = $query->select(['id','agregar','consultar'])->from('permission_usuario')->where('id_users ='.$_SESSION["user"].'and id_modulo = 2 and (agregar = true or consultar = true)')->orderby('id DESC')->all();
             if ($data) {
                  foreach ($data as $row) 
                 { 
                     $agregar = $row['agregar'];    
                     $consultar = $row['consultar'];  
                 }
             }
             if($agregar == 1 || $consultar == 1 || Yii::$app->user->identity->role_id == 1)
             { 
                 ?> 
				 
				 <?php 
             $estado=0;
             $data = $query->select(['id','status'])->from('modulo')->where('id = 2')->distinct()->all();
             if ($data) {
                  foreach ($data as $row) 
                 { 
                     $estado = $row['status'];    
                 }
             }
             if($estado == 1)
             { 
                 ?> 
            <!--Contabilidad-->
            <li class="nav-item has-treeview menu-closed">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-book"></i>
                    <p>
                    <?php
                     
                        $data = $query->select(['id','nombre'])->from('modulo')->where('id = 2')->distinct()->all();
                        if ($data) 
                        {
                            foreach ($data as $row) {
                                echo $row['nombre'];
                        
                            }
                        }
                    ?>
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                <?php 
              
               
                $data = $query->select(['id','agregar','consultar'])->from('permission_usuario')->where('id_users ='.$_SESSION["user"].'and id_modulo = 2 and id_submodulos = 1')->orderby('id DESC')->all();
                if ($data) {
                     foreach ($data as $row) 
                    { 
                        $agregar = $row['agregar'];    
                        $consultar = $row['consultar'];  
                    }
                }
                if($agregar == 1 || $consultar == 1 || Yii::$app->user->identity->role_id == 1 )
                { ?>
                    
                    <li class="nav-item">
                        <a href="<?= Url::to(['accountingseats/index?AccountingSeats%5Baccount%5D=&AccountingSeats%5Bdatefrom%5D=2022-01-01&AccountingSeats%5Bdateto%5D=2022-12-31&AccountingSeats%5Bcost_center%5D=']) ?>" class="nav-link">
                            <p>Asientos
                            </p>
                        </a>
                    </li>
                <?php }?>
                <?php 
               
                $data = $query->select(['id','agregar','consultar'])->from('permission_usuario')->where('id_users ='.$_SESSION["user"].'and id_modulo = 2 and id_submodulos = 2')->orderby('id DESC')->all();
                if ($data) {
                     foreach ($data as $row) 
                    { 
                        $agregar = $row['agregar'];    
                        $consultar = $row['consultar'];  
                    }
                }
                if($agregar == 1 || $consultar == 1 || Yii::$app->user->identity->role_id == 1)
                { ?>
                
                
                    <li class="nav-item">
                        <a href="<?= Url::to(['accountingexercises/index']) ?>" class="nav-link">
                            <p>Ejercicios Contables</p>
                        </a>
                    </li>
                <?php } ?>
                <?php 
              
                $data = $query->select(['id','agregar','consultar'])->from('permission_usuario')->where('id_users ='.$_SESSION["user"].' and id_modulo = 2 and id_submodulos = 4')->orderby('id DESC')->all();
                if ($data) {
                     foreach ($data as $row) 
                    { 
                        $agregar = $row['agregar'];    
                        $consultar = $row['consultar'];  
                    }
                }
                if($agregar == 1 || $consultar == 1 || Yii::$app->user->identity->role_id == 1)
                { ?>
                    
                    <li class="nav-item">
                        <a href="<?= Url::to(['chartaccounts/index'], $schema = true) ?>" class="nav-link">
                            <p>Plan de Cuentas</p>
                        </a>
                    </li>

                    <?php } ?>
                <?php 
              
                $data = $query->select(['id','agregar','consultar'])->from('permission_usuario')->where('id_users ='.$_SESSION["user"].'and id_modulo = 2 and id_submodulos = 5')->orderby('id DESC')->all();
                if ($data) {
                     foreach ($data as $row) 
                    { 
                        $agregar = $row['agregar'];    
                        $consultar = $row['consultar'];  
                    }
                }
                if($agregar == 1 || $consultar == 1 || Yii::$app->user->identity->role_id == 1)
                { ?>

                    <li class="nav-item">
                        <a href="<?= Url::to(['costcenter/index'], $schema = true) ?>" class="nav-link">
                            <p>Centros de Costos</p>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </li>
        <?php } ?>
<?php } ?>

        <?php 
             
             $agregar=0;
             $consultar=0;
             $_SESSION["user"] = Yii::$app->user->identity->id;
             
             $data = $query->select(['id','agregar','consultar'])->from('permission_usuario')->where('id_users ='.$_SESSION["user"].'and id_modulo = 4 and (agregar = true or consultar = true)')->orderby('id DESC')->all();
             if ($data) {
                  foreach ($data as $row) 
                 { 
                     $agregar = $row['agregar'];    
                     $consultar = $row['consultar'];  
                 }
             }
             if($agregar == 1 || $consultar == 1 || Yii::$app->user->identity->role_id == 1)
             
             { 

                 ?> 
				 <?php 
             $estado=0;
             $data = $query->select(['id','status'])->from('modulo')->where('id = 4')->distinct()->all();
             if ($data) {
                  foreach ($data as $row) 
                 { 
                     $estado = $row['status'];    
                 }
             }
             if($estado == 1)
             { 
                 ?>
                <!--Personas-->
            <li class="nav-item has-treeview menu-closed">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                    <?php
                        $query = new yii\db\Query();
                        $data = $query->select(['nombre'])->from('modulo')->where('id = 4')->distinct()->all();
                        if ($data) 
                        {
                            foreach ($data as $row) {
                                echo $row['nombre'];
                            }
                        }
                    ?>
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="<?= Url::to(['person/index'], $schema = true) ?>" class="nav-link">
                        <?php
                        $query = new yii\db\Query();
                        $data = $query->select(['nombre'])->from('modulo')->where('id = 4')->distinct()->all();
                        if ($data) 
                        {
                            foreach ($data as $row) {
                                echo $row['nombre'];
                            }
                        }
                        ?>
                        </a>
                    </li>
                </ul>
            </li>
			 <?php } } ?>



            <?php 
             
             $agregar=0;
             $consultar=0;
             $_SESSION["user"] = Yii::$app->user->identity->id;
             
             $data = $query->select(['id','agregar','consultar'])->from('permission_usuario')->where('id_users ='.$_SESSION["user"].'and id_modulo = 6 and id_submodulos = 60 and (agregar = true or consultar = true)')->orderby('id DESC')->all();
             if ($data) {
                  foreach ($data as $row) 
                 { 
                     $agregar = $row['agregar'];    
                     $consultar = $row['consultar'];  
                 }
             }
             if($agregar == 1 || $consultar == 1 || Yii::$app->user->identity->role_id == 1)
             
             { 

                 ?> 
				 <?php 
             $estado=0;
             $data = $query->select(['id','status'])->from('modulo')->where('id = 6')->distinct()->all();
             if ($data) {
                  foreach ($data as $row) 
                 { 
                     $estado = $row['status'];    
                 }
             }
             if($estado == 1)
             { 
                 ?>
                <!--Transacciones-->
            <li class="nav-item has-treeview menu-closed">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-clipboard"></i>
                    <p>
                    <?php
                        $query = new yii\db\Query();
                        $data = $query->select(['nombre'])->from('modulo')->where('id = 6')->distinct()->all();
                        if ($data) 
                        {
                            foreach ($data as $row) {
                                echo $row['nombre'];
                            }
                        }
                    ?>
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>

                <ul class="nav nav-treeview">
					<li class="nav-item">
                               <a href="<?= Url::to(['cliente/index?tipos=All']) ?>" class="nav-link">
                                    <p>Todo</p>
                                </a>
                    </li>
					<li class="nav-item">
                                
                                <a href="<?= Url::to(['cliente/index?tipos=Cliente']) ?>" class="nav-link">
                                    <p>Venta</p>
                                </a>
                            </li>
                   <li class="nav-item">
                                <a href="<?= Url::to(['cliente/index?tipos=Proveedor']) ?>" class="nav-link">
                                    <p>Compra</p>
                                </a>

                   </li>
                    <li class="nav-item">
                        <a href="<?= Url::to(['/cobros/view']) ?>" class="nav-link">
                            <p>Cobros/Pagos</p>
                        </a>

                    </li>
                    <li class="nav-item">
                        <a href="<?= Url::to(['/anullments/index']) ?>" class="nav-link">
                            <p>Anulaciones</p>
                        </a>

                    </li>

                </ul>
            </li>
			 <?php } } ?>
           <?php 
             $estado=0;
             $data = $query->select(['id','status'])->from('modulo')->where('id = 7')->distinct()->all();
             if ($data) {
                  foreach ($data as $row) 
                 { 
                     $estado = $row['status'];    
                 }
             }
             if($estado == 1)
             { 
                 ?>
            <!--Producto/Servicio-->
            <li class="nav-item has-treeview menu-closed">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-clipboard"></i>
                    <p>
                    <?php
                        $query = new yii\db\Query();
                        $data = $query->select(['nombre'])->from('modulo')->where('id = 7')->distinct()->all();
                        if ($data) 
                        {
                            foreach ($data as $row) {
                                echo $row['nombre'];
                            }
                        }
                    ?>
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">

                        <a href="<?= Url::to(['product/index']) ?>" class="nav-link">
                            <p>Servicios/Productos</p>
                        </a>
                    </li>
                </ul>
             
            </li>
           
            <?php 
             }
             $agregar=0;
             $consultar=0;
             $_SESSION["user"] = Yii::$app->user->identity->id;
             
             $data = $query->select(['id','agregar','consultar'])->from('permission_usuario')->where('id_users ='.$_SESSION["user"].'and id_modulo = 3 and (agregar = true or consultar = true)')->orderby('id DESC')->all();
             if ($data) {
                  foreach ($data as $row) 
                 { 
                     $agregar = $row['agregar'];    
                     $consultar = $row['consultar'];  
                 }
             }
             if($agregar == 1 || $consultar == 1 || Yii::$app->user->identity->role_id == 1)
             
             { 

                 ?> 
				 <?php 
             $estado=0;
             $data = $query->select(['id','status'])->from('modulo')->where('id = 3')->distinct()->all();
             if ($data) {
                  foreach ($data as $row) 
                 { 
                     $estado = $row['status'];    
                 }
             }
             if($estado == 1)
             { 
                 ?>
                <!--Bancos-->
            <li class="nav-item has-treeview menu-closed">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-clipboard"></i>
                    <p>
                    <?php
                        $query = new yii\db\Query();
                        $data = $query->select(['nombre'])->from('modulo')->where('id = 3')->distinct()->all();
                        if ($data) 
                        {
                            foreach ($data as $row) {
                                echo $row['nombre'];
                            }
                        }
                    ?>
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
				<ul class="nav nav-treeview">
                    <li class="nav-item">
                                <a href="<?= Url::to(['bankdetails/index']) ?>" class="nav-link">
                                    <p>Ver Todos</p>
                                </a>
                            </li>
                </ul>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                                <a href="<?= Url::to(['bankdetails/transaction']) ?>" class="nav-link">
                                    <p>Movimientos Bancarios</p>
                                </a>
                            </li>
                </ul>
				<ul class="nav nav-treeview">
                    <li class="nav-item">
                                <a href="<?= Url::to(['bankdetails/create']) ?>" class="nav-link">
                                    <p>Crear Nuevo</p>
                                </a>
                            </li>
                </ul>
				
              
            </li>
			 <?php }} ?>
            <?php 
             
             $agregar=0;
             $consultar=0;
             $_SESSION["user"] = Yii::$app->user->identity->id;
             
             $data = $query->select(['id','agregar','consultar'])->from('permission_usuario')->where('id_users ='.$_SESSION["user"].'and id_modulo = 5 and (agregar = true or consultar = true)')->orderby('id DESC')->all();
             if ($data) {
                  foreach ($data as $row) 
                 { 
                     $agregar = $row['agregar'];    
                     $consultar = $row['consultar'];  
                 }
             }
             if($agregar == 1 || $consultar == 1 || Yii::$app->user->identity->role_id == 1)
             
             { 
                 ?>
				 <?php 
             $estado=0;
             $data = $query->select(['id','status'])->from('modulo')->where('id = 5')->distinct()->all();
             if ($data) {
                  foreach ($data as $row) 
                 { 
                     $estado = $row['status'];    
                 }
             }
             if($estado == 1)
             { 
                 ?>
             <!--Inventario-->
             <li class="nav-item has-treeview menu-closed">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-clipboard"></i>
                    <p>
                    <?php
                        $query = new yii\db\Query();
                        $data = $query->select(['nombre'])->from('modulo')->where('id = 5')->distinct()->all();
                        if ($data) 
                        {
                            foreach ($data as $row) {
                                echo $row['nombre'];
                            }
                        }
                    ?>
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <p>Inventarios</p>
                        </a>
                    </li>
                </ul>
            
            </li>
			 <?php } }?>



            <?php 
             
             $agregar=0;
             $consultar=0;
             $_SESSION["user"] = Yii::$app->user->identity->id;
             
             $data = $query->select(['id','agregar','consultar'])->from('permission_usuario')->where('id_users ='.$_SESSION["user"].'and id_modulo = 11 and (agregar = true or consultar = true)')->orderby('id DESC')->all();
             if ($data) {
                  foreach ($data as $row) 
                 { 
                     $agregar = $row['agregar'];    
                     $consultar = $row['consultar'];  
                 }
             }
             if($agregar == 1 || $consultar == 1 || Yii::$app->user->identity->role_id == 1)
             
             { 
                 ?>
				 <?php 
             $estado=0;
             $data = $query->select(['id','status'])->from('modulo')->where('id = 11')->distinct()->all();
             if ($data) {
                  foreach ($data as $row) 
                 { 
                     $estado = $row['status'];    
                 }
             }
             if($estado == 1)
             { 
                 ?>
                <!--RRHH-->
            <li class="nav-item has-treeview menu-closed">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-clipboard"></i>
                    <p>
                    <?php
                        $query = new yii\db\Query();
                        $data = $query->select(['nombre'])->from('modulo')->where('id = 11')->distinct()->all();
                        if ($data) 
                        {
                            foreach ($data as $row) {
                                echo $row['nombre'];
                            }
                        }
                    ?>
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <p>Recursos Humanos</p>
                        </a>
                    </li>
                </ul>
              
            </li>
			 <?php } } ?>



            <?php 
             
             $agregar=0;
             $consultar=0;
             $_SESSION["user"] = Yii::$app->user->identity->id;
             
             $data = $query->select(['id','agregar','consultar'])->from('permission_usuario')->where('id_users ='.$_SESSION["user"].'and id_modulo = 13 and (agregar = true or consultar = true)')->orderby('id DESC')->all();
             if ($data) {
                  foreach ($data as $row) 
                 { 
                     $agregar = $row['agregar'];    
                     $consultar = $row['consultar'];  
                 }
             }
             if($agregar == 1 || $consultar == 1 || Yii::$app->user->identity->role_id == 1)
             
             { 
                 ?>
				 
				 <?php 
             $estado=0;
             $data = $query->select(['id','status'])->from('modulo')->where('id = 13')->distinct()->all();
             if ($data) {
                  foreach ($data as $row) 
                 { 
                     $estado = $row['status'];    
                 }
             }
             if($estado == 1)
             { 
                 ?>
                <!--CRM-->
            <li class="nav-item has-treeview menu-closed">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-clipboard"></i>
                    <p>
                    <?php
                        $query = new yii\db\Query();
                        $data = $query->select(['nombre'])->from('modulo')->where('id = 13')->distinct()->all();
                        if ($data) 
                        {
                            foreach ($data as $row) {
                                echo $row['nombre'];
                            }
                        }
                    ?>
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <p>CRM</p>
                        </a>
                    </li>
                </ul>
              
            </li>
			 <?php } } ?>


            <?php 
             
             $acceder=0;
             
             $_SESSION["user"] = Yii::$app->user->identity->id;
             
             $data = $query->select(['id','acceder'])->from('permission_usuario')->where('id_users ='.$_SESSION["user"].'and id_modulo = 8 and acceder = true')->orderby('id DESC')->all();
             if ($data) {
                  foreach ($data as $row) 
                 { 
                     $acceder = $row['acceder'];    
                     
                 }
             }
             if($acceder == 1 || Yii::$app->user->identity->role_id == 1)
             
             { 

                 ?> 
				 <?php 
             $estado=0;
             $data = $query->select(['id','status'])->from('modulo')->where('id = 8')->distinct()->all();
             if ($data) {
                  foreach ($data as $row) 
                 { 
                     $estado = $row['status'];    
                 }
             }
             if($estado == 1)
             { 
                 ?>
             <!--Reportes-->
             <li class="nav-item has-treeview menu-closed">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-clipboard"></i>
                    <p>
                    <?php
                        $query = new yii\db\Query();
                        $data = $query->select(['nombre'])->from('modulo')->where('id = 8')->distinct()->all();
                        if ($data) 
                        {
                            foreach ($data as $row) {
                                echo $row['nombre'];
                            }
                        }
                    ?>
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <p>Reportes</p>
                        </a>
                    </li>
                </ul>
              
            </li>
			 <?php } } ?>
            
        </ul>
        <?php } ?>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
