<style type="text/css" >
	/*  inicio de light box  */
	.modal-dialog {width:360px;}
	.thumbnail {margin-bottom:6px;}
	/*  fin de light box  */
	
	#cuerpo{margin:0 auto;  padding:0; width:405px; height:210px; background:#f4f4f4;}
    
    #loginModal{padding-top:90px;}  /* ... baja la ventana modal más al centro vertical ... */
</style>

<script>
$(document).ready(function(){

	$('#loginModal').modal({show:true,backdrop: true,backdrop: 'static'}); //... backdrop... hace que solo se tenga que hacer dentro del modal...
	
	$("#btnLogin").click(function(){
    	validarUsuario();
	});
               
}); // fin document.ready ...


function validarUsuario(){
	var registrosValidos= true;	  // ... bandera para grabar o no grabar registros ...
	
	if($("#inputUsuario").val()=="" ){
			alert("¡¡¡ E R R O R !!! ... El contenido de USUARIO está vacío");
			var registrosValidos= false;	
	}
	
	if($("#inputPassword").val()=="" ){
			alert("¡¡¡ E R R O R !!! ... El contenido de PASSWORD está vacío");
			var registrosValidos= false;	
	}
	
	if(registrosValidos){
		$('#loginModal').modal('hide'); // cierra el lightBox
		$("#form_").submit(); // ...  validarUsuario ...
	}
	
}

</script>

<div class="jumbotron" id="cuerpo" style="background-color:lightgray;">	
	<?php
		echo "<br><br>";
		echo img('assets/img/exprinturCuerpo.jpg');
		echo "<br><br><br><br>";
	?>
</div>


<!-- ... inicio  lightbox login... -->

<div class="modal fade" id="loginModal" >
  <div class="modal-dialog">
    <div class="modal-content" >
      <div class="modal-header">
        <!--button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button-->
        <h4 class="modal-title">¡¡¡ Bienvenido&hellip;regístrese por favor&hellip;  !!!</h4>
      </div>
      <div class="modal-body" >
      	  
      	 <form class="form-horizontal" method="post" action="<?=base_url()?>login/validarUsuario" id="form_" name="form_">
        	<div class="input-group input-group-sm">
			  <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
			  	<input type='text' class='form-control input-sm' id='inputUsuario' name='inputUsuario' placeholder='usuario &hellip;' > 
			</div>
        
        	<div style="height:15px;"></div>
        	
        	<div class="input-group input-group-sm">
			  <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
			  <input type="password" class="form-control input-sm" id="inputPassword" name="inputPassword" placeholder='password &hellip;'>
			</div>
        	
      	</form>
      	        
      </div>
      <div class="modal-footer">
      	<button type="button" class="btn btn-inverse btn-sm" id="btnSalir"  onClick="window.location.href='<?=base_url();?>login/salir'"><span class="glyphicon glyphicon-eject"></span> Salir</button>&nbsp;
        <button type="button" class="btn btn-primary btn-sm" id='btnLogin'><span class="glyphicon glyphicon-log-in"></span> Login</button>
      </div>
      
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- ... fin  lightbox login ... -->