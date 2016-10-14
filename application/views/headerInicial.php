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
            <!--div class="row"-->
                <div class="span12">
                    <div class="well well-sm" style="font-size:12px;">
                    	<table width="99%" class="table-condensed">
                    		
                		<thead>
                    		<tr class="cabecera">
                    			<td>
                    				<center>
                    				<?php
										echo img('assets/img/logo.jpg');
									?>
									</center>
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
                    		</tr>
                 		</thead>
                    	</table>
                    </div>
                <div>
            <!--/div-->
            

