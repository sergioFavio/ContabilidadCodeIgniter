<style type="text/css" >
.modal-static { 
    position: fixed;
    top: 50% !important; 
    left: 50% !important; 
    margin-top: -100px;  
    margin-left: -100px; 
    overflow: visible !important;
}
.modal-static,
.modal-static .modal-dialog,
.modal-static .modal-content {
    width: 200px; 
    height: 200px; 
}
.modal-static .modal-dialog,
.modal-static .modal-content {
    padding: 0 !important; 
    margin: 0 !important;
}
.modal-static .modal-content .icon {
}
</style>
<script>

$(document).ready(function(){
	
	$('#processing-modal').modal('show');  //... carga directo el modal al llamar al script sin tener que invocar un button o hipervínculo

}); // fin document.ready 	

</script>

<?php

//$salida = shell_exec('c:\xampp\mysql\bin\mysqldump -u root irbadb>d:\respaldoBD\irbaDB.sql');
//echo "<pre>$salida</pre>";	

?>

<div class="container">
	<div class="row">
	<h3><center><small>Crea un respaldo de la base de datos en la partición C:\respaldoBD</small></center></h3>
	<?php
		$directorio='C:\respaldoBD';
		if (!file_exists($directorio)) {
			echo"<p align='center'><code>NOTA: El directorio $directorio NO existe, se tiene que crear el directorio $directorio</code></p><br />";
	?>
		<p align='center'><button type='button' id='btnSalir' class='btn btn-primary btn-sm' onClick="window.location.href='<?=base_url();?>menuController/index'"><span class='glyphicon glyphicon-eject'></span> Salir</button></p>
		<br />
	<?php
		}else{ 
	?>
			<br /><br /><br /><br /><br /><br />
	</div>
</div>

<br /><br /><br /><br /><br /><br /><br /><br />

<!-- Static Modal -->
<div class="modal modal-static fade" id="processing-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body" >
            	<?php
            		shell_exec('c:\xampp\mysql\bin\mysqldump -u root contabilidad>c:\respaldoBD\contabilidadDB.sql');
                    echo img('/assets/img/loading.gif');
                ?>    
                <h4>Procesando... <button type="button" class="close" style="float:none;" data-dismiss="modal" aria-hidden="true"  onClick="window.location.href='<?=base_url();?>menuController/index'" >×</button></h4>
            </div>
        </div>
    </div>
</div>

 <br /><br /> 
<p align='center'><code>NOTA: Presione el botón 'X' en la ventana modal para terminar el proceso de respaldo de la base de datos.</code></p><br />
           
<br /><br /><br />
	<?php		
		}			