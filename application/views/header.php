<!DOCTYPE HTML>
<html>
    <head>
        <link rel="stylesheet" href="<?= base_url("css/bootstrap.css")?>">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script type="text/javascript" src="<?= base_url("js/jquery.js")?>"></script>
        <script type="text/javascript" src="<?= base_url("js/bootstrap.js")?>"></script>
        <style>
        	.cabecera{font-size:11px;text-align:center; }
        </style>
    </head>
    
    <body background="<?= base_url("img/body-bg.jpg")?>">
   		<div style='height:5px;'></div> 
        <div class="container">  
                <div class="span12">
                    <div class="well well-sm" style="font-size:12px;">
    	
                    	<table width="99%" class="table-condensed">
                		<thead>
                    		<tr class="cabecera">
                    			<td>
                    				<center>
                    				<?php
										$usuarioConectado=$this->session->userdata('usuarioLogueado');
										$usuarioNombre=$this->session->userdata('userName');
										echo img('/assets/img/logo.jpg');
									?>
									</center>
								</td>
							
								<td>
		        					<div style="width: 80px;"></div>
								</td>			
								
								<td>
									<center>
										Sistema de Informaci&oacute;n EXPRINTUR Ltda.
									</center>
								</td>
								
								<td>
									<center>
									<?php										
									    $fecha = date("d-m-Y");
										echo 'Fecha: '.$fecha;
									?>
									</center>
								</td>
								
								<td>
									<center>
										<strong style="color: #FF0000;">
										<?php
										if(isset($usuarioConectado)){
										  ?>
											<?= 'usuario: '.$usuarioNombre;  ?> </strong> &nbsp;|&nbsp; <?= anchor("login/logout", "CERRAR SESION"); ?>
										  <?php	
										}
										?>
										</center>
								</td>	
                    		</tr>
                 		</thead>
                    	</table>
                    	
                    	
                    	
<!--div style="height:0px;"></div-->
      	
<!--div class="row-fluid"> <!-- segunda fila de la cabecera -->
	
    <!--div class="col-md-1">
		<div class="input-group input-group-sm">
	    	<span class="input-group-addon" id="letraCabecera" ><?= img('/assets/img/logo.jpg');  ?></span>
		</div>
	</div><!-- /.col-md-2 -->
	
	<!--div class="col-md-1">
	 	<span></span>
	</div>
	
	<div class="col-md-2">
		<div class="input-group input-group-sm">
	    	<span class="label label-success" id="letraCabecera" >Sistema de Informaci&oacute;n IRBA Ltda.</span>
	 	</div>
	</div><!-- /.col-md-2 -->
	
	<!--div class="col-md-1">
	 	<span></span>
	</div>
	
    <div class="col-md-2">
		<div class="input-group input-group-sm">
	    	<span class="input-group-addon" id="letraCabecera" ><span class="glyphicon glyphicon-calendar"></span> </span>
	 		<input type="text"  class="form-control input-sm" id="fechaSistema" name="fechaSistema" <?= $fecha=date("d-m-Y"); ?> value="<?= $fecha; ?>" readonly='readonly' style="width: 100px;font-size:11px;text-align:center;" >
	 		
		</div>
	</div><!-- /.col-md-2 -->
	
	<!--div class="col-md-1">
	 	<span></span>
	</div>
	
    <div class="col-md-1">
		<div class="input-group input-group-sm">
	    	<span class="input-group-addon" id="letraCabecera" ><span class="glyphicon glyphicon-user"></span> </span>
	 		<input type="text"  class="form-control input-sm" id="inputUsuario" name="inputUsuario" value="<?= $usuarioNombre; ?>" readonly="readonly" style="width: 80px;font-size:11px;text-align:center;color: #FF0000;" >
	    </div>
	</div><!-- /.col-md-1 -->
	
	<!--div class="col-md-1">
	 	<span></span>
	</div>
	
    <div class="col-md-1">
		<div class="input-group input-group-sm">
	    	<button type="button"  class="btn btn-primary btn-sm" onClick="window.location.href='<?=base_url();?>login/logout'"><span class="glyphicon glyphicon-log-out"></span> Cerrar  Sesi&oacute;n</button>
	    </div>
	</div><!-- /.col-md-2 -->
	
<!--/div-->  <!-- fin segunda fila de la cabecera -->
		
		
		
                    	
                    </div>
                </div>
            <div>
  

