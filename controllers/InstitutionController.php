<?php

namespace app\controllers;

use Yii;
use app\models\Institution;
use app\models\InstitutionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * InstitutionController implements the CRUD actions for Institution model.
 */
class InstitutionController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulkdelete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Institution models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new InstitutionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Institution model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Empresa: ".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Institution model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Institution();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Crear nueva Empresa",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) && $model->save()){
                $query="insert into chart_accounts (id, code, slug, institution_id, bigparent_id, parent_id, status, created_at, updated_at, deleted_at, type_account)
values  (13580, '5.2.3', 'Gastos de Operaciones Descontinuadas', :institution , 461, 13425, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13306, '2.1.11', 'Pasivos directamente relacionados con los Activos No Corrientes', :institution , 188, 13229, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Pasivos directamente relacionados con Activos No Corrientes'),
        (13163, '1.1.5.2', 'Retenciones del IVA', :institution , 45, 13160, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Credito Tributario a favor de la Empresa (IVA)'),
        (13197, '1.2.1.8', 'Vehículos, equipos de transporte y equipo caminero móvil', :institution , 79, 13189, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Vehiculo, equipos de transporte y equipo caminero movil'),
        (13219, '1.2.4.5', '(-) Deterioro Acumulado de Activos Intangibles', :institution , 101, 13214, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, '(-) Deterioro Acumulado de Activos Intangibles'),
        (13323, '2.2.3.1', 'Obligaciones con Instituciones Financieras Locales No Corrientes', :institution , 204, 13322, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones con Instituciones Financieras locales No Corrientes'),
        (13324, '2.2.3.2', 'Obligaciones con Instituciones Financieras del Exterior No Corriente', :institution , 205, 13322, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones con Instituciones Financieras del Exterior No Corriente'),
        (13357, '3.1.6.2', 'Resultados provenientes de la adopción por 1era vez del las NIIF', :institution , 238, 13355, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Resultados provenientes de la adopción por 1era vez del las NIIF'),
        (13398, '5.1.2.2', 'Sobretiempos Mano de Obra Directa', :institution , 279, 13396, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Sueldos y Beneficios Sociales - Directa'),
        (13412, '5.1.3.6', 'Aportes Patronales al I.E.S.S. Mano de Obra Indirecta', :institution , 293, 13406, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Sueldos y Beneficios Sociales - Indirecta'),
        (13494, '5.2.1.2', 'Administrativos', :institution , 375, 13426, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (14072, '1.4.1.7', 'cgnfgnjg', :institution , 0, 14051, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (14063, '1.4.1.2', 'prueba', :institution , 0, 14051, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13188, '1.2', 'Activos No Corrientes', :institution , 70, 13120, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (14045, '1.3', 'activos tecnoglobal', :institution , 463, 13120, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'activos'),
        (14048, '1.4', 'activos tecnoglobal 2', :institution , 464, 13120, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'activos'),
        (13229, '2.1', 'Pasivo Corriente', :institution , 111, 13228, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13312, '2.2', 'Pasivo No Corriente', :institution , 194, 13228, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13340, '3.1', 'Patrimonio Atribuible a Propietarios', :institution , 221, 13339, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13375, '4.2', 'Otros Ingresos de Actividades Ordinarias', :institution , 256, 13362, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13386, '4.4', 'Ingresos por Operaciones Discontinuadas', :institution , 267, 13362, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Ingresos por Operaciones discontinuadas'),
        (13186, '1.1.6', 'Activos No Corrientes Mantenidos para la Venta y Op. desc.', :institution , 68, 13121, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Activos No Corrientes mantenidos para la Venta y descontinuadas.'),
        (13221, '1.2.5', 'Activos por Impuestos Diferidos', :institution , 103, 13188, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Activos por Impuestos Diferidos'),
        (13304, '2.1.9', 'Otros Pasivos Financieros', :institution , 186, 13229, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Pasivos Financieros'),
        (13344, '3.1.2', 'Aportes de Socios o Accionistas para Futura Capitalización', :institution , 225, 13340, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Aportes de Socios o Accionistas para Futura Capitalización'),
        (13383, '4.3.3', 'Ganancia de Asociadas y Negocios Conjuntos', :institution , 264, 13380, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Ganancia de Asociadas y Negocios Conjuntos'),
        (13567, '5.2.2', 'Gastos No Operacionales', :institution , 448, 13425, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13200, '1.2.1.11', '(-) Depreciación Acumulada Propiedades, Planta y Equipo', :institution , 82, 13189, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, '(-) Depreciación Acumulada Propiedades, Planta  y Equipo'),
        (13166, '1.1.5.2.3', '100% Honorarios, Arrendamientos, Personas Naturales', :institution , 48, 13163, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Credito Tributario a favor de la Empresa (IVA)'),
        (13237, '2.1.3.2.1', 'Documentos por Pagar Proveedores', :institution , 119, 13236, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Documento por Pagar'),
        (13261, '2.1.7.2.9', '1.75% Bienes Muebles de Naturaleza Corporal', :institution , 143, 13252, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Tributarias'),
        (13317, '2.2.2.1.2', 'Proveedores Exterior No Corriente', :institution , 199, 13315, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Cuenta por Pagar del Exterior No Corriente'),
        (13431, '5.2.1.1.4', 'Alimentación Vtas.', :institution , 312, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Sueldos, Salarios y demás Remuneraciones Vtas'),
        (13436, '5.2.1.1.9', 'Décimo Cuarto Sueldo Vtas.', :institution , 317, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Beneficios Sociales e Indemnizaciones Vtas'),
        (13495, '5.2.1.2.1', 'Sueldos Unificados Adm.', :institution , 376, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Sueldos, Salarios y demás Remuneraciones Adm.'),
        (13569, '5.2.2.1.1', 'Perdida en Inversiones en Asociadas/Subsidiarias y otras', :institution , 450, 13568, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Perdida en Inversiones en Asociadas/Subsidiarias y otras'),
        (13452, '5.2.1.1.25', 'Seguros Vtas.', :institution , 333, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Seguros y Reaseguros Vtas'),
        (13464, '5.2.1.1.37', 'Agua Vtas.', :institution , 345, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Agua, Energia, Luz y Telecomunicaciones Vtas'),
        (13476, '5.2.1.1.49', 'Amortizaciones Otros Activos Vtas.', :institution , 357, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos por Amortizaciones Otros Activos Vtas'),
        (13481, '5.2.1.1.54', 'Gastos por Deterioro Cuentas por Cobrar Vtas', :institution , 362, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos por Deterioro Cuentas por Cobrar Vtas'),
        (13508, '5.2.1.2.14', 'Servicios Contratados Adm.', :institution , 389, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Honorarios, Comisiones y Dietas a PN Adm'),
        (13526, '5.2.1.2.32', 'Pasajes Aereos Adm.', :institution , 407, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Transporte Adm'),
        (13538, '5.2.1.2.44', 'Contribuciones a Superintendencia de Compañías Adm.', :institution , 419, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Impuestos, Contribuciones y Otros Adm'),
        (13554, '5.2.1.2.60', 'Gastos por Valor Neto de realizacion de Inventarios Adm.', :institution , 435, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos por Valor Neto de realizacion Inventario Adm'),
        (13198, '1.2.1.9', 'Otras Propiedades, Planta y Equipo', :institution , 80, 13189, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otras Propiedades, Planta y Equipo'),
        (14064, '1.4.1.3', 'prueba 2', :institution , null, 14051, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'NULL'),
        (13141, '1.1.2.8', 'Provision para Cuentas Incobrables', :institution , 23, 13127, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Cuenta por Cobrar'),
        (13231, '2.1.2', 'Pasivos por Contratos de Arrendamientos Financieros', :institution , 113, 13229, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Pasivos por Contratos de Arrendamiento Financieros'),
        (13285, '2.1.7.6.4', '11.15% Aportes Patronales I.E.S.S.', :institution , 167, 13281, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Sociales con el IESS'),
        (13379, '4.2.4', 'Propinas (No Gravables)', :institution , 260, 13375, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Propinas de Actividades Ordinarias No Gravables'),
        (13547, '5.2.1.2.53', 'Gastos por Deterioro Instrumentos Financieros Adm.', :institution , 428, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos por Deterioro Instrumentos Financieros Adm'),
        (14065, '1.4.1.4', 'p3', :institution , null, 14051, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (14066, '1.4.1.5', 'test', :institution , null, 14051, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (14067, '1.4.1.6', 'eeryte', :institution , null, 14051, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13380, '4.3', 'Otros Ingresos Financieros', :institution , 261, 13362, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13122, '1.1.1', 'Efectivo y Equivalentes a Efectivo', :institution , 4, 13121, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13127, '1.1.2', 'Activos Financieros', :institution , 9, 13121, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13142, '1.1.3', 'Inventario', :institution , 24, 13121, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13155, '1.1.4', 'Servicios y otros Pagos Anticipados', :institution , 37, 13121, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13160, '1.1.5', 'Activos por Impuestos Corrientes', :institution , 42, 13121, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Anticipos Entregados'),
        (13123, '1.1.1.1', 'Caja', :institution , 5, 13122, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Caja'),
        (13125, '1.1.1.3', 'Bancos', :institution , 7, 13122, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Banco'),
        (13126, '1.1.1.4', 'Banco dinero electrónico', :institution , 8, 13122, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Banco dinero electrónico'),
        (13128, '1.1.2.1', 'Activos Financieros con cambios en resultados', :institution , 10, 13127, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Activos Financieros con Cambios en Resultados'),
        (13129, '1.1.2.2', 'Activos Financieros Disponibles para la Venta', :institution , 11, 13127, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Activos Financieros Disponibles para la Venta'),
        (13130, '1.1.2.3', 'Activos Financieros mantenidos hasta el vencimiento', :institution , 12, 13127, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Activos Financieros mantenidos hasta el vencimiento'),
        (13131, '1.1.2.4', '(-) Provisión por Deterioro', :institution , 13, 13127, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, '(-) Provisión por Deterioro'),
        (13132, '1.1.2.5', 'Cuentas por Cobrar', :institution , 14, 13127, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13135, '1.1.2.6', 'Documentos por Cobrar', :institution , 17, 13127, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Cuenta por Cobrar del Exterior'),
        (13140, '1.1.2.7', 'Otras Cuentas por Cobrar', :institution , 22, 13127, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Cuenta por Cobrar'),
        (13143, '1.1.3.1', 'Materia Prima', :institution , 25, 13142, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Materia Prima'),
        (13144, '1.1.3.2', 'Producto en Proceso', :institution , 26, 13142, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Productos en Proceso'),
        (13145, '1.1.3.3', 'Suministros o materiales a ser consumidos en el proceso de producción', :institution , 27, 13142, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Suministros o Materiales a ser Consumidos en el Proceso de Producción'),
        (13146, '1.1.3.4', 'Suministros o materiales a ser consumidos en la prestación de servicios', :institution , 28, 13142, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Suministros o Materiales a ser Consumidos en la prestación de Servicios'),
        (13147, '1.1.3.5', 'Productos terminados y mercadería producidos por la compañía', :institution , 29, 13142, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Productos terminados y mercadería producidos por la compañía'),
        (13148, '1.1.3.6', 'Productos terminados y mercadería comprados a terceros', :institution , 30, 13142, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Productos terminados y mercadería comprados a terceros'),
        (13149, '1.1.3.7', 'Mercaderías en Transito', :institution , 31, 13142, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Mercaderías en Transito'),
        (13150, '1.1.3.8', 'Obras en Construcción', :institution , 32, 13142, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obras en Construcción'),
        (13151, '1.1.3.9', 'Inventarios repuestos, herramientas y accesorios', :institution , 33, 13142, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Inventarios, repuestos, herramientas y accesorios'),
        (13156, '1.1.4.1', 'Seguros', :institution , 38, 13155, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Seguros pagados por Anticipado'),
        (13157, '1.1.4.2', 'Arriendos', :institution , 39, 13155, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Arriendos Pagados por Anticipado'),
        (13158, '1.1.4.3', 'Anticipo a Proveedores', :institution , 40, 13155, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Anticipo Proveedores'),
        (13159, '1.1.4.4', 'Otros Anticipos Entregados', :institution , 41, 13155, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Anticipos Entregados'),
        (13161, '1.1.5.1', 'IVA sobre Compras', :institution , 43, 13160, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Anticipos Entregados'),
        (13152, '1.1.3.10', 'Otros Inventarios', :institution , 34, 13142, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Inventarios'),
        (13153, '1.1.3.11', '(-) Provisión de Inventarios por valor neto de realización', :institution , 35, 13142, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, '(-) Provisión de Inventarios por valor neto de realización'),
        (13154, '1.1.3.12', '(-) Provisión de Inventarios por deterioro', :institution , 36, 13142, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, '(-) Provisión de Inventarios por deterioro'),
        (13133, '1.1.2.5.1', 'Clientes Comerciales', :institution , 15, 13132, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Cuenta por Cobrar'),
        (13134, '1.1.2.5.2', 'Clientes Comerciales Exterior', :institution , 16, 13132, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Cuenta por Cobrar del Exterior'),
        (13136, '1.1.2.6.1', 'Clientes Comerciales', :institution , 18, 13135, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Documento por Cobrar'),
        (13137, '1.1.2.6.2', 'Socios o Accionistas', :institution , 19, 13135, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Documento por Cobrar Relacionadas'),
        (13138, '1.1.2.6.3', 'Funcionarios y/o Empleados', :institution , 20, 13135, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Documento por Cobrar Relacionadas'),
        (13139, '1.1.2.6.4', 'Compañías Relacionadas', :institution , 21, 13135, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Documento por Cobrar Relacionadas'),
        (13162, '1.1.5.1.1', 'IVA sobre Compras', :institution , 44, 13161, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Credito Tributario a favor de la Empresa (IVA)'),
        (13164, '1.1.5.2.1', '30% Bienes', :institution , 46, 13163, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Credito Tributario a favor de la Empresa (IVA)'),
        (13165, '1.1.5.2.2', '70% Servicios', :institution , 47, 13163, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Credito Tributario a favor de la Empresa (IVA)'),
        (13167, '1.1.5.2.4', '100% Exportadores', :institution , 49, 13163, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Credito Tributario a favor de la Empresa (IVA)'),
        (13168, '1.1.5.2.5', '10% Bienes (Contribuyentes Especiales)', :institution , 50, 13163, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Credito Tributario a favor de la Empresa (IVA)'),
        (13121, '1.1', 'Activo Corriente', :institution , 3, 13120, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13189, '1.2.1', 'Propiedad, Planta y Equipos', :institution , 71, 13188, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13204, '1.2.2', 'Propiedades de Inversion', :institution , 86, 13188, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13209, '1.2.3', 'Activos Biológicos', :institution , 91, 13188, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13214, '1.2.4', 'Intangibles', :institution , 96, 13188, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13376, '4.2.1', 'Fletes', :institution , 257, 13375, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Fletes de Actividades Ordinarias'),
        (13171, '1.1.5.3', 'Retenciones en la Fuente del Impuesto a la Renta', :institution , 53, 13160, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13183, '1.1.5.4', 'Anticipo de Impuesto a Renta', :institution , 65, 13160, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13190, '1.2.1.1', 'Terrenos', :institution , 72, 13189, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Terrenos'),
        (13191, '1.2.1.2', 'Edificios', :institution , 73, 13189, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Edificios'),
        (13192, '1.2.1.3', 'Construcciones en Curso', :institution , 74, 13189, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Construcciones en Curso'),
        (13193, '1.2.1.4', 'Instalaciones', :institution , 75, 13189, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Instalaciones'),
        (13194, '1.2.1.5', 'Muebles y Enseres', :institution , 76, 13189, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Muebles y Enseres'),
        (13195, '1.2.1.6', 'Maquinarias y Equipos', :institution , 77, 13189, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Maquinarias y Equipos'),
        (13196, '1.2.1.7', 'Equipos de Computación', :institution , 78, 13189, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Equipos de Computación'),
        (13205, '1.2.2.1', 'Terrenos', :institution , 87, 13204, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Terrenos de Inversión'),
        (13206, '1.2.2.2', 'Edificios', :institution , 88, 13204, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Edificios de Inversión'),
        (13207, '1.2.2.3', '(-) Depreciación Acumulada de Propiedades de Inversión', :institution , 89, 13204, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, '(-) Deterioro Acumulado de Propiedades de Inversión'),
        (13208, '1.2.2.4', '(-) Deterioro Acumulado de Propiedades de Inversión', :institution , 90, 13204, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, '(-) Deterioro Acumulado de Propiedades de Inversión'),
        (13210, '1.2.3.1', 'Animales Vivos en Crecimiento', :institution , 92, 13209, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Activo fijo'),
        (13211, '1.2.3.2', 'Animales Vivos en Producción', :institution , 93, 13209, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Activo fijo'),
        (13212, '1.2.3.3', '(-) Depreciación Activos Biológicos', :institution , 94, 13209, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Activo fijo'),
        (13213, '1.2.3.4', '(-) Deterioro Activos Biológicos', :institution , 95, 13209, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Activo fijo'),
        (13215, '1.2.4.1', 'Plusvalías', :institution , 97, 13214, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Plusvalías'),
        (13216, '1.2.4.2', 'Marcas, Patentes, Derechos de Llave, Cuotas Patrimoniales', :institution , 98, 13214, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Marcas, Patentes, Derechos de Llave, Cuotas Patrimoniales'),
        (13217, '1.2.4.3', 'Activos de Exploración y Explotación', :institution , 99, 13214, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Activos de Exploración y Explotación'),
        (13218, '1.2.4.4', '(-) Amortización Acumulada de Activos Intangibles', :institution , 100, 13214, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, '(-) Amortización Acumulada de Activos Intangibles'),
        (13319, '2.2.2.2', 'Documentos por Pagar', :institution , 200, 13314, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13199, '1.2.1.10', 'Repuestos y Herramientas', :institution , 81, 13189, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Repuestos y Herramientas'),
        (13201, '1.2.1.12', '(-) Deterioro Acumulado Propiedades, Planta y Equipo', :institution , 83, 13189, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, '(-) Deterioro Acumulado Propiedades, Planta y Equipo'),
        (13202, '1.2.1.13', 'Activos de Exploración y Explotación', :institution , 84, 13189, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Activos de Exploración y Explotación'),
        (13203, '1.2.1.14', 'Naves, Aeronaves, Barcazas y Similares', :institution , 85, 13189, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Naves, Aeronaves, Barcazas y Similares'),
        (13169, '1.1.5.2.6', '20% Servicios (Contribuyentes Especiales)', :institution , 51, 13163, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Credito Tributario a favor de la Empresa (IVA)'),
        (13170, '1.1.5.2.7', '50% Servicios (Exportadores de Recursos Naturales No Renovables)', :institution , 52, 13163, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Credito Tributario a favor de la Empresa (IVA)'),
        (13172, '1.1.5.3.1', '1% Bienes Muebles de Naturaleza Corporal', :institution , 54, 13171, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Credito Tributario a favor de la Empresa (IR)'),
        (13173, '1.1.5.3.2', '2% Servicios', :institution , 55, 13171, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Credito Tributario a favor de la Empresa (IR)'),
        (13174, '1.1.5.3.3', '8% Honorarios, Arrendamientos, Docencia, Deportistas', :institution , 56, 13171, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Credito Tributario a favor de la Empresa (IR)'),
        (13175, '1.1.5.3.4', '25% Extranjeros No Residentes', :institution , 57, 13171, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Credito Tributario a favor de la Empresa (IR)'),
        (13176, '1.1.5.3.5', '10% Honorarios Profesionales y Dietas', :institution , 58, 13171, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Credito Tributario a favor de la Empresa (IR)'),
        (13177, '1.1.5.3.6', '15% Loterías, Rifas y Similares', :institution , 59, 13171, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Credito Tributario a favor de la Empresa (IR)'),
        (13178, '1.1.5.3.7', 'Otras Retenciones Aplicables al Cód. 343', :institution , 60, 13171, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Credito Tributario a favor de la Empresa (IR)'),
        (13179, '1.1.5.3.8', '2.75% Servicios', :institution , 61, 13171, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Credito Tributario a favor de la Empresa (IR)'),
        (13180, '1.1.5.3.9', '1.75% Bienes Muebles de Naturaleza Corporal', :institution , 62, 13171, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Credito Tributario a favor de la Empresa (IR)'),
        (13184, '1.1.5.4.1', '1era. Cuota Julio', :institution , 66, 13183, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Anticipo de Impuesto a la Renta'),
        (13185, '1.1.5.4.2', '2da. Cuota Septiembre', :institution , 67, 13183, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Anticipo de Impuesto a la Renta'),
        (13181, '1.1.5.3.10', '1.75% Régimen Microempresas', :institution , 63, 13171, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Credito Tributario a favor de la Empresa (IR)'),
        (13182, '1.1.5.3.11', 'Otras autoretenciones', :institution , 64, 13171, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Credito Tributario a favor de la Empresa (IR)'),
        (13187, '1.1.7', 'Otros Activos Corrientes', :institution , 69, 13121, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Activos Corrientes'),
        (13227, '1.2.7', 'Otros Activos No Corrientes', :institution , 109, 13188, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Activos No Corrientes'),
        (13230, '2.1.1', 'Pasivos Financieros con Cambios en Resultados', :institution , 112, 13229, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Pasivos Financieros con cambio en Resultados'),
        (13232, '2.1.3', 'Cuentas y Documentos por Pagar', :institution , 114, 13229, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13239, '2.1.4', 'Obligaciones Con Instituciones Financieras', :institution , 121, 13229, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13243, '2.1.5', 'Provisiones', :institution , 125, 13229, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Cuenta por Pagar'),
        (13246, '2.1.6', 'Porcion Corriente de Obligaciones Emitidas', :institution , 128, 13229, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Porción Corriente de Obligaciones Emitidas'),
        (13247, '2.1.7', 'Otras Obligaciones Corrientes', :institution , 129, 13229, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13377, '4.2.2', 'Multas', :institution , 258, 13375, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Multas de Actividades Ordinarias'),
        (13220, '1.2.4.6', 'Otros Activos Intangibles', :institution , 102, 13214, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Activos Intangibles'),
        (13223, '1.2.6.1', 'Activos Financieros mantenidos hasta el Vencimiento No Corrientes', :institution , 105, 13222, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Activos Financieros hasta el vencimiento No Corrientes'),
        (13224, '1.2.6.2', '(-) Deterioro de Activos Financieros hasta el Vencimiento No Corrientes', :institution , 106, 13222, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, '(-) Deterioro de Activos Financieros hasta el vencimiento No Corrientes'),
        (13225, '1.2.6.3', 'Documentos y Cuentas por Cobrar No Corrientes', :institution , 107, 13222, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Documentos y Cuentas por Cobrar No Corrientes'),
        (13226, '1.2.6.4', '(-) Cuentas Incobrables de Activos Financieros No Corrientes', :institution , 108, 13222, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, '(-) Cuenta Incobrables de Activos Financieros No Corrientes'),
        (13233, '2.1.3.1', 'Cuentas por Pagar', :institution , 115, 13232, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13236, '2.1.3.2', 'Documentos por Pagar', :institution , 118, 13232, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13240, '2.1.4.1', 'Obligaciones con Instituciones Financieras Locales', :institution , 122, 13239, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones con Instituciones Financieras Locales'),
        (13241, '2.1.4.2', 'Obligaciones con Instituciones Financieras del Exterior', :institution , 123, 13239, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones con Instituciones Financieras del Exterior'),
        (13242, '2.1.4.3', 'Tarjeta de crédito (Pago)', :institution , 124, 13239, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Cuenta por Pagar (Tarjetas de Crédito)'),
        (13244, '2.1.5.1', 'Provisiones Locales', :institution , 126, 13243, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Cuenta por Pagar'),
        (13245, '2.1.5.2', 'Provisiones del Exterior', :institution , 127, 13243, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Cuenta por Pagar'),
        (13248, '2.1.7.1', 'Retenciones del I.E.S.S.', :institution , 130, 13247, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13252, '2.1.7.2', 'Retenciones en la Fuente de Impuestos a la Renta', :institution , 134, 13247, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13264, '2.1.7.3', 'Retenciones del Impuesto al Valor Agregado', :institution , 146, 13247, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13234, '2.1.3.1.1', 'Proveedores', :institution , 116, 13233, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Cuenta por Pagar'),
        (13235, '2.1.3.1.2', 'Proveedores Exterior', :institution , 117, 13233, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Cuenta por Pagar del Exterior'),
        (13238, '2.1.3.2.2', 'Documentos por Pagar Proveedores Exterior', :institution , 120, 13236, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Documento por Pagar del Exterior'),
        (13249, '2.1.7.1.1', '9.35% Aportes Individuales', :institution , 131, 13248, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Sociales con el IESS'),
        (13250, '2.1.7.1.2', 'Prestamos Quirografarios', :institution , 132, 13248, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Sociales con el IESS'),
        (13251, '2.1.7.1.3', 'Prestamos Hipotecarios', :institution , 133, 13248, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Sociales con el IESS'),
        (13253, '2.1.7.2.1', '1% Bienes Muebles de Naturaleza Corporal', :institution , 135, 13252, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Tributarias'),
        (13254, '2.1.7.2.2', '2% Servicios', :institution , 136, 13252, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Tributarias'),
        (13255, '2.1.7.2.3', '8% Honorarios, Arrendamientos, Docencia, Deportistas', :institution , 137, 13252, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Tributarias'),
        (13256, '2.1.7.2.4', '25% Extranjeros No Residentes', :institution , 138, 13252, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Tributarias'),
        (13257, '2.1.7.2.5', '10% Honorarios Profesionales y Dietas', :institution , 139, 13252, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Tributarias'),
        (13258, '2.1.7.2.6', '15% Loterías, Rifas y Similares', :institution , 140, 13252, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Tributarias'),
        (13259, '2.1.7.2.7', 'Otras Retenciones Aplicables al Cód. 343', :institution , 141, 13252, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Tributarias'),
        (13260, '2.1.7.2.8', '2.75% Servicios', :institution , 142, 13252, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Tributarias'),
        (13265, '2.1.7.3.1', '30% Bienes', :institution , 147, 13264, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Tributarias'),
        (13266, '2.1.7.3.2', '70% Servicios', :institution , 148, 13264, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Tributarias'),
        (13267, '2.1.7.3.3', '100% Honorarios, Arrendamientos', :institution , 149, 13264, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Tributarias'),
        (13268, '2.1.7.3.4', '100% Exportadores', :institution , 150, 13264, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Tributarias'),
        (13269, '2.1.7.3.5', '10% Bienes (Contribuyentes Especiales)', :institution , 151, 13264, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Tributarias'),
        (13270, '2.1.7.3.6', '20% Servicios (Contribuyentes Especiales)', :institution , 152, 13264, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Tributarias'),
        (13262, '2.1.7.2.10', '1.75% Régimen Microempresas', :institution , 144, 13252, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Tributarias'),
        (13263, '2.1.7.2.11', 'Otras autoretenciones', :institution , 145, 13252, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Tributarias'),
        (13228, '2', 'Pasivos', :institution , 110, 0, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13222, '1.2.6', 'Activos Financieros No Corrientes', :institution , 104, 13188, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13313, '2.2.1', 'Pasivos por Contratos de Arrendamiento Financiero', :institution , 195, 13312, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Pasivos por Contratos de Arrendamiento Financiero No Corrientes'),
        (13314, '2.2.2', 'Cuentas y Documentos por Pagar', :institution , 196, 13312, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13322, '2.2.3', 'Obligaciones con Instituciones Financieras', :institution , 203, 13312, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13378, '4.2.3', 'Intereses', :institution , 259, 13375, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Intereses de Actividades Ordinarias'),
        (13305, '2.1.10', 'Anticipos de Clientes', :institution , 187, 13229, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Anticipo de Clientes'),
        (13307, '2.1.12', 'Porción Corriente de Provisiones por Beneficios a Empleados', :institution , 189, 13229, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13310, '2.1.13', 'Otros Pasivos Corrientes', :institution , 192, 13229, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Pasivos Corrientes'),
        (13311, '2.1.14', 'Anticipo Gastos Administrativos', :institution , 193, 13229, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Anticipo de Clientes'),
        (13272, '2.1.7.4', 'IVA Sobre Ventas', :institution , 154, 13247, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13274, '2.1.7.5', 'Impuestos por Pagar', :institution , 156, 13247, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13281, '2.1.7.6', 'Beneficios Sociales por Pagar', :institution , 163, 13247, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13290, '2.1.7.7', 'Nominas', :institution , 172, 13247, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Sociales con el IESS'),
        (13292, '2.1.7.8', 'Participación de Trabajadores', :institution , 174, 13247, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13295, '2.1.7.9', 'Dividendos por Pagar a Socios', :institution , 177, 13247, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Dividendos por Pagar'),
        (13297, '2.1.8.1', 'Cuenta por Pagar Socios o Accionistas', :institution , 179, 13296, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Cuenta por Pagar Relacionadas'),
        (13298, '2.1.8.2', 'Cuenta por Pagar Funcionarios y/o Empleados', :institution , 180, 13296, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Cuenta por Pagar Relacionadas'),
        (13299, '2.1.8.3', 'Cuenta por Pagar Compañías Relacionadas', :institution , 181, 13296, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Cuenta por Pagar Relacionadas'),
        (13300, '2.1.8.4', 'Cuenta por Pagar Compañías Relacionadas del Exterior', :institution , 182, 13296, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Cuenta por Pagar Relacionadas'),
        (13301, '2.1.8.5', 'Documentos por Pagar Funcionarios y/o Empleados', :institution , 183, 13296, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Documento por Pagar Relacionadas'),
        (13302, '2.1.8.6', 'Documentos por Pagar Compañías Relacionadas', :institution , 184, 13296, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Documento por Pagar Relacionadas'),
        (13303, '2.1.8.7', 'Cuenta por Pagar Funcionarios y/o Empleados (10% Servicio)', :institution , 185, 13296, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Cuenta por Pagar Relacionadas'),
        (13315, '2.2.2.1', 'Cuentas por Pagar', :institution , 197, 13314, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13318, '2.2.2.2', 'Documentos por Pagar', :institution , 200, 13314, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13308, '2.1.12.1', 'Jubilación Patronal', :institution , 190, 13307, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Jubilación Patronal'),
        (13309, '2.1.12.2', 'Otros Beneficios a largo Plazo para los Empleados', :institution , 191, 13307, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Cuenta por Pagar'),
        (13517, '5.2.1.2.23', 'Combustible Adm.', :institution , 398, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Combustibles Adm'),
        (13271, '2.1.7.3.7', '50% Servicios (Exportadores de Recursos Naturales No Renovables)', :institution , 153, 13264, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Tributarias'),
        (13273, '2.1.7.4.1', 'IVA sobre Ventas', :institution , 155, 13272, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Tributarias'),
        (13275, '2.1.7.5.1', 'Impuesto a la Renta Cía.', :institution , 157, 13274, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Impuesto a la Renta por Pagar'),
        (13276, '2.1.7.5.2', 'Impuesto a la Junta de Beneficiencia', :institution , 158, 13274, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Tributarias'),
        (13277, '2.1.7.5.3', 'Impuesto a los Activos Totales', :institution , 159, 13274, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Tributarias'),
        (13278, '2.1.7.5.4', 'Impuestos a la Universidad de Guayaquil', :institution , 160, 13274, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Tributarias'),
        (13279, '2.1.7.5.5', 'Impuestos Prediales', :institution , 161, 13274, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Tributarias'),
        (13280, '2.1.7.5.6', 'Impuesto a los Consumos Especiales', :institution , 162, 13274, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Tributarias'),
        (13282, '2.1.7.6.1', 'Décimo Tercer Sueldo', :institution , 164, 13281, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Sociales con el IESS'),
        (13283, '2.1.7.6.2', 'Décimo Cuarto Sueldo', :institution , 165, 13281, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Sociales con el IESS'),
        (13284, '2.1.7.6.3', 'Vacaciones', :institution , 166, 13281, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Sociales con el IESS'),
        (13286, '2.1.7.6.5', '1% Secap - Iece', :institution , 168, 13281, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Sociales con el IESS'),
        (13287, '2.1.7.6.6', 'Fondos de Reservas', :institution , 169, 13281, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Sociales con el IESS'),
        (13288, '2.1.7.6.7', 'Provisión de Jubilación Patronal', :institution , 170, 13281, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Sociales con el IESS'),
        (13289, '2.1.7.6.8', 'Provisión por Desahucio', :institution , 171, 13281, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Sociales con el IESS'),
        (13291, '2.1.7.7.1', 'Sueldos por Pagar', :institution , 173, 13290, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Sociales con el IESS'),
        (13293, '2.1.7.8.1', '10% Trabajadores en General', :institution , 175, 13292, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Participación Trabajadores por Pagar del Ejercicio'),
        (13294, '2.1.7.8.2', '5% Cargas Familiares', :institution , 176, 13292, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Participación Trabajadores por Pagar del Ejercicio'),
        (13316, '2.2.2.1.1', 'Proveedores No Corriente', :institution , 198, 13315, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Cuenta por Pagar No Corriente'),
        (13320, '2.2.2.2.1', 'Documento por Pagar Proveedores No Corriente', :institution , 201, 13318, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Documento por Pagar No Corriente'),
        (13321, '2.2.2.2.2', 'Documentos por Pagar Proveedores Exterior No Corriente', :institution , 202, 13318, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Documento por Pagar del Exterior No Corriente'),
        (13296, '2.1.8', 'Cuentas por Pagar Diversas/Relacionadas', :institution , 178, 13229, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13363, '4.1', 'Ingresos de Actividades Ordinarias', :institution , 244, 13362, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13325, '2.2.4', 'Cuenta por Pagar Diversas/Relacionadas', :institution , 206, 13312, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13330, '2.2.5', 'Obligaciones Emitidas', :institution , 211, 13312, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones Emitidas No Corrientes'),
        (13331, '2.2.6', 'Anticipos de Clientes', :institution , 212, 13312, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Anticipos de Clientes No Corrientes'),
        (13332, '2.2.7', 'Provisiones por Beneficios a Empleados', :institution , 213, 13312, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13335, '2.2.8', 'Pasivo Diferido', :institution , 216, 13312, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13338, '2.2.9', 'Otros Pasivos No Corrientes', :institution , 219, 13312, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Pasivos No Corrientes'),
        (13341, '3.1.1', 'Capital Social', :institution , 222, 13340, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13345, '3.1.3', 'Prima por Emisión Primaria de Acciones', :institution , 226, 13340, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Prima por emisión Primaria de Acciones'),
        (13346, '3.1.4', 'Reservas', :institution , 227, 13340, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13350, '3.1.5', 'Otros Resultados Integrales', :institution , 231, 13340, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13355, '3.1.6', 'Resultados Acumulados', :institution , 236, 13340, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13358, '3.1.7', 'Resultado del Ejercicio', :institution , 239, 13340, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13361, '3.2.1', 'Participación No Controladas', :institution , 242, 13360, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Participación No Controladas'),
        (13364, '4.1.1', 'Venta de Bienes', :institution , 245, 13363, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Ingresos por Ventas de Bienes'),
        (13365, '4.1.2', 'Prestación de Servicios', :institution , 246, 13363, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Ingresos por Prestación de Servicios'),
        (13366, '4.1.3', 'Devoluciones sobre Ventas', :institution , 247, 13363, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Devoluciones sobre Ventas'),
        (13367, '4.1.4', 'Rebaja y/o Descuentos sobre Ventas', :institution , 248, 13363, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Rebaja y/o Descuentos sobre Ventas'),
        (13368, '4.1.5', 'Ingresos por Regalías Cuotas y Comisiones', :institution , 249, 13363, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Ingresos por Regalías,  Cuotas y Comisiones'),
        (13369, '4.1.6', 'Ingresos por Contratos de Intermediación', :institution , 250, 13363, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Ingresos por Contratos de Intermediación'),
        (13370, '4.1.7', 'Ingresos por Primas y Prestaciones', :institution , 251, 13363, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Ingresos Primas y Prestaciones'),
        (13371, '4.1.8', 'Contratos de Construcción', :institution , 252, 13363, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Contratos de Construcción'),
        (13372, '4.1.9', 'Subvenciones del Gobierno', :institution , 253, 13363, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Subvenciones de Gobierno'),
        (13373, '4.1.10', 'Ingresos por Dividendos', :institution , 254, 13363, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Ingresos por Dividendos'),
        (13374, '4.1.11', 'Otros Ingresos de Actividades Ordinarias', :institution , 255, 13363, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Ingresos de Actividades Ordinarias'),
        (13326, '2.2.4.1', 'Cuenta por Pagar Socios o Accionistas No Corrientes', :institution , 207, 13325, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Cuenta por Pagar Relacionadas No Corriente'),
        (13327, '2.2.4.2', 'Cuenta por Pagar Funcionarios y/o Empleados No Corriente', :institution , 208, 13325, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Cuenta por Pagar Relacionadas No Corriente'),
        (13328, '2.2.4.3', 'Cuenta por Pagar Compañias Relacionadas No Corriente', :institution , 209, 13325, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Cuenta por Pagar Relacionadas No Corriente'),
        (13329, '2.2.4.4', 'Cuenta por Pagar Compañías Relacionadas del Exterior No Corriente', :institution , 210, 13325, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Cuenta por Pagar Compañías Relacionadas del Ext No Corriente'),
        (13333, '2.2.7.1', 'Jubilación Patronal', :institution , 214, 13332, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Obligaciones por Beneficios de Ley No Corrientes'),
        (13334, '2.2.7.2', 'Otros Beneficios No Corrientes Para los Empleados', :institution , 215, 13332, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Beneficios para los Empleados No Corrientes'),
        (13336, '2.2.8.1', 'Ingresos Diferidos', :institution , 217, 13335, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Pasivos por Ingresos Diferidos'),
        (13337, '2.2.8.2', 'Pasivos por Impuestos Diferidos', :institution , 218, 13335, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Pasivos por Impuestos Diferidos'),
        (13342, '3.1.1.1', 'Capital Social suscrito o pagado', :institution , 223, 13341, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Capital Social Suscrito o Pagado'),
        (13343, '3.1.1.2', '(-) Capital suscrito no pagado, acciones en tesorería', :institution , 224, 13341, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, '(-) Capital Suscrito no Pagado, Acciones en Tesorería'),
        (13347, '3.1.4.1', 'Legal', :institution , 228, 13346, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Legal'),
        (13348, '3.1.4.2', 'Facultativa y Estatutaria', :institution , 229, 13346, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Facultativa y Estatutaria'),
        (13349, '3.1.4.3', 'Reserva de Capital', :institution , 230, 13346, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Reserva de Capital'),
        (13351, '3.1.5.1', 'Superávit por Valuación de Activos Financieros', :institution , 232, 13350, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Superávit por Valuación de Activos Financieros'),
        (13352, '3.1.5.2', 'Superávit por Revaluación de Propiedad, Plantas y Equipos', :institution , 233, 13350, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Superávit por Revaluación de Propiedad, Plantas y Equipos'),
        (13353, '3.1.5.3', 'Superávit por Valuación de Activos Intagibles', :institution , 234, 13350, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Superávit por Revaluacion de Activos Intangibles'),
        (13354, '3.1.5.4', 'Otros Superávit por Revaluación', :institution , 235, 13350, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Superávit por Revaluacion'),
        (13356, '3.1.6.1', 'Resultados Acumulados', :institution , 237, 13355, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Ganancias Acumuladas'),
        (13359, '3.1.7.1', 'Resultado del Ejercicio', :institution , 240, 13358, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Ganancia neta el Ejercicio'),
        (13362, '4', 'Ingresos', :institution , 243, 0, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13339, '3', 'Patrimonio', :institution , 220, 0, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13360, '3.2', 'Participación No Controladas', :institution , 241, 13339, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13425, '5.2', 'Gastos', :institution , 306, 13387, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13381, '4.3.1', 'Dividendos Financieros', :institution , 262, 13380, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Dividendos Financieros'),
        (13382, '4.3.2', 'Intereses Financieros', :institution , 263, 13380, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Intereses Financieros'),
        (13384, '4.3.4', 'Valuación de Instrumentos Financieros', :institution , 265, 13380, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Valuación de Instrumentos Financieros'),
        (13385, '4.3.5', 'Otras Rentas', :institution , 266, 13380, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otras Rentas'),
        (13389, '5.1.1', 'Materiales Utilizados o Productos Vendidos', :institution , 270, 13388, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13396, '5.1.2', 'Mano de Obra Directa', :institution , 277, 13388, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13406, '5.1.3', 'Mano de Obra Indirecta', :institution , 287, 13388, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13416, '5.1.4', 'Costos Indirectos de Fabricación', :institution , 297, 13388, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Sueldos y Beneficios Sociales - Indirecta'),
        (13426, '5.2.1', 'Gastos de Actividades Ordinarias', :institution , 307, 13425, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13390, '5.1.1.1', 'Bienes No Producidos', :institution , 271, 13389, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Bienes No Producidos'),
        (13391, '5.1.1.2', 'Bienes No Producidos Importados', :institution , 272, 13389, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Bienes No Producidos Importados'),
        (13392, '5.1.1.3', 'Materia Prima', :institution , 273, 13389, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Materia Prima - Costo de Venta'),
        (13393, '5.1.1.4', 'Materia Prima Importada', :institution , 274, 13389, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Materia Prima Importada - Costo de Venta'),
        (13394, '5.1.1.5', 'Productos en Proceso', :institution , 275, 13389, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Productos en Proceso - Costo de Venta'),
        (13395, '5.1.1.6', 'Productos Terminados', :institution , 276, 13389, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Productos Terminados - Costo de Venta'),
        (13397, '5.1.2.1', 'Sueldos Mano de Obra Directa', :institution , 278, 13396, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Sueldos y Beneficios Sociales - Directa'),
        (13399, '5.1.2.3', 'Décimo Tercer Sueldo Mano de Obra Directa', :institution , 280, 13396, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Sueldos y Beneficios Sociales - Directa'),
        (13400, '5.1.2.4', 'Decimo Cuarto Sueldo Mano de Obra Directa', :institution , 281, 13396, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Sueldos y Beneficios Sociales - Directa'),
        (13401, '5.1.2.5', 'Vacaciones Mano de Obra Directa', :institution , 282, 13396, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Sueldos y Beneficios Sociales - Directa'),
        (13402, '5.1.2.6', 'Aportes Patronales al I.E.S.S. Mano de Obra Directa', :institution , 283, 13396, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Sueldos y Beneficios Sociales - Directa'),
        (13403, '5.1.2.7', 'Secap - Iece Mano de Obra Directa', :institution , 284, 13396, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Sueldos y Beneficios Sociales - Directa'),
        (13404, '5.1.2.8', 'Fondos de Reserva Mano de Obra Directa', :institution , 285, 13396, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Sueldos y Beneficios Sociales - Directa'),
        (13405, '5.1.2.9', 'Gastos Planes de Beneficios a Empleados Mano de Obra Directa', :institution , 286, 13396, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Planes de Beneficios a Empleados - Directa'),
        (13407, '5.1.3.1', 'Sueldos Mano de Obra Indirecta', :institution , 288, 13406, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Sueldos y Beneficios Sociales - Indirecta'),
        (13408, '5.1.3.2', 'Sobretiempos Mano de Obra Indirecta', :institution , 289, 13406, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Sueldos y Beneficios Sociales - Indirecta'),
        (13409, '5.1.3.3', 'Décimo Tercer Sueldo Mano de Obra Indirecta', :institution , 290, 13406, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Sueldos y Beneficios Sociales - Indirecta'),
        (13410, '5.1.3.4', 'Décimo Cuarto Sueldo Mano de Obra Indirecta', :institution , 291, 13406, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Sueldos y Beneficios Sociales - Indirecta'),
        (13411, '5.1.3.5', 'Vacaciones Mano de Obra Indirecta', :institution , 292, 13406, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Sueldos y Beneficios Sociales - Indirecta'),
        (13413, '5.1.3.7', 'Secap - Iece Mano de Obra Indirecta', :institution , 294, 13406, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Sueldos y Beneficios Sociales - Indirecta'),
        (13414, '5.1.3.8', 'Fondos de Reserva Mano de Obra Indirecta', :institution , 295, 13406, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Sueldos y Beneficios Sociales - Indirecta'),
        (13415, '5.1.3.9', 'Gastos Planes de Beneficios a Empleados - Indirecta', :institution , 296, 13406, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Sueldos y Beneficios Sociales - Indirecta'),
        (13417, '5.1.4.1', 'Depreciación Propiedades, Plantas y Equipos', :institution , 298, 13416, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Depreciación Propiedades, Plantas y Equipos'),
        (13418, '5.1.4.2', 'Depreciación de Activos Biológicos', :institution , 299, 13416, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Depreciación de Activos Biológicos'),
        (13419, '5.1.4.3', 'Deterioro de Propiedad, Planta y Equipo', :institution , 300, 13416, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Deterioro de Propiedad, Planta y Equipo'),
        (13420, '5.1.4.4', 'Efecto valor neto de realización de Inventarios', :institution , 301, 13416, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Efecto valor neto de realización de Inventarios'),
        (13421, '5.1.4.5', 'Gasto por Garantías en Venta de Productos o Servicios', :institution , 302, 13416, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gasto por Garantías en Venta de Productos o Servicios'),
        (13422, '5.1.4.6', 'Mantenimiento y Reparaciones Costos', :institution , 303, 13416, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Mantenimiento y Reparaciones Costos'),
        (13423, '5.1.4.7', 'Suministros, Materiales y Repuestos Costos', :institution , 304, 13416, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Suministros, Materiales y Repuestos Costos'),
        (13424, '5.1.4.8', 'Otros Costos de Producción', :institution , 305, 13416, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Costos de Producción'),
        (13427, '5.2.1.1', 'Ventas', :institution , 308, 13426, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13428, '5.2.1.1.1', 'Sueldos Unificados Vtas.', :institution , 309, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Sueldos y Beneficios Sociales'),
        (13429, '5.2.1.1.2', 'Sobretiempos Vtas.', :institution , 310, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Sueldos, Salarios y demás Remuneraciones Vtas'),
        (13430, '5.2.1.1.3', 'Gratificaciones Vtas.', :institution , 311, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Sueldos, Salarios y demás Remuneraciones Vtas'),
        (13387, '5', 'Costos y Gastos', :institution , 268, 0, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13388, '5.1', 'Costos de Venta y Producción', :institution , 269, 13387, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13433, '5.2.1.1.6', 'Secap - Iece Vtas.', :institution , 314, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Aportes a la Seguridad Social Vtas'),
        (13434, '5.2.1.1.7', 'Fondos de Reserva Vtas.', :institution , 315, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Aportes a la Seguridad Social Vtas'),
        (13435, '5.2.1.1.8', 'Décimo Tercer Sueldo Vtas.', :institution , 316, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Beneficios Sociales e Indemnizaciones Vtas'),
        (13437, '5.2.1.1.10', 'Vacaciones Vtas.', :institution , 318, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Beneficios Sociales e Indemnizaciones Vtas'),
        (13438, '5.2.1.1.11', 'Desahucio Vtas.', :institution , 319, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Beneficios Sociales e Indemnizaciones Vtas'),
        (13439, '5.2.1.1.12', 'Gastos Planes de Beneficios a Empleados Vtas', :institution , 320, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Planes de Beneficios a Empleados Vtas'),
        (13440, '5.2.1.1.13', 'Honorarios Profesionales Vtas.', :institution , 321, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Honorarios, Comisiones y Dietas a PN Vtas'),
        (13441, '5.2.1.1.14', 'Servicios Contratados Vtas.', :institution , 322, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Honorarios, Comisiones y Dietas a PN Vtas'),
        (13442, '5.2.1.1.15', 'Gastos Remuneraciones a otros trabajadores autónomos Vtas', :institution , 323, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Remuneraciones a otros trabajadores autónomos Vtas'),
        (13443, '5.2.1.1.16', 'Gastos Honorarios a extranjeros por Servicios Ocasionales Vtas', :institution , 324, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Honorarios a extranjeros por Servicios Ocasionales Vtas'),
        (13444, '5.2.1.1.17', 'Mantenimiento de Equipos Vtas.', :institution , 325, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos de Mantenimiento, Repuestos y Reparaciones Vtas'),
        (13445, '5.2.1.1.18', 'Reparaciones de Equipos Vtas.', :institution , 326, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos de Mantenimiento, Repuestos y Reparaciones Vtas'),
        (13446, '5.2.1.1.19', 'Arriendos Vtas', :institution , 327, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Arrendamiento Operativo Vtas'),
        (13447, '5.2.1.1.20', 'Comisiones Vtas', :institution , 328, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Comisiones Vtas'),
        (13448, '5.2.1.1.21', 'Publicidad y Promoción Vtas.', :institution , 329, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Promociones y Publicidad Vtas'),
        (13449, '5.2.1.1.22', 'Publicaciones y Agencias Vtas.', :institution , 330, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Promociones y Publicidad Vtas'),
        (13450, '5.2.1.1.23', 'Combustible Vtas.', :institution , 331, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Combustibles Vtas'),
        (13451, '5.2.1.1.24', 'Lubricantes Vtas.', :institution , 332, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Lubricantes Vtas'),
        (13453, '5.2.1.1.26', 'Movilización y Transporte Vtas.', :institution , 334, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Transporte Vtas'),
        (13454, '5.2.1.1.27', 'Guías de Transportes Vtas.', :institution , 335, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Transporte Vtas'),
        (13455, '5.2.1.1.28', 'Fletes Vtas.', :institution , 336, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Transporte Vtas'),
        (13456, '5.2.1.1.29', 'Gastos de Gestión Vtas.', :institution , 337, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos de Gestión Vtas'),
        (13457, '5.2.1.1.30', 'Viajes Vtas.', :institution , 338, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos de Viajes Vtas'),
        (13458, '5.2.1.1.31', 'Viajes al Exterior Vtas.', :institution , 339, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos de Viajes Vtas'),
        (13459, '5.2.1.1.32', 'Pasajes Aereos Vtas.', :institution , 340, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos de Viajes Vtas'),
        (13460, '5.2.1.1.33', 'Energía Eléctrica Vtas.', :institution , 341, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos de Viajes Vtas'),
        (13461, '5.2.1.1.34', 'Teléfonos Convencionales Vtas.', :institution , 342, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Agua, Energia, Luz y Telecomunicaciones Vtas'),
        (13462, '5.2.1.1.35', 'Celulares Vtas.', :institution , 343, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Agua, Energia, Luz y Telecomunicaciones Vtas'),
        (13463, '5.2.1.1.36', 'Internet Vtas.', :institution , 344, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Agua, Energia, Luz y Telecomunicaciones Vtas'),
        (13465, '5.2.1.1.38', 'Televisión Pagada Vtas.', :institution , 346, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Agua, Energia, Luz y Telecomunicaciones Vtas'),
        (13466, '5.2.1.1.39', 'Gastos Notariales Vtas.', :institution , 347, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Notarios y Registradores de la Propiedad Vtas'),
        (13467, '5.2.1.1.40', 'Gastos de Registro Mercantil Vtas.', :institution , 348, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Notarios y Registradores de la Propiedad Vtas'),
        (13468, '5.2.1.1.41', 'Impuesto a los Consumos Especiales Vtas.', :institution , 349, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Impuestos, Contribuciones y Otros Vtas'),
        (13469, '5.2.1.1.42', 'Impuesto a Consumos Vtas.', :institution , 350, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Impuestos, Contribuciones y Otros Vtas'),
        (13470, '5.2.1.1.43', 'Tasas y Contribuciones Vtas.', :institution , 351, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Impuestos, Contribuciones y Otros Vtas'),
        (13471, '5.2.1.1.44', 'Contribuciones a Superintendencia de Compañías Vtas.', :institution , 352, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Impuestos, Contribuciones y Otros Vtas'),
        (13472, '5.2.1.1.45', 'IVA Gasto Vtas.', :institution , 353, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Impuestos, Contribuciones y Otros Vtas'),
        (13473, '5.2.1.1.46', 'Depreciaciones Propiedades Planta y Equipos Vtas.', :institution , 354, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Impuestos, Contribuciones y Otros Vtas'),
        (13474, '5.2.1.1.47', 'Depreciaciones de Inversiones Vtas.', :institution , 355, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos por Depreciación Propiedades de Inversión Vtas'),
        (13475, '5.2.1.1.48', 'Amortizaciones Intangibles Vtas.', :institution , 356, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos por Amortizaciones Intangibles Vtas'),
        (13477, '5.2.1.1.50', 'Gastos por Deterioro Propiedades Planta y Equipos Vtas', :institution , 358, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos por Deterioro Propiedades, Plantas y Equipos Vtas'),
        (13478, '5.2.1.1.51', 'Gastos de Deterioro Inventario Vtas', :institution , 359, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos por Deterioro Inventario Vtas'),
        (13479, '5.2.1.1.52', 'Gastos por Deterioro Instrumentos Financieros Vtas', :institution , 360, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos por Deterioro Instrumentos Financieros Vtas'),
        (13432, '5.2.1.1.5', 'Aportes Patronales al IESS Vtas.', :institution , 313, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Aportes a la Seguridad Social Vtas'),
        (13497, '5.2.1.2.3', 'Gratificaciones Adm.', :institution , 378, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Sueldos, Salarios y demás Remuneraciones Adm.'),
        (13498, '5.2.1.2.4', 'Alimentación Adm.', :institution , 379, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Sueldos, Salarios y demás Remuneraciones Adm.'),
        (13499, '5.2.1.2.5', 'Aportes Patronales al IESS Adm.', :institution , 380, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Aportes a la Seguridad Social Adm.'),
        (13500, '5.2.1.2.6', 'Secap - Iece Adm.', :institution , 381, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Aportes a la Seguridad Social Adm.'),
        (13501, '5.2.1.2.7', 'Fondos de Reserva Adm.', :institution , 382, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Aportes a la Seguridad Social Adm.'),
        (13502, '5.2.1.2.8', 'Décimo Tercer Sueldo Adm.', :institution , 383, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Beneficios Sociales e Indemnizaciones Adm'),
        (13503, '5.2.1.2.9', 'Décimo Cuarto Sueldo Adm.', :institution , 384, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Beneficios Sociales e Indemnizaciones Adm'),
        (13480, '5.2.1.1.53', 'Gastos por Deterioro Intangibles Vtas.', :institution , 361, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos por Deterioro Intangibles Vtas'),
        (13482, '5.2.1.1.55', 'Gastos por Deterioro Otros Activos Vtas.', :institution , 363, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos por Deterioro Otros Activos Vtas'),
        (13483, '5.2.1.1.56', 'Gastos Anormales Mano de Obra Vtas.', :institution , 364, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Anormales Mano de Obra Vtas'),
        (13484, '5.2.1.1.57', 'Gastos Anormales Materiales Vtas.', :institution , 365, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Anormales Materiales Vtas'),
        (13485, '5.2.1.1.58', 'Gastos Anormales Costos de Produccion Vtas', :institution , 366, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Anormales Costos de Producción Vtas'),
        (13486, '5.2.1.1.59', 'Gastos por Restructuracion Vtas', :institution , 367, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos por Reestructuracion Vtas'),
        (13487, '5.2.1.1.60', 'Gastos por Valor Neto de realizacion de Inventarios Vtas', :institution , 368, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos por Valor Neto de realizacion Inventario Vtas'),
        (13488, '5.2.1.1.61', 'Asociaciones y Suscripciones Vtas.', :institution , 369, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Gastos Vtas'),
        (13489, '5.2.1.1.62', 'Cuotas y Afiliaciones Vtas.', :institution , 370, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Gastos Vtas'),
        (13490, '5.2.1.1.63', 'Gastos de Oficina Vtas', :institution , 371, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Gastos Vtas'),
        (13491, '5.2.1.1.64', 'Capacitación y Entrenamiento Vtas.', :institution , 372, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Gastos Vtas'),
        (13492, '5.2.1.1.65', 'Uniformes Vtas.', :institution , 373, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Gastos Vtas'),
        (13493, '5.2.1.1.66', 'Miscelaneos Vtas.', :institution , 374, 13427, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Gastos Vtas'),
        (13504, '5.2.1.2.10', 'Vacaciones Adm.', :institution , 385, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Beneficios Sociales e Indemnizaciones Adm'),
        (13505, '5.2.1.2.11', 'Desahucio Adm.', :institution , 386, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Beneficios Sociales e Indemnizaciones Adm'),
        (13506, '5.2.1.2.12', 'Gastos Planes de Beneficios a Empleados Adm.', :institution , 387, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Planes de Beneficios a Empleados Adm'),
        (13507, '5.2.1.2.13', 'Honorarios Profesionales Adm.', :institution , 388, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Honorarios, Comisiones y Dietas a PN Adm'),
        (13509, '5.2.1.2.15', 'Gastos Remuneraciones a otros trabajadores autónomos Adm.', :institution , 390, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Remuneraciones a otros trabajadores autónomos Adm'),
        (13510, '5.2.1.2.16', 'Gastos Honorarios a extranjeros por Servicios Ocasionales Adm.', :institution , 391, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Honorarios a extranjeros por Servicios Ocasionales Adm'),
        (13511, '5.2.1.2.17', 'Mantenimiento de Equipos Adm.', :institution , 392, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos de Mantenimiento, Repuestos y Reparaciones Adm'),
        (13512, '5.2.1.2.18', 'Reparaciones de Equipos Adm.', :institution , 393, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos de Mantenimiento, Repuestos y Reparaciones Adm'),
        (13513, '5.2.1.2.19', 'Arriendos Adm.', :institution , 394, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Arrendamiento Operativo Adm'),
        (13514, '5.2.1.2.20', 'Comisiones Adm.', :institution , 395, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Comisiones Adm'),
        (13515, '5.2.1.2.21', 'Publicidad y Promoción Adm.', :institution , 396, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Promoción y Publicidad Adm.'),
        (13516, '5.2.1.2.22', 'Publicaciones y Agencias Adm.', :institution , 397, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Promoción y Publicidad Adm.'),
        (13518, '5.2.1.2.24', 'Lubricantes Adm.', :institution , 399, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Combustibles Adm'),
        (13519, '5.2.1.2.25', 'Seguros Adm.', :institution , 400, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Seguros y Reaseguros Adm'),
        (13520, '5.2.1.2.26', 'Movilización y Transporte Adm.', :institution , 401, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Transporte Adm'),
        (13521, '5.2.1.2.27', 'Guías de Transportes Adm.', :institution , 402, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Transporte Adm'),
        (13522, '5.2.1.2.28', 'Fletes Adm.', :institution , 403, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Transporte Adm'),
        (13523, '5.2.1.2.29', 'Gastos de Gestión Adm.', :institution , 404, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Transporte Adm'),
        (13524, '5.2.1.2.30', 'Gastos de Viajes Adm.', :institution , 405, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Transporte Adm'),
        (13525, '5.2.1.2.31', 'Viajes al Exterior Adm.', :institution , 406, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Transporte Adm'),
        (13527, '5.2.1.2.33', 'Energía Eléctrica Adm.', :institution , 408, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Agua, Energia, Luz y Telecomunicaciones Adm'),
        (13528, '5.2.1.2.34', 'Teléfonos Convencionales Adm.', :institution , 409, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Agua, Energia, Luz y Telecomunicaciones Adm'),
        (13529, '5.2.1.2.35', 'Celulares Adm.', :institution , 410, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Agua, Energia, Luz y Telecomunicaciones Adm'),
        (13560, '5.2.1.2.66', 'Miscelaneos Adm.', :institution , 441, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Gastos Adm'),
        (13496, '5.2.1.2.2', 'Sobretiempos Adm.', :institution , 377, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Sueldos, Salarios y demás Remuneraciones Adm.'),
        (13568, '5.2.2.1', 'Otros Gastos', :institution , 449, 13567, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (13581, '5.2.3.1', 'Gastos de Operaciones Descontinuadas', :institution , 462, 13580, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos de Operaciones Descontinuadas'),
        (13562, '5.2.1.3.1', 'Intereses', :institution , 443, 13561, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Intereses'),
        (13563, '5.2.1.3.2', 'Comisiones', :institution , 444, 13561, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Comisiones'),
        (13564, '5.2.1.3.3', 'Gastos de Financiamiento de Activos', :institution , 445, 13561, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos de Financiamiento de Activos'),
        (13565, '5.2.1.3.4', 'Gastos Diferencia en Cambio', :institution , 446, 13561, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Diferencia en Cambio'),
        (13566, '5.2.1.3.5', 'Otros Gastos Financieros', :institution , 447, 13561, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Gastos Financieros'),
        (13570, '5.2.2.1.2', 'Intereses Tributarios', :institution , 451, 13568, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Gastos No Deducibles'),
        (13571, '5.2.2.1.3', 'Multas Tributarias', :institution , 452, 13568, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Gastos No Deducibles'),
        (13572, '5.2.2.1.4', 'Multas Superintendencia de Compañías', :institution , 453, 13568, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Gastos No Deducibles'),
        (13573, '5.2.2.1.5', 'Faltantes de Inventario', :institution , 454, 13568, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Gastos No Deducibles'),
        (13574, '5.2.2.1.6', 'Faltantes de Caja', :institution , 455, 13568, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Gastos No Deducibles'),
        (13575, '5.2.2.1.7', 'Comprobantes de Ventas que no cumplen requisitos legales', :institution , 456, 13568, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Gastos No Deducibles'),
        (13576, '5.2.2.1.8', 'Gastos de Viajes', :institution , 457, 13568, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Gastos No Deducibles'),
        (13577, '5.2.2.1.9', 'Gastos de Gestión', :institution , 458, 13568, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Gastos No Deducibles'),
        (13530, '5.2.1.2.36', 'Internet Adm.', :institution , 411, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Agua, Energia, Luz y Telecomunicaciones Adm'),
        (13531, '5.2.1.2.37', 'Agua Adm.', :institution , 412, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Agua, Energia, Luz y Telecomunicaciones Adm'),
        (13532, '5.2.1.2.38', 'Televisión Pagada Adm.', :institution , 413, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Agua, Energia, Luz y Telecomunicaciones Adm'),
        (13533, '5.2.1.2.39', 'Gastos Notariales Adm.', :institution , 414, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Notarios y Registradores de la Propiedad Adm'),
        (13534, '5.2.1.2.40', 'Gastos de Registro Mercantil Adm.', :institution , 415, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Notarios y Registradores de la Propiedad Adm'),
        (13535, '5.2.1.2.41', 'Impuesto a los Consumos Especiales Adm.', :institution , 416, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Impuestos, Contribuciones y Otros Adm'),
        (13536, '5.2.1.2.42', 'Impuesto a los Consumos Adm.', :institution , 417, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Impuestos, Contribuciones y Otros Adm'),
        (13537, '5.2.1.2.43', 'Tasas y Contribuciones Adm.', :institution , 418, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Impuestos, Contribuciones y Otros Adm'),
        (13539, '5.2.1.2.45', 'IVA Gasto Adm.', :institution , 420, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Impuestos, Contribuciones y Otros Adm'),
        (13540, '5.2.1.2.46', 'Depreciaciones Propiedades Planta y Equipos Adm.', :institution , 421, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Impuestos, Contribuciones y Otros Adm'),
        (13541, '5.2.1.2.47', 'Depreciaciones de Inversiones Adm.', :institution , 422, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos por Depreciación Propiedades de Inversión Adm'),
        (13542, '5.2.1.2.48', 'Amortizaciones Intangibles Adm.', :institution , 423, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos por Amortizaciones Intangibles Adm'),
        (13543, '5.2.1.2.49', 'Amortizaciones Otros Activos Adm.', :institution , 424, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos por Amortizaciones Otros Activos Adm'),
        (13544, '5.2.1.2.50', 'Gastos por Deterioro Propiedades Planta y Equipos Adm.', :institution , 425, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos por Deterioro Propiedades, Plantas y Equipos Adm'),
        (13545, '5.2.1.2.51', 'Gastos de Deterioro Inventario Adm.', :institution , 426, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos por Deterioro Inventario Adm'),
        (13546, '5.2.1.2.52', 'Gastos por Deterioro Instrumentos Financieros Adm.', :institution , 427, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos por Deterioro Instrumentos Financieros Adm'),
        (13548, '5.2.1.2.54', 'Gastos por Deterioro Cuentas por Cobrar Adm.', :institution , 429, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos por Deterioro Cuentas por Cobrar Adm'),
        (13549, '5.2.1.2.55', 'Gastos por Deterioro Otros Activos Adm.', :institution , 430, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos por Deterioro Otros Activos Adm'),
        (13550, '5.2.1.2.56', 'Gastos Anormales Mano de Obra Adm.', :institution , 431, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Anormales Mano de Obra Adm'),
        (13551, '5.2.1.2.57', 'Gastos Anormales Materiales Adm.', :institution , 432, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Anormales Materiales Adm'),
        (13552, '5.2.1.2.58', 'Gastos Anormales Costos de Producción Adm.', :institution , 433, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos Anormales Costos de Producción Adm'),
        (13553, '5.2.1.2.59', 'Gastos por Restructuracion Adm.', :institution , 434, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Gastos por Reestructuracion Adm'),
        (13555, '5.2.1.2.61', 'Asociaciones y Suscripciones Adm.', :institution , 436, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Gastos Adm'),
        (13556, '5.2.1.2.62', 'Cuotas y Afiliaciones Adm.', :institution , 437, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Gastos Adm'),
        (13557, '5.2.1.2.63', 'Gastos de Oficina Adm.', :institution , 438, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Gastos Adm'),
        (13558, '5.2.1.2.64', 'Capacitación y Entrenamiento Adm.', :institution , 439, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Gastos Adm'),
        (13559, '5.2.1.2.65', 'Uniformes Adm.', :institution , 440, 13494, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Gastos Adm'),
        (13578, '5.2.2.1.10', 'Retenciones Asumidas', :institution , 459, 13568, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Gastos No Deducibles'),
        (13579, '5.2.2.1.11', 'Gastos por Cancelación de Propinas', :institution , 460, 13568, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'Otros Gastos No Deducibles'),
        (13561, '5.2.1.3', 'Gastos Financieros', :institution , 442, 13426, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, ''),
        (9, '1.1.1.1.2', 'CAJA GUAYAQUIL', :institution , null, 13123, true, '2022-01-28 18:13:45', '2022-01-28 18:13:45', null, null),
        (14057, '2.3.1', 'chi chenor', :institution , 471, 14056, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'pasivos'),
        (14056, '2.3', 'pasivo que pasof', :institution , 470, 13228, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'pasivos'),
        (14053, '4.5', 'ingresos tecnoglobal', :institution , 467, 13362, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'ingresos'),
        (14058, '4.6', 'mis ingresos', :institution , 471, 13362, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'ingresos'),
        (14051, '1.4.1', 'act tec 2 hijo', :institution , 465, 14048, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'activos'),
        (14059, '4.6.1', 'mis ingresos hijitos', :institution , 472, 14058, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'ingresos'),
        (14052, '1.4.1.1', 'act tec 3 hijo', :institution , 466, 14051, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'activos'),
        (14055, '2.1.1.1', 'pasivos mios', :institution , 469, 13230, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'pasivos'),
        (14054, '1.1.1.1.1', 'caja mia', :institution , 468, 13123, true, '2021-08-11 10:36:00', '2021-08-11 10:36:00', null, 'caja'),
        (13120, '1', 'Activos', :institution , 1, 2, false, '2022-01-03 22:48:31', '2022-01-03 22:48:31', null, null),
        (10, '1.1.1.3.5', 'Banco de ambato', :institution , null, 13125, true, '2022-01-13 10:44:49', '2022-01-13 10:44:49', null, null),
        (12, '1.1.1.3.7', 'Banco 123', :institution , null, 13125, true, '2022-01-15 19:17:16', '2022-01-15 19:17:16', null, null),
        (14, '1.1.1.1.3', 'Caja cuenca', :institution , null, 13123, true, '2022-02-01 22:53:37', '2022-02-01 22:53:37', null, null),
        (16, '1.1.3.3.1', 'Inventario Scooter', :institution , null, 13145, true, '2022-02-02 15:54:10', '2022-02-02 15:54:10', null, null),
        (17, '1.1.1.3.8', 'banco america', :institution , null, 13125, true, '2022-02-02 19:58:12', '2022-02-02 19:58:12', null, null),
        (18, '1.1.1.3.9', 'Banco del Pichincha cc', :institution , null, 13125, true, '2022-02-03 14:15:20', '2022-02-03 14:15:20', null, null)";
                Yii::$app->db->createCommand($query)->bindValue(':institution',$model->id)->execute();

                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Crear nueva Empresa",
                    'content'=>'<span class="text-success">Create Institution success</span>',
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Crear Otra',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];         
            }else{           
                return [
                    'title'=> "Crear nueva Empresa",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    /**
     * Updates an existing Institution model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Actualizar Empresa:".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Empresa: ".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Actualizar Empresa #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];        
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Institution model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }


    }

     /**
     * Delete multiple existing Institution model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkdelete()
    {        
        $request = Yii::$app->request;
        $pks = explode(',', $request->post( 'pks' )); // Array or selected records primary keys
        foreach ( $pks as $pk ) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
       
    }

    /**
     * Finds the Institution model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Institution the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Institution::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
