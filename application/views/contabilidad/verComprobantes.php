
<link rel="stylesheet" href="<?= base_url("css/bootstrap-theme.min.css")?>">

<style type="text/css" >

	/*  inicio de light box  */
	.modal-dialog {width:520px;}
	.modalEditar-dialog {width:843px;}
	.thumbnail {margin-bottom:6px;}
	/*  fin de light box  */

	/*  inicio de scrollbar  */
	thead { display:block;  margin:0px; cell-spacing:0px; left:0px; }  
	tbody { display:block; overflow:auto; height:357px; }               
	th { height:10px;  width:850px;}                                    
	td { height:10px;  width:850px; margin:0px; cell-spacing:0px;}
	/*  fin de scrollbar  */
	
	#pdfModal{padding-top:60px;padding-left:261px;}  /* ... baja la ventana modal más al centro vertical ... */
	
	.cuerpoDetalle, {margin: 2px;} 
    
	/* Fix alignment issue of label on extra small devices in Bootstrap 3.2 */
    .form-horizontal .control-label{padding-top: 7px;}
    
    .tituloReporte{font-size:9px; margin-top:10px; }
    
	#cuerpoCabecera{margin:0 auto; padding:0; width:820px; height:50px;}
	#cuerpoDetalle{margin:0 auto; padding:0; width:820px; height:430px;}
	#cuerpoPaginacion{margin:0 auto;padding:0; width:820px; height:60px;}
	
	#inputBuscarPatron, #letraCabecera{font-size:11px;text-align:center; }
	
	.letraDetalle, .letraTipoMaterial{font-size:11px;text-align:center; }
	
	.letraNumero{font-size:11px;text-align:right; }
	
	#titulo{font-size:14px;margin-top:1px;  text-align:right; font-weight:bold} 
	
</style>

<script>

$(document).ready(function() {
			 	  
}); // fin document.ready 
	
</script>

<div class="jumbotron" id="cuerpoCabecera" >	<!--cuerpoCrudMaterial-->
		
	    <form class="form-horizontal" method="post" action="<?=base_url()?>contabilidad/ubicarComprobante" id="formBuscarRegistro_" name="formBuscarRegistro_" >
	    	<div style="height:10px;"></div>
			
		   <div class="row">
			   	<div class="col-xs-1"> 
					<span></span>
			   	</div>
			   	     
				<div class="col-xs-4">    
			    	<div class="input-group input-group-sm">
			    		<input type="text" class="form-control input-sm" id="inputBuscarPatron" name="inputBuscarPatron" value='<?= $consultaComprobante ?>' placeholder="buscar No. comprobante&hellip;">
						
						<div class="input-group-btn">
                        	<button type="submit" class="btn btn-info btn-sm"><span class="glyphicon glyphicon-search"></span></button>
                    	</div>
			    	</div>
			    </div><!-- /.col-xs-3 -->	
			    
			    <div class="col-xs-1"> 
					<span></span>
			   	</div>
			   	
			   	<div class="col-xs-3"> 
					<span  id="titulo" class="label label-success"> V e r  &nbsp;&nbsp;&nbsp;&nbsp; C o m p r o b a n t  e</span>
			   	</div>
			    
			    <div class="col-xs-1"> 
					<span></span>
			   	</div>
			   			     	
			    <div class="col-xs-2"> 
			    	<button type="button" id="btnSalir" class="btn btn-primary btn-sm" onClick="window.location.href='<?=base_url();?>menuController/index'"><span class="glyphicon glyphicon-eject"></span> Salir</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</div>
			    
		</div>  <!-- /.row -->
			
	   </form>  <!--/div-->
	
</div> <!-- fin ... cuerpoCabecera -->



<div style="height:7px;"></div>

<div class="jumbotron" id="cuerpoDetalle" >
	<div style="height:5px;"></div>
			
    <?php foreach($listaComprobante as $compbte):?>
		<embed src="<?= base_url('pdfsArchivos/contabilidad/comprobantes/cpbte'.$compbte->numComprobante.'.pdf') ?>" width="820" height="455" > <!-- documento embebido PDF -->
    <?php endforeach ?>
        		
</div> <!-- fin ... cuerpoDetalle -->

<div style="height:5px;"></div>

<div class="jumbotron" id="cuerpoPaginacion" style='font-size:11px;text-align:center;'>
	<ul class="pagination">
    <?php
      /* Se imprimen los números de página */           
      echo $this->pagination->create_links();
    ?>
    </ul>
</div>


