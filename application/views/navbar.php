<style type="text/css" >
	.marginBottom-0 {margin-bottom:0;}

	.dropdown-submenu{position:relative;}
	.dropdown-submenu>.dropdown-menu{top:0;left:100%;margin-top:-6px;margin-left:-1px;-webkit-border-radius:0 6px 6px 6px;-moz-border-radius:0 6px 6px 6px;border-radius:0 6px 6px 6px;}
	.dropdown-submenu>a:after{display:block;content:" ";float:right;width:0;height:0;border-color:transparent;border-style:solid;border-width:5px 0 5px 5px;border-left-color:#cccccc;margin-top:5px;margin-right:-10px;}
	.dropdown-submenu:hover>a:after{border-left-color:#555;}
	.dropdown-submenu.pull-left{float:none;}.dropdown-submenu.pull-left>.dropdown-menu{left:-100%;margin-left:10px;-webkit-border-radius:6px 0 6px 6px;-moz-border-radius:6px 0 6px 6px;border-radius:6px 0 6px 6px;}  
</style>

<script type="text/javascript">
(function($){
	$(document).ready(function(){
		$('ul.dropdown-menu [data-toggle=dropdown]').on('click', function(event) {
			event.preventDefault(); 
			event.stopPropagation(); 
			$(this).parent().siblings().removeClass('open');
			$(this).parent().toggleClass('open');
		});
	});
})(jQuery);

</script>

<div  class="row">
    <div class="span12">
        <ul class="nav nav-tabs">	
 			<li >
 				<a href="<?=base_url();?>menuController/index" ><span class="glyphicon glyphicon-home"></span> Inicio </b></a>
 			</li>              

            <li class="dropdown" >
                <a href="<?=base_url();?>" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-list-alt"></span> Inventarios <b class="caret"></b></a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                    <li class="divider"></li>
                    <li><a href="<?=base_url();?>materiales/ingresoMaterial?nombreDeposito=almacen"><span class="glyphicon glyphicon-log-in"></span> Ingreso a Almacén</a></li>
                    <li><a href="<?=base_url();?>materiales/ingresoMaterial?nombreDeposito=bodega"><span class="glyphicon glyphicon-log-in"></span> Ingreso a Bodega</a></li>
                    <li class="divider"></li>
                    <li><a href="<?=base_url();?>materiales/salidaMaterial?nombreDeposito=almacen"><span class="glyphicon glyphicon-log-out"></span> Salida de Almacén</a></li>
                    <li><a href="<?=base_url();?>materiales/salidaMaterial?nombreDeposito=bodega"><span class="glyphicon glyphicon-log-out"></span> Salida de Bodega</a></li> 		
                    <li class="divider"></li>
                    <li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-list-alt"></span> Reportes </b></a>
						<ul class="dropdown-menu">
							<li><a href="<?=base_url();?>materiales/fechasReporteIngresoSalida?nombreDeposito=almacen&tipoTransaccion=ingreso"><span class="glyphicon glyphicon-list-alt"></span> Ingreso Almacén</a></li>
							<li><a href="<?=base_url();?>materiales/fechasReporteIngresoSalida?nombreDeposito=bodega&tipoTransaccion=ingreso"><span class="glyphicon glyphicon-list-alt"></span> Ingreso Bodega</a></li>						
							<li><a href="<?=base_url();?>materiales/fechasReporteIngresoSalida?nombreDeposito=almacen&tipoTransaccion=salida"><span class="glyphicon glyphicon-list-alt"></span> Salida Almacén</a></li>
							<li><a href="<?=base_url();?>materiales/fechasReporteIngresoSalida?nombreDeposito=bodega&tipoTransaccion=salida"><span class="glyphicon glyphicon-list-alt"></span> Salida Bodega</a></li>
							<li><a href="<?=base_url();?>materiales/fechaReporteMensualSalida?nombreDeposito=almacen&tipoTransaccion=salida"><span class="glyphicon glyphicon-list-alt"></span> Mensual Salida Materiales</a></li>
							<li><a href="<?=base_url();?>materiales/fechasReporteMaterialUsadoResponsable?nombreDeposito=almacen&tipoTransaccion=salida"><span class="glyphicon glyphicon-list-alt"></span> Material Usado por Responsable</a></li>
							<li><a href="<?=base_url();?>materiales/materialPorNumeroOrden?nombreDeposito=almacen&tipoTransaccion=salida"><span class="glyphicon glyphicon-list-alt"></span> Material por N&uacute;mero de Orden</a></li>
							<li><a href="<?=base_url();?>materiales/datosFisicoValorado"><span class="glyphicon glyphicon-list-alt"></span> Inventario Físico Valorado</a></li>
						</ul>
					</li>
                    
                    <li><a href="<?=base_url();?>materiales/datosKardex"><span class="glyphicon glyphicon-list-alt"></span> Kardex de Materiales</a></li>
                    <li><a href="<?=base_url();?>materiales/reponerMateriales"><span class="glyphicon glyphicon-transfer"></span> Reponer Materiales</a></li>
                    
                </ul>
            </li>

            <li class="dropdown" >
                <a href="<?=base_url();?>" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> Proveedores <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="#">Crear Proveedor</a></li>
                    <li><a href="#">Editar Proveedor</a></li>
                    <li><a href="#">Eliminar Proveedor</a></li>
                    <li><a href="#">Listar Proveedores </a></li>
                    <li><a href="#">Hacer Pedido</a></li>
                    <li><a href="#">otros ...</a></li>
                </ul>
            </li>

            <li class="dropdown" >
                <a href="<?=base_url();?>" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-signal"></span> Producci&oacute;n <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-pencil"></span> Pedidos</a>
						<ul class="dropdown-menu">
							<li><a href="<?=base_url();?>tienda/realizarPedido?local=F"><span class="glyphicon glyphicon-pencil"></span> Hacer Pedido </a></li>
							<li><a href="<?=base_url();?>produccion/crudVerPedidos"><span class="glyphicon glyphicon-eye-open"></span> Ver Pedidos</a></li>
						</ul>
					</li>
                    <li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-usd"></span> Cotización</a>
						<ul class="dropdown-menu">
							<li><a href="<?=base_url();?>produccion/cotizar"><span class="glyphicon glyphicon-usd"></span> Armar Cotizaci&oacute;n</a></li>
							<li><a href="<?=base_url();?>produccion/crudVerCotizaciones"><span class="glyphicon glyphicon-eye-open"></span> Ver Cotizaciones</a></li>
						</ul>
					</li>
					
					<li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-signal"></span> Producción</a>
						<ul class="dropdown-menu">
							<li><a href="<?=base_url();?>produccion/crearPlantillaProducto?nombreDeposito=acabado"><span class="glyphicon glyphicon-list-alt"></span> Crear Plantilla Producto Acabado</a></li>
							<li><a href="<?=base_url();?>produccion/crearPlantillaProducto?nombreDeposito=blanco"><span class="glyphicon glyphicon-list-alt"></span> Crear Plantilla Producto Blanco</a></li>
							<li><a href="<?=base_url();?>produccion/consultarProduccionProducto?nombreDeposito=acabado"><span class="glyphicon glyphicon-question-sign"></span> Consultar Producción Producto Acabado</a></li>
							<li><a href="<?=base_url();?>produccion/consultarProduccionProducto?nombreDeposito=blanco"><span class="glyphicon glyphicon-question-sign"></span> Consultar Producción Producto Blanco</a></li>
							<li><a href="#">Otros</a></li>
						</ul>
					</li>
                                       
                    <li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-tag"></span> Orden de Trabajo</a>
						<ul class="dropdown-menu">
							<li><a href="<?=base_url();?>produccion/datosOrdenTrabajo"><span class="glyphicon glyphicon-tag"></span> Asignar Orden de Trabajo</a></li>
							<li><a href="<?=base_url();?>produccion/verOrdenesTrabajo"><span class="glyphicon glyphicon-eye-open"></span> Ver Órdenes de Trabajo</a></li>
						</ul>
					</li>
					
					<li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-tags"></span> Ordenes de Stock</a>
						<ul class="dropdown-menu">
							<li><a href="<?=base_url();?>produccion/datosOrdenStock"><span class="glyphicon glyphicon-tag"></span> Asignar Orden de Stock</a></li>
							<li><a href="<?=base_url();?>produccion/verOrdenesStock"><span class="glyphicon glyphicon-eye-open"></span> Ver Órdenes de Stock</a></li>
						</ul>
					</li>
                                        
                    <li><a href="#"><span class="glyphicon glyphicon-list-alt"></span> Reportes</a></li>  
                    <li><a href="#">otros ...</a></li>
                </ul>
            </li>

            <li class="dropdown" >
                <a href="<?=base_url();?>" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-usd"></span> Ventas <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-pencil"></span> Pedidos</a>
						<ul class="dropdown-menu">
							<li><a href="<?=base_url();?>tienda/realizarPedido?local=T"><span class="glyphicon glyphicon-pencil"></span> Hacer Pedido </a></li>
							<li><a href="<?=base_url();?>tienda/verPedidos"><span class="glyphicon glyphicon-eye-open"></span> Ver Pedidos</a></li>
						</ul>
					</li>
					
					<li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-tags"></span> Notas de Entrega PENDIENTE</a>
						<ul class="dropdown-menu">
							<li><a href="<?=base_url();?>tienda/notaEntrega?local=T"><span class="glyphicon glyphicon-pencil"></span> Hacer Nota de Entrega </a></li>
							<li><a href="<?=base_url();?>tienda/..."><span class="glyphicon glyphicon-eye-open"></span> Ver Notas de Entregas</a></li>
						</ul>
					</li>
					
					<li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-usd"></span> Depósitos PENDIENTE</a>
						<ul class="dropdown-menu">
							<li><a href="<?=base_url();?>tienda.../realizarPedido?local=T"><span class="glyphicon glyphicon-pencil"></span> Registrar Depósito </a></li>
							<li><a href="<?=base_url();?>tienda/..."><span class="glyphicon glyphicon-eye-open"></span> Ver Depósitos</a></li>
						</ul>
					</li>
					
					<li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-list-alt"></span> Proforma PENDIENTE</a>
						<ul class="dropdown-menu">
							<li><a href="<?=base_url();?>tienda/proforma?local=T"><span class="glyphicon glyphicon-pencil"></span> Hacer Proforma </a></li>
							<li><a href="<?=base_url();?>tienda/..."><span class="glyphicon glyphicon-eye-open"></span> Ver Proformas</a></li>
						</ul>
					</li>
					
                    <li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-usd"></span> Cotización</a>
						<ul class="dropdown-menu">
							<li><a href="#"><span class="glyphicon glyphicon-usd"></span> Cotizar</a></li>
							<li><a href="#"><span class="glyphicon glyphicon-eye-open"></span> Ver Cotizaciones</a></li>
							<li><a href="#">otros</a></li>
						</ul>
					</li>
					<li><a href="#"><span class="glyphicon glyphicon-info-sign"></span> Consultar Stock</a></li>
                    <li><a href="<?=base_url();?>tienda/venta"><span class="glyphicon glyphicon-shopping-cart"></span> Venta</a></li>
                    <li><a href="<?=base_url();?>tienda/listaPreciosProductos"><span class="glyphicon glyphicon-print"></span> Lista de Precios</a></li>
                    <li><a href="#"><span class="glyphicon glyphicon-list-alt"></span> Reportes</a></li>
                    <li><a href="#">Otros ...</a></li>
                </ul>
            </li>

            <li class="dropdown" >
                <a href="<?=base_url();?>" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-list"></span> Contabilidad <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-pencil"></span> Comprobantes</a>
                    	<ul class="dropdown-menu">
                    		<li><a href="<?=base_url();?>contabilidad/comprobante?tipoComprobante=ingreso"><span class="glyphicon glyphicon-log-in"></span> Ingreso</a></li>
							<li><a href="<?=base_url();?>contabilidad/comprobante?tipoComprobante=egreso"><span class="glyphicon glyphicon-log-out"></span> Egreso</a></li>
							<li><a href="<?=base_url();?>contabilidad/comprobante?tipoComprobante=diario"><span class="glyphicon glyphicon-new-window"></span> Diario</a></li>
							<li><a href="<?=base_url();?>contabilidad/buscarComprobante"><span class="glyphicon glyphicon-edit"></span> Modificar</a></li>
								
							<li><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-unchecked"></span> Ajuste ...PENDIENTE</a></li>
							
							<li><a href="<?=base_url();?>contabilidad/verComprobante"><span class="glyphicon glyphicon-search"></span> Buscar</a></li>
						</ul>
					</li>
                    <li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-list-alt"></span> Reportes</a>
                    	<ul class="dropdown-menu">
							<li><a href="<?=base_url();?>contabilidad/reporteContabilidad?reporte=DG"><span class="glyphicon glyphicon-book"></span> Libro Diario</a></li>
							<li><a href="<?=base_url();?>contabilidad/reporteContabilidad?reporte=MY"><span class="glyphicon glyphicon-book"></span> Libro Mayor</a></li>
							<li><a href="<?=base_url();?>contabilidad/reporteContabilidad?reporte=SS"><span class="glyphicon glyphicon-plus-sign"></span><span class="glyphicon glyphicon-minus-sign"></span> Balance Sumas y Saldos</a></li>
							<li><a href="<?=base_url();?>contabilidad/reporteContabilidad?reporte=BG"><span class="glyphicon glyphicon-book"></span><span class="glyphicon glyphicon-pencil"></span> Balance General</a></li>
							<li><a href="<?=base_url();?>contabilidad/reporteContabilidad?reporte=ER"><span class="glyphicon glyphicon-usd"></span><span class="glyphicon glyphicon-ok"></span> Estado de Resultados</a></li>
							
							<li><a href="#"><span class="glyphicon glyphicon-eye-open"></span> otros ....</a></li>
						</ul>
					</li>
					<li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-pencil"></span> Cuentas</a>
                    	<ul class="dropdown-menu">
							<li><a href="<?=base_url();?>contabilidad/crudCuenta"><span class="glyphicon glyphicon-file"></span> CRUD Cuenta</a></li>
							<li><a href="<?=base_url();?>contabilidad/generarReportePlanDeCuentas"><span class="glyphicon glyphicon-print"></span> Plan de Cuentas</a></li>
						</ul>
					</li>
                    <li><a href="#"><span class="glyphicon glyphicon-calendar"></span> Iniciar Per&iacute;odo de  Gesti&oacute;n</a></li>
                    <li><a href="#">otros ...</a></li>
                </ul>
            </li>

            <li class="dropdown" >
                <a href="<?=base_url();?>" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-tasks"></span> Administraci&oacute;n <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-list-alt"></span> Inventarios</a>
                    	<ul class="dropdown-menu">
                    		<li><a href="<?=base_url();?>materiales/crudMaterial"><span class="glyphicon glyphicon-file"></span> CRUD Almac&eacute;n</a></li>
                    		<li><a href="<?=base_url();?>materiales/buscarSalidaAlmacen"><span class="glyphicon glyphicon-pencil"></span> Modificar Salida de Almac&eacute;n</a></li>
							<li><a href="<?=base_url();?>materiales/fechasReporteIngresoSalida?nombreDeposito=almacen&tipoTransaccion=salidas_modificadas"><span class="glyphicon glyphicon-list-alt"></span> Reporte Salidas Modificadas Almac&eacute;n</a></li>
						</ul>
					</li>
                    <li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> Proveedores PENDIENTE</a>
                    	<ul class="dropdown-menu">
							<li><a href="#"><span class="glyphicon glyphicon-usd"></span> Libro Diario</a></li>
							<li><a href="#"><span class="glyphicon glyphicon-eye-open"></span> Libro Mayores</a></li>
							<li><a href="#"><span class="glyphicon glyphicon-eye-open"></span> Sumas y saldos</a></li>
							<li><a href="#"><span class="glyphicon glyphicon-eye-open"></span> otros ....</a></li>
						</ul>
					</li>
					<li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-signal"></span> Producci&oacute;n</a>
                    	<ul class="dropdown-menu">
							<li><a href="<?=base_url();?>produccion/crudMaterialArea"><span class="glyphicon glyphicon-file"></span> CRUD material X m2</a></li>
							<li><a href="<?=base_url();?>contabilidad/generarReportePlanDeCuentas"><span class="glyphicon glyphicon-print"></span> Listado material X m2</a></li>
							<li><a href="<?=base_url();?>produccion/crudManoObra"><span class="glyphicon glyphicon-file"></span> CRUD mano de obra</a></li>
							<li><a href="<?=base_url();?>contabilidad/generarReportePlanDeCuentas"><span class="glyphicon glyphicon-print"></span> Listado mano de obra</a></li>
							
							<!--li><a href="<?=base_url();?>produccion/dataTable"><span class="glyphicon glyphicon-file"></span> data Table</a></li-->
							
						</ul>
					</li>
					
					<li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-tags"></span> Productos</a>
                    	<ul class="dropdown-menu">
							<li><a href="<?=base_url();?>tienda/crudProducto"><span class="glyphicon glyphicon-file"></span> CRUD productosfabrica</a></li>
							<li><a href="<?=base_url();?>tienda/listaPreciosProductos"><span class="glyphicon glyphicon-print"></span> Lista de Precios</a></li>
						</ul>
					</li>
					
                    <li><a href="#"><span class="glyphicon glyphicon-time"></span> Iniciar Gesti&oacute;n PENDIENTE</a></li>
                    <li><a href="<?=base_url();?>menuController/respaldoBaseDatos"><span class="glyphicon glyphicon-floppy-save"></span> Respaldo Base  Datos</a></li>
                    <li><a href="#">otros ...</a></li>
                </ul>
            </li>
            
            <li >
 				<a href="<?=base_url();?>login/salir"  ><span class="glyphicon glyphicon-eject"></span> Salir </b></a>
 			</li> 

            </ul>
    </div>
</div>