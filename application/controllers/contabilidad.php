<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contabilidad extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	
	
	/////////////////////////////////////////////
	//... funciones del CRUD contaplandectas ...//
	/////////////////////////////////////////////
	
	public function crudCuenta()
	{
		//... control de permisos de acceso ....
		$permisoUserName=$this->session->userdata('userName');
		$permisoMenu=$this->session->userdata('usuarioMenu');
		if($permisoUserName!='superuser' && $permisoUserName!='developer' && $permisoMenu!='contabilidad'){  //... valida permiso de userName y de menu...
			$datos['mensaje']='Usuario NO autorizado para operar Sistema de Contabilidad';
			$this->load->view('header');
			$this->load->view('mensaje',$datos );
			$this->load->view('footer');

		}	//... fin control de permisos de acceso ....
		else {	//... usuario validado ...
			$this->load->model("tablaGenerica_model");
			
			/* URL a la que se desea agregar la paginación*/
	    	$config['base_url'] = base_url().'contabilidad/crudCuenta';
			
			/*Obtiene el total de registros a paginar */
	    	$config['total_rows'] = $this->tablaGenerica_model->get_total_registros('contaplandectas');
			
			$contador= $config['total_rows'];
			
			if($contador==0){  //...cuando NO hay registros ...
				$datos['mensaje']='No hay registros grabados en la tabla del PLAN DE CUENTAS';
				$this->load->view('header');
				$this->load->view('mensaje',$datos );
				$this->load->view('footer');
			}
			else{      //... cuando hay registros ...
			
				/*Obtiene el numero de registros a mostrar por pagina */
	//	    	$config['per_page'] = '13';
				$config['per_page'] = $contador;
				
				/*Indica que segmento de la URL tiene la paginación, por default es 3*/
	//	    	$config['uri_segment'] = '3';
				$desde = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		  
				/*Se personaliza la paginación para que se adapte a bootstrap*/
	/*		    $config['cur_tag_open'] = '<li class="active"><a href="#">';
			    $config['cur_tag_close'] = '</a></li>';
			    $config['num_tag_open'] = '<li>';
			    $config['num_tag_close'] = '</li>';
			    $config['last_link'] = FALSE;
			    $config['first_link'] = FALSE;
			    $config['next_link'] = '&raquo;';
			    $config['next_tag_open'] = '<li>';
			    $config['next_tag_close'] = '</li>';
			    $config['prev_link'] = '&laquo;';
			    $config['prev_tag_open'] = '<li>';
			    $config['prev_tag_close'] = '</li>';
	*/			
				/* Se inicializa la paginacion*/
	//	    	$this->pagination->initialize($config);
		
				/* Se obtienen los registros a mostrar*/ 
		   		$datos['listaMaterial'] = $this->tablaGenerica_model->get_registros('contaplandectas',$config['per_page'], $desde); 
		
				$datos['consultaMaterial'] ='';
				$datos['titulo'] ='Contabilidad';
				
		 		/*Se llama a la vista para mostrar la información*/
				$this->load->view('header');
				$this->load->view('contabilidad/mostrarCuentasCrud', $datos);
				$this->load->view('footer');
				
			}  // fin else cuando hay registros 
		}	//... fin IF validar usuario ...
	} //... fin crudCuenta
	
	
	
	public function grabarNuevaCuentaCrud(){	
		//... graba una nuevaCuenta en tabla plandectas ...
		$cuenta = array(
       		"cuenta"=>$_POST['inputCodigo'],
        	"descripcion"=>$_POST['inputDescripcion'],
        	"nivel"=>$_POST['nivelCuenta']
       	);

		$this-> load -> model("tablaGenerica_model");
   		$this-> tablaGenerica_model -> grabar('contaplandectas',$cuenta);
	  //$datos['titulo'] ='Contabilidad';
   		redirect('contabilidad/crudCuenta');  //...vuelve a la vista: mostrarCuentasCrud ...
	}
	
	
	public function actualizarCuentaCrud(){
		//... edita registro de las tablas [almacen/bodega] ...
		$registro=array(
       		"cuenta"=>$this->input->post("inputCodigoC"),
        	"descripcion"=>$this->input->post("inputDescripcionC"),
        	"nivel"=>$this->input->post("inputNivelC")
       	);
				
		$this-> load -> model("tablaGenerica_model");
   		$this-> tablaGenerica_model -> editarRegistro('contaplandectas','cuenta',$registro['cuenta'],$registro);
  
		$data=base_url("contabilidad/crudCuenta");
		echo $data;
	}
	
	
	public function eliminarCuentaCrud(){
		//... elimina registro de las tablas [almacen/bodega] ...
		$cuentaContabilidad=$_POST['codigo'];
		$this-> load -> model("tablaGenerica_model");
		$this-> tablaGenerica_model -> eliminar('contaplandectas','cuenta',$cuentaContabilidad);
		$data=base_url("contabilidad/crudCuenta");
		echo $data;
	}
	
	
	public function generarReportePlanDeCuentas(){
		//... genera reporte PlanDeCuentas en PDF
		
		//... control de permisos de acceso ....
		$permisoUserName=$this->session->userdata('userName');
		$permisoMenu=$this->session->userdata('usuarioMenu');
		$permisoProceso3=$this->session->userdata('usuarioProceso3');
		if($permisoUserName!='superuser' && $permisoUserName!='developer' && $permisoMenu!='contabilidad'){  //... valida permiso de userName y de menu...
			$datos['mensaje']='Usuario NO autorizado para operar Sistema de Contabilidad';
			$this->load->view('header');
			$this->load->view('mensaje',$datos );
			$this->load->view('footer');
		}	//... fin control de permisos de acceso ....
		else {		//... usuario validado ...
			// Se carga la libreria fpdf
			$this->load->library('contabilidad/PlanDeCuentasPdf');
			
			// Se obtienen los registros de la base de datos
			$sql="SELECT cuenta,descripcion,nivel FROM contaplandectas";
			
			$registros = $this->db->query($sql);
			 
			$contador= $registros->num_rows; //...contador de registros que satisfacen la consulta ..
			
			if($contador==0){
				$datos['mensaje']='No hay registros en la tabla del PLAN DE CUENTAS.';
				$this->load->view('header');
				$this->load->view('mensaje',$datos );
				$this->load->view('footer');
			}else{
				// Creacion del PDF
			    /*
			    * Se crea un objeto de la clase SalAlmacenPdf, recordar que la clase Pdf
			    * heredó todos las variables y métodos de fpdf
			    */
			     
			    ob_clean(); // cierra si es se abrio el envio de pdf...
			    $this->pdf = new PlanDeCuentasPdf();
				
			    // Agregamos una página
			    $this->pdf->AddPage();
			    // Define el alias para el número de página que se imprimirá en el pie
			    $this->pdf->AliasNbPages();
			 
			    /* Se define el titulo, márgenes izquierdo, derecho y
			    * el color de relleno predeterminado
			    */
			         
			    // Se define el formato de fuente: Arial, negritas, tamaño 9
			    //$this->pdf->SetFont('Arial', 'B', 9);
			    $this->pdf->SetFont('Arial', '', 9);
			    $espacio=3; 			//... epacio variable para imprimir ...
			    foreach ($registros->result() as $registro) {
			        // Se imprimen los datos de cada registro
			       	
		        	//$this->pdf->Cell(12,5,$registro->cuenta,'',0,'L',0);
					$this->pdf->Cell($espacio*($registro->nivel),5,'','',0,'L',0);
					$this->pdf->Cell(2,5,$registro->cuenta,'',0,'L',0);
					$this->pdf->Cell(30,5,'','',0,'L',0);
					
					
		            //$this->pdf->Cell(80,5,utf8_decode($registro->descripcion),'',0,'L',0);
					$this->pdf->Cell(120,5,utf8_decode($registro->descripcion),'',0,'L',0);
					$this->pdf->Cell(20-($espacio*($registro->nivel)),5,'','',0,'L',0);
		       		$this->pdf->Cell(20,5,utf8_decode($registro->nivel),'',0,'L',0);
		          
					//Se agrega un salto de linea
		        	$this->pdf->Ln(5);	
			    }
					
				     /* PDF Output() settings
				     * Se manda el pdf al navegador
				     *
				     * $this->pdf->Output(nombredelarchivo, destino);
				     *
				     * I = Muestra el pdf en el navegador
				     * D = Envia el pdf para descarga
					 * F: save to a local file
					 * S: return the document as a string. name is ignored.
					 * $pdf->Output(); //default output to browser
					 * $pdf->Output('D:/example2.pdf','F');
					 * $pdf->Output("example2.pdf", 'D');
					 * $pdf->Output('', 'S'); //... Returning the PDF file content as a string:
				     */
				  
				  	$this->pdf->Output('pdfsArchivos/contabilidad/plandectas.pdf', 'F');
					
					$datos['documento']="pdfsArchivos/contabilidad/plandectas.pdf";	
					$datos['titulo']=' Plan de Cuentas';	// ... titulo ...
					
					$this->load->view('header');
					$this->load->view('reportePdfSinFechas',$datos );
					$this->load->view('footer');	
				}
			}	//.. fin IF validar usuario ...
	    
	} //... fin funcion: generarReportePlanDeCuentas ...
	
	
	
	public function habilitarCuentasMovimiento()
	{
		$sql ="DELETE FROM contaplana";				//... borra los registros anteriores de contaplana antes de cargar los de la nueva consulta... 
 		$result = $this->db->query($sql);
		
		$sql ="SELECT cuenta,descripcion,nivel FROM contaplandectas WHERE nivel>='4' ";
 		$result = $this->db->query($sql);
		
		$this->load->model("tablaGenerica_model");	//...carga el modelo tabla para cargar planCtas que solo se pueden registrar [contaplana]
		
		foreach ($result->result() as $registro) {                 //... carga registros a la tabla contaplana ...
			$registro=array(
	       		"cuenta"=>$registro->cuenta,
	        	"descripcion"=>$registro->descripcion,
	        	"nivel"=>$registro->nivel
       		);
                        
            $this-> tablaGenerica_model -> grabar('contaplana',$registro);	        
        };
			
		$sql ="SELECT cuenta FROM contaplandectas WHERE nivel='5' ";
 		$result = $this->db->query($sql);
		
		$codAnterior='';										//... var. aux.para codigo cuenta ...
		foreach ($result->result() as $registro) {                 //... carga registros a la tabla contaplanb ...
			$registroAux=array(
	       		"cuenta"=>substr($registro->cuenta,0,6).'00'	//... actualiza codigo al de la subcta ...
	   		);
	               
			if( $codAnterior!='' && $codAnterior!= substr($registro->cuenta,0,6).'00'){ 

				$registro=array(
		       		"cuenta"=>$codAnterior	//... actualiza codigo al de la subcta ...
	       		);
				
				$this-> tablaGenerica_model -> eliminar('contaplana','cuenta',$codAnterior);	//... elimina registro en tabla contaplana ...
			}		//... fin if ...
			
			$codAnterior= $registroAux['cuenta'] ;
 
        };	//... fin foreach ...
	
		//... graba ultimo registro después del ciclo del foreach ...
		$registro=array(
       		"cuenta"=>$codAnterior	//... actualiza codigo al de la subcta ...
   		);
      
	    $this-> tablaGenerica_model -> eliminar('contaplana','cuenta',$codAnterior);	//... elimina registro en tabla contaplana ...
		// ... fin grabacion del ultimo registro ...
	
		redirect("menuController/index");	//... vuelve menu principal ...
	}
	
	public function buscarComprobante()
	{		
		//... control de permisos de acceso ....
		$permisoUserName=$this->session->userdata('userName');
		$permisoMenu=$this->session->userdata('usuarioMenu');
		$permisoMenu=$this->session->userdata('usuarioMenu');
	
		if($permisoUserName!='superuser' && $permisoUserName!='developer' && $permisoMenu!='contabilidad'){  //... valida permiso de userName ...
			$datos['mensaje']='Usuario NO autorizado para operar Sistema de Contabilidad';
			$this->load->view('header');
			$this->load->view('mensaje',$datos );
			$this->load->view('footer');
			//... fin control de permisos de acceso ....	
		}else{
			$sql ="SELECT * FROM contagestion ORDER BY gestion DESC LIMIT 1";			//... recupera el ultimo registro insertado de una tabla... 
			
			$consulta = $this->db->query($sql);
			if ($consulta->num_rows() > 0){
			   $row = $consulta->row_array(); 
			   $gestion= $row['gestion'];			//..asign ultimo registro tabla contagestion ...
			}
			
			///////////////////////////////////////
			///...INICIO genera nuevo numero de comprobante ...
			//////////////////////////////////////
			$anhoGestion = substr($gestion, 0, 4);	//... anho del periodo ...
			$mesGestion = substr($gestion, 4, 2);	//... mes del periodo ...
	
			$sql ="SELECT * FROM comprobantecabecera WHERE year(fecha)='$anhoGestion' AND month(fecha)='$mesGestion' ";	
			$cabeceraComprobante = $this->db->query($sql)->result_array();
			 	
			$datos['titulo']='Modificar COMPROBANTE';
			$datos['gestion']=$gestion;
			$datos['cabeceraComprobante']=$cabeceraComprobante;	
	
			$this->load->view('header');
			$this->load->view('contabilidad/buscarComprobante',$datos);
			$this->load->view('footer');
		}		//... fin IF validar usuario ...
		
	}		//... fin buscarComprobante ....
	
	
	public function comprobante()
	{
		$tipoComprobante= $_GET['tipoComprobante']; //... lee tipoComprobante que viene del menu principal(ingreso/egreso/traspaso ) ...		
		
		//... control de permisos de acceso ....
		
		$permisoUserName=$this->session->userdata('userName');
		$permisoMenu=$this->session->userdata('usuarioMenu');
		$permisoProceso1=$this->session->userdata('usuarioProceso1');
		if($permisoUserName!='superuser' && $permisoUserName!='developer' && $permisoMenu!='contabilidad'){  //... valida permiso de userName y de menu...
			$datos['mensaje']='Usuario NO autorizado para operar Sistema de Contabilidad';
			$this->load->view('header');
			$this->load->view('mensaje',$datos );
			$this->load->view('footer');
		}	//... fin control de permisos de acceso ....
		else {				//... usuario validado ...
			$this->load->model("numeroDocumento_model");
			$nombreTabla='contanocomprobante'; // ... prefijoTabla
	    	$pedido = $this->numeroDocumento_model->getNumero($nombreTabla);
			$sql ="SELECT * FROM contagestion ORDER BY gestion DESC LIMIT 1";			//... recupera el ultimo registro insertado de una tabla... 
		
			$consulta = $this->db->query($sql);
			if ($consulta->num_rows() > 0){
			   $row = $consulta->row_array(); 
			   $gestion= $row['gestion'];			//..asign ultimo registro tabla contagestion ...
			}
			
			///////////////////////////////////////
			///...INICIO genera nuevo numero de comprobante ...
			//////////////////////////////////////
			$anhoSistema = substr($gestion, 0, 4);	//... anho del periodo ...
			$mesSistema = substr($gestion, 4, 2);	//... mes del periodo ...
			
			$anhoPedido= substr($pedido, 0, 4);  // toma los primeros 4 caracteres ... anho.
			$mesPedido= substr($pedido, 4, 2);  // toma los  caracteres ... mes.
			$secuenciaPedido= substr($pedido, 6, 3);  // toma los caracteres ... secuencia.
			if($anhoPedido==$anhoSistema){
				if($mesPedido==$mesSistema){
			        $secuenciaPedido=$secuenciaPedido +1;
					if(strlen($secuenciaPedido)==1){
						 $secuenciaPedido="00". $secuenciaPedido;
					}
					if(strlen($secuenciaPedido)==2){
						 $secuenciaPedido="0". $secuenciaPedido;
					}
			     	$numComprobante=$anhoSistema.$mesSistema.$secuenciaPedido;
				}
			    else{
					$numComprobante=$anhoSistema.$mesSistema."001";
				}
			}
			else{
				$numComprobante=$anhoSistema.$mesSistema."001";
			}
			
			$this->load->model("tablaGenerica_model");	//...carga el modelo tabla para cargar planCtas que solo se pueden registrar [contaplana]			
			$cuentas= $this->tablaGenerica_model->getTodos('contaplana'); //..una vez cargado el modelo de la tabla llama contaplana..
			
			$datos['gestion']=$gestion;			
			$datos['titulo']='Comprobante de '.$tipoComprobante;
			$datos['numComprobante']=$numComprobante;
			$datos['cuentas']=$cuentas;		
			$datos['tipoComprobante']=$tipoComprobante;	// ... ingreso/egreso/traspaso ...
	
			$this->load->view('header');
			$this->load->view('contabilidad/comprobante',$datos);
			$this->load->view('footer');
		}	//... fin IF validar usuario ...
	}	//.. fin funcion comprobante ...


	public function grabarComprobante()
	{
		$tipoComprobante=$_POST['tipoComprobante']; //... formulario tipoComprobante [ingreso/egreso/traspaso] ...
		
		if($tipoComprobante!='egreso'){
			$numeroCheque='';
		}else{
			$numeroCheque=$_POST['inputCheque'];
		}
				
		$numeroFilasValidas=$_POST['numeroFilas']; 	//... formulario salida[ numeroFilas] ...
		$numComprobante=$_POST['numComprobante'];
		$valorLiteral=$_POST['inputLiteral'];		//... valor literal total del comprobante ...
		
		// ... actualizar numero de documento de comprobante ingreso/egreso/traspaso ...
		$this-> load -> model("numeroDocumento_model");	//... modelo numeroDocumento_model ... 
		$nombreTabla='contanocomprobante'; // ... prefijoTabla
		
		$this-> numeroDocumento_model -> actualizar($numComprobante,$nombreTabla);
		// fin actualizar numero de documento de comprobante ingreso/egreso/traspaso ...
		
		
		// ... inserta registro en tabla comprobantecabecera ...	
		$fecha=$_POST['inputFecha'];
		
		$cabecera = array(
	    	"numComprobante"=>$numComprobante,
	    	"fecha"=>$_POST['inputFecha'],
	    	"tipoComprobante"=>strtoupper($tipoComprobante),  
	    	"clienteBanco"=>$_POST['inputCliente'],
	    	"numeroCheque"=>$numeroCheque,
	    	"concepto"=>$_POST['inputConcepto']
	    	
		);
		
	    $this-> load -> model("tablaGenerica_model");	//... carga modelo tablaGenerica
	    $this-> tablaGenerica_model -> grabar('comprobantecabecera',$cabecera);
		// ...fin de insertar registro en tabla comprobantecabecera ...	
		
 	    $debeHaber=''; 		//... D:debe  H:haber ...
        for($i=0; $i<$numeroFilasValidas; $i++){
       
        	if( $_POST['cantDebe_'.$i] != "0" || $_POST['cantDebe_'.$i] != "0.00" || $_POST['cantHaber_'.$i] != "0" || $_POST['cantHaber_'.$i] != "0.00"  ){
          	    //... si cantidad mayor que cero  graba registro ... 
          	    //... agrega registro tabla librodiario ...  
          	    
          	    if($_POST['cantDebe_'.$i]>0 ){
          	    	$debeHaber='D';
					$monto= $_POST['cantDebe_'.$i];
          	    } else{
          	    	$debeHaber='H';
					$monto=$_POST['cantHaber_'.$i];
          	    }
	
				$monto=str_replace(",","",$monto); //...quita comas de separacion ..
          	    
          	    $codigoSinEspacio=str_replace(" ","",$_POST['idCta_'.$i]); //...quita espacio en blanco ..
			
	            $detalle = array(
	            	"idComprobante"=>$numComprobante,
	            	"tComprobante"=>strtoupper($tipoComprobante),
	            	"fechaComprobante"=>$_POST['inputFecha'],
	            	"cuentaComprobante"=>$codigoSinEspacio,
				    "debeHaber"=>$debeHaber,
				    "monto"=>$monto,
				    "glosa"=>$_POST['glosa_'.$i]
				);
				
				$this-> load -> model("tablaGenerica_model");	//... carga modelo tablaGenerica
	    		$this-> tablaGenerica_model -> grabar('comprobantedetalle',$detalle);
				// ...fin de insertar registro en tabla comprobantedetalle ...	
							
				$clave=$codigoSinEspacio;
				$debeMonto=$_POST['cantDebe_'.$i];				
				$haberMonto=$_POST['cantHaber_'.$i];
				
				if( $debeMonto== ""){
					$debeMonto=0.00;
					$haberMonto=str_replace(",","",$haberMonto); //...quita comas de separacion ..
				}else{
					$haberMonto=0.00;
					$debeMonto=str_replace(",","",$debeMonto); //...quita comas de separacion ..
				}
									
				if(substr($clave,6,2)=='00'){
					$nivelCuenta=4;
				}else{
					$nivelCuenta=5;
				}
								
				$k=0; 			//... cantidad de digitos a tomar de $clave ...
				
				for($j=1;$j<=$nivelCuenta; $j++){
					$k=$j;
					if($j==3){
						$k=4;
					}
					
					if($j==4){
						$k=6;
					}
					
					if($j==5){
						$k=8;
					}
					
					$cuentaAux=substr($clave,0,$k);	//... variable aux para generar cuentas de niveles anteriores a 4 y 5.
				
					for($l=1; $l<=8-$k; $l++){
						$cuentaAux=$cuentaAux.'0';		//... genera cuenta los niveles anteriores ..1,2,3..4
					}	// ... fin FOR l
					
					// ... actualiza registro tabla maestra[almacen/bodega]	
					$this-> load -> model("tablaGenerica_model");
		    		$this-> tablaGenerica_model -> aumentarSaldosContables('contaplandectas',$cuentaAux,$debeMonto,$haberMonto);  
									
					// ... fin de inserción  registro tabla transacciones y actualizacion tabla maestra...
						
				}	//... fin FOR j	
				
				
			}	// ... fin IF
			
		}  // ... fin  FOR  i
		
		// ... actualizar numero de comprobante ...	
		$this-> load -> model("numeroDocumento_model");	//... modelo numeroDocumento_model ... cotizacion
		$nombreTabla='contanocomprobante'; // ... prefijoTabla
		$this-> numeroDocumento_model -> actualizar($numComprobante,$nombreTabla);
		// fin actualizar numero de comprobante ...
		
		$datos['numeroComprobante']=$numComprobante;
		$datos['valorLiteral']=$valorLiteral;		
		
		redirect("contabilidad/generarComprobantePDF?numeroComprobante=$numComprobante&valorLiteral=$valorLiteral");	

	}	//... fin grabarComprobante
	
	
	public function modificarComprobante()
	{
		$numComprobante= str_replace(" ","",$_POST['inputNumero']); //... lee tipoComprobante y quita espacio en blanco ..
		$fecha= $_POST['inputFecha']; 				//... lee fecha ...	
		$concepto= $_POST['inputConcepto']; 		//... lee concepto ...
		$gestion= $_POST['gestion']; 				//... lee gestion ...
		$clienteBanco= $_POST['inputCliente']; 		//... lee clienteBanco ...
		$numeroCheque= $_POST['inputCheque']; 		//... lee numeroCheque ...
		
  		$tComprobante=str_replace(" ","",$_POST['inputTipo']); //... lee tipoComprobante y quita espacio en blanco ..
  
		$tipoComprobante='vacio';
		if($tComprobante=="I"){
			$tipoComprobante='ingreso';
		} else if($tComprobante=='E'){
				$tipoComprobante='egreso';
			}else{
				$tipoComprobante='diario';
		}
				
		$sql="SELECT cuentaComprobante,descripcion,debeHaber,monto,glosa FROM comprobantedetalle,contaplandectas WHERE idComprobante='$numComprobante' AND cuentaComprobante=cuenta";
		$regComprobante=mysql_query($sql);
		$nRegistrosComprobante= mysql_num_rows($regComprobante); 	//... numero registros salida que satisfacen la consulta ...
			
		$this->load->model("tablaGenerica_model");	//...carga el modelo tabla para cargar planCtas que solo se pueden registrar [contaplana]			
		$cuentas= $this->tablaGenerica_model->getTodos('contaplana'); //..una vez cargado el modelo de la tabla llama contaplana..
						
		$datos['titulo']='Modificar Comprobante '.$tipoComprobante;

		$datos['numComprobante']=$numComprobante; 		//... dato cabecera comprobante ..
		$datos['fecha']=$fecha;							//... dato cabecera comprobante ..
		$datos['concepto']=$concepto;					//... dato cabecera comprobante ..
		$datos['tipoComprobante']=$tipoComprobante;		//... dato cabecera comprobante ..
		$datos['gestion']=$gestion;						//... dato cabecera comprobante ..
		$datos['clienteBanco']=$clienteBanco;			//... dato cabecera comprobante ..
		$datos['numeroCheque']=$numeroCheque;			//... dato cabecera comprobante ..
		$datos['nRegistrosComprobante']=$nRegistrosComprobante;	
		$datos['regComprobante']=$regComprobante;	
		$datos['cuentas']=$cuentas;	

		$this->load->view('header');
		$this->load->view('contabilidad/modificarComprobante',$datos);
		$this->load->view('footer');
	}		//... fin funcion: modificarComprobante ...
	
	
	public function grabarComprobanteModificado(){
		
		$tipoComprobante=$_POST['tipoComprobante']; //... formulario tipoComprobante [ingreso/egreso/traspaso] ...
		
		if($tipoComprobante!='egreso'){
			$numeroCheque='';
		}else{
			$numeroCheque=$_POST['inputCheque'];
		}
				
		$numeroFilasValidas=$_POST['numeroFilas']; 	//... formulario salida[ numeroFilas] ...
		$numComprobante=$_POST['numComprobante'];
		$valorLiteral=$_POST['inputLiteral'];		//... valor literal total del comprobante ...
				
		$sql="SELECT cuentaComprobante,debeHaber,monto,fechaComprobante FROM comprobantedetalle WHERE idComprobante='$numComprobante' ";
		$regComprobante=$this->db->query($sql);
		
		// ... borra registros en la tabla: comprobantedetalle ...	
		$this-> load -> model("tablaGenerica_model");	//... modelo tablaGenerica_model
		$this-> tablaGenerica_model -> eliminar('comprobantedetalle','idComprobante',$numComprobante);	
		// fin borrar registros de comprobantedetalle ...
		
		//... decrementar montos de cuentas en tabla: contaplandectas ...
		foreach ($regComprobante->result() as $regCompbte) {
			$clave=$regCompbte->cuentaComprobante;
			$fechaComprobante=$regCompbte->fechaComprobante;
			
			if($regCompbte->debeHaber=='D'){
				$debeMonto=$regCompbte->monto;				
				$haberMonto=0.00;
			}else{
				$debeMonto=0.00;				
				$haberMonto=$regCompbte->monto;
			}
						
			if(substr($clave,6,2)=='00'){
				$nivelCuenta=4;
			}else{
				$nivelCuenta=5;
			}
							
			$k=0; 			//... cantidad de digitos a tomar de $clave ...
			
			for($j=1;$j<=$nivelCuenta; $j++){
				$k=$j;
				if($j==3){
					$k=4;
				}
				
				if($j==4){
					$k=6;
				}
				
				if($j==5){
					$k=8;
				}
				
				$cuentaAux=substr($clave,0,$k);	//... variable aux para generar cuentas de niveles anteriores a 4 y 5.
			
				for($l=1; $l<=8-$k; $l++){
					$cuentaAux=$cuentaAux.'0';		//... genera cuenta los niveles anteriores ..1,2,3..4
				}	// ... fin FOR l
				
				// ... actualiza registro tabla maestra[almacen/bodega]	
				$this-> load -> model("tablaGenerica_model");
	    		$this-> tablaGenerica_model -> disminuirSaldosContables('contaplandectas',$cuentaAux,$debeMonto,$haberMonto);  
								
				// ... fin de inserción  registro tabla transacciones y actualizacion tabla maestra...
					
			}	//... fin FOR j	
			
		}	 //... fin foreach ...
		
		//... fin de deshacer datos de linea de detalle del comprobante original ...		
		
		//... inicio de insercion y actualizacion de registros en tablas: comprobantedetalle y contaplandectas ...	
 	    $debeHaber=''; 		//... D:debe  H:haber ...
        for($i=0; $i<$numeroFilasValidas; $i++){
       
        	if( $_POST['cantDebe_'.$i] != "0" || $_POST['cantDebe_'.$i] != "0.00" || $_POST['cantHaber_'.$i] != "0" || $_POST['cantHaber_'.$i] != "0.00"  ){
          	    //... si cantidad mayor que cero  graba registro ... 
          	    //... agrega registro tabla librodiario ...  
          	    
          	    if($_POST['cantDebe_'.$i]>0 ){
          	    	$debeHaber='D';
					$monto= $_POST['cantDebe_'.$i];
          	    } else{
          	    	$debeHaber='H';
					$monto=$_POST['cantHaber_'.$i];
          	    }
	
				$monto=str_replace(",","",$monto); //...quita comas de separacion ..
          	    
          	    $codigoSinEspacio=str_replace(" ","",$_POST['idCta_'.$i]); //...quita espacio en blanco ..
			
	            $detalle = array(
	            	"idComprobante"=>$numComprobante,
	            	"tComprobante"=>strtoupper($tipoComprobante),
	            	"fechaComprobante"=>$fechaComprobante,
	            	"cuentaComprobante"=>$codigoSinEspacio,
				    "debeHaber"=>$debeHaber,
				    "monto"=>$monto,
				    "glosa"=>$_POST['glosa_'.$i]
				);
				
				$this-> load -> model("tablaGenerica_model");	//... carga modelo tablaGenerica
	    		$this-> tablaGenerica_model -> grabar('comprobantedetalle',$detalle);
				// ...fin de insertar registro en tabla comprobantedetalle ...	
							
				$clave=$codigoSinEspacio;
				$debeMonto=$_POST['cantDebe_'.$i];				
				$haberMonto=$_POST['cantHaber_'.$i];
				
				if( $debeMonto== ""){
					$debeMonto=0.00;
					$haberMonto=str_replace(",","",$haberMonto); //...quita comas de separacion ..
				}else{
					$haberMonto=0.00;
					$debeMonto=str_replace(",","",$debeMonto); //...quita comas de separacion ..
				}
									
				if(substr($clave,6,2)=='00'){
					$nivelCuenta=4;
				}else{
					$nivelCuenta=5;
				}
								
				$k=0; 			//... cantidad de digitos a tomar de $clave ...
				
				for($j=1;$j<=$nivelCuenta; $j++){
					$k=$j;
					if($j==3){
						$k=4;
					}
					
					if($j==4){
						$k=6;
					}
					
					if($j==5){
						$k=8;
					}
					
					$cuentaAux=substr($clave,0,$k);	//... variable aux para generar cuentas de niveles anteriores a 4 y 5.
				
					for($l=1; $l<=8-$k; $l++){
						$cuentaAux=$cuentaAux.'0';		//... genera cuenta los niveles anteriores ..1,2,3..4
					}	// ... fin FOR l
					
					// ... actualiza registro tabla maestra[almacen/bodega]	
					$this-> load -> model("tablaGenerica_model");
		    		$this-> tablaGenerica_model -> aumentarSaldosContables('contaplandectas',$cuentaAux,$debeMonto,$haberMonto);  
									
					// ... fin de inserción  registro tabla transacciones y actualizacion tabla maestra...
						
				}	//... fin FOR j	
				
				
			}	// ... fin IF
			
		}  // ... fin  FOR  i
		
		$datos['numeroComprobante']=$numComprobante;
		$datos['valorLiteral']=$valorLiteral;		
		
		redirect("contabilidad/generarComprobantePDF?numeroComprobante=$numComprobante&valorLiteral=$valorLiteral");	

	}	//... fin grabarComprobanteModificado ...
	
	
	function convertirNumeroAliteral(){
		$object['literal']=convertirNumeroAliteral($this->input->post('cadena'));
   		echo json_encode($object);		
	}
	
	
	public function generarComprobantePDF(){
		//... genera reporte de salida en PDF
		$numeroComprobante= $_GET['numeroComprobante']; 	//... lee numeroComprobante que viene de grabarComprobante ...
		$valorLiteral= $_GET['valorLiteral']; 				//... valor literal total del comprobante ...
				
		$sql ="SELECT * FROM comprobantecabecera WHERE numComprobante='$numeroComprobante' ";
		$contador = $this->db->query($sql);	
 		$contador= $contador->num_rows; //...contador de registros que satisfacen la consulta ..

		if($contador==0){
			$datos['mensaje']='No hay registro grabado con el n&uacute;mero de  comprobante '.$numeroComprobante.' en la tabla COMPROBANTEcabecera.';
			$this->load->view('header');
			$this->load->view('mensaje',$datos );
			$this->load->view('footer');
		}else{
			// Se carga la libreria fpdf
			$this->load->library('contabilidad/ComprobantePdf');
			
			// Se obtienen los registros de la base de datos
			$sql ="SELECT idComprobante,cuentaComprobante,debeHaber,monto,glosa,descripcion FROM comprobantedetalle,contaplandectas WHERE idComprobante='$numeroComprobante' AND cuentaComprobante=cuenta";
			$cuentas = $this->db->query($sql);
							
			$this->load->model("tablaGenerica_model");	//...carga el modelo tabla generica ...
			$comprobanteCabecera= $this->tablaGenerica_model->buscar('comprobantecabecera','numComprobante',$numeroComprobante); //..una vez cargado el modelo de la tabla llama comprobantecabecera..
			
			$fechaComprobante= $comprobanteCabecera["fecha"];				// ... forma de asignar cuando se utliza funcion ...buscar ... de tablaGenerica_model ...
			$tipoComprobante= $comprobanteCabecera["tipoComprobante"];		// ... forma de asignar cuando se utliza funcion ...buscar ... de tablaGenerica_model ...
			if($tipoComprobante=="I"){
				$tComprobante="Ingreso";
			}else if($tipoComprobante=="E"){
				$tComprobante="Egreso";
				}else{
					$tComprobante="Diario";
			}
			
			$clienteBanco= $comprobanteCabecera["clienteBanco"];			// ... forma de asignar cuando se utliza funcion ...buscar ... de tablaGenerica_model ...
			$numeroCheque= $comprobanteCabecera["numeroCheque"];			// ... forma de asignar cuando se utliza funcion ...buscar ... de tablaGenerica_model ...
			$concepto= $comprobanteCabecera["concepto"];					// ... forma de asignar cuando se utliza funcion ...buscar ... de tablaGenerica_model ...
	//		$usuario= $comprobanteCabecera["usuario"];						// ... forma de asignar cuando se utliza funcion ...buscar ... de tablaGenerica_model ...
				
			// Creacion del PDF
		    /*
		    * Se crea un objeto de la clase ComprobantePdf, recordar que la clase Pdf
		    * heredó todos las variables y métodos de fpdf
		    */
		     
		    ob_clean(); // cierra si es se abrio el envio de pdf...
		    $this->pdf = new ComprobantePdf();
			
			$this->pdf->numeroComprobante=strtoupper($numeroComprobante);      		//...pasando variable para el header del PDF
			$this->pdf->fechaComprobante=fechaMysqlParaLatina($fechaComprobante); 	//...pasando variable para el header del PDF
			$this->pdf->clienteBanco=$clienteBanco; 								//...pasando variable para el header del PDF
			$this->pdf->tipoComprobante=$tComprobante; 							//...pasando variable para el header del PDF
			$this->pdf->numeroCheque=$numeroCheque; 								//...pasando variable para el header del PDF
			$this->pdf->concepto=$concepto; 										//...pasando variable para el header del PDF
//			$this->pdf->usuario=$usuario; 											//...pasando variable para el header del PDF
		    // Agregamos una página
		    $this->pdf->AddPage();
		    // Define el alias para el número de página que se imprimirá en el pie
		    $this->pdf->AliasNbPages();
		 
		    /* Se define el titulo, márgenes izquierdo, derecho y
		    * el color de relleno predeterminado
		    */
		         
	        $this->pdf->SetLeftMargin(10);
	        $this->pdf->SetRightMargin(10);
	        $this->pdf->SetFillColor(200,200,200);
	 
		    // Se define el formato de fuente: Arial, negritas, tamaño 9
		    //$this->pdf->SetFont('Arial', 'B', 9);
		    $this->pdf->SetFont('Arial', '', 9);
		    
			$totalDebe=0; 		//... acumula los importes del DEBE...
			$totalHaber=0; 		//... acumula los importes del HABER...
			$numeroLineas=0; 	//...numero de lineas de impresion ...
		    foreach ($cuentas->result() as $cuenta) {
		        // se imprime el numero actual y despues se incrementa el valor de $x en uno
		        // Se imprimen los datos de cada registro
				$numeroLineas = $numeroLineas +1;
				$this->pdf->Cell(15,5,$cuenta->cuentaComprobante,'',0,'L',0);
				$this->pdf->Cell(3,5,'','',0,'L',0);
				$this->pdf->Cell(94,5,utf8_decode($cuenta->descripcion),'',0,'L',0);
				if($cuenta->debeHaber=='D'){
					$this->pdf->Cell(15,5,'','',0,'L',0);
					$totalDebe=$totalDebe+$cuenta->monto;
				}else{
					$this->pdf->Cell(56,5,'','',0,'L',0);
					$totalHaber=$totalHaber+$cuenta->monto;
				}
				$this->pdf->Cell(20,5,number_format($cuenta->monto,2),'',0,'R',0);
				//Se agrega un salto de linea
				$this->pdf->Ln('5');
		    }

			$this->pdf->Ln('5');
			$this->pdf->Cell(86,5,'','',0,'L',0);
    		$this->pdf->Cell(20,5,'Totales ',0,0,'R');
			$this->pdf->Cell(10,5,'','',0,'L',0);
			$this->pdf->Cell(31,5,number_format($totalDebe,2),0,0,'R');
			$this->pdf->Cell(10,5,'','',0,'L',0);
			$this->pdf->Cell(31,5,number_format($totalHaber,2),0,0,'R');
			
			$this->pdf->Ln('5');
			$this->pdf->Ln('5');
			$this->pdf->Cell(5,5,$valorLiteral,'',0,'L',0);
			
			for($x=$numeroLineas; $x<42; $x++){
				$this->pdf->Ln('5');				//... imprime lineas en blanco ...
			}
			
			$this->pdf->Cell(20,5,'____________________','',0,'L',0);
			$this->pdf->Cell(30,5,'','',0,'L',0);
			$this->pdf->Cell(20,5,'____________________','',0,'L',0);
			$this->pdf->Cell(30,5,'','',0,'L',0);
			$this->pdf->Cell(20,5,'____________________','',0,'L',0);
			$this->pdf->Cell(30,5,'','',0,'L',0);
			$this->pdf->Cell(20,5,'____________________','',0,'L',0);
			
			$this->pdf->Ln('5');
			$this->pdf->Cell(35,5,'Elaborado','',0,'C',0);
			$this->pdf->Cell(25,5,'','',0,'L',0);
			$this->pdf->Cell(20,5,'Vo.Bo. Contador','',0,'C',0);
			$this->pdf->Cell(30,5,'','',0,'L',0);
			$this->pdf->Cell(20,5,'Aprobado','',0,'C',0);
			$this->pdf->Cell(30,5,'','',0,'L',0);
			$this->pdf->Cell(20,5,'Recibido','',0,'C',0);
			
			
		     /* PDF Output() settings
		     * Se manda el pdf al navegador
		     *
		     * $this->pdf->Output(nombredelarchivo, destino);
		     *
		     * I = Muestra el pdf en el navegador
		     * D = Envia el pdf para descarga
			 * F: save to a local file
			 * S: return the document as a string. name is ignored.
			 * $pdf->Output(); //default output to browser
			 * $pdf->Output('D:/example2.pdf','F');
			 * $pdf->Output("example2.pdf", 'D');
			 * $pdf->Output('', 'S'); //... Returning the PDF file content as a string:
		     */
			  
			$this->pdf->Output('pdfsArchivos/contabilidad/comprobantes/cpbte'.$numeroComprobante.'.pdf', 'F');
			 
			$datos['documento']="pdfsArchivos/contabilidad/comprobantes/cpbte".$numeroComprobante.".pdf";	
			$datos['titulo']=' Comprobante No. '.substr($numeroComprobante,0,6).'-'.substr($numeroComprobante,6,3);	// ... titulo ...
		
			$this->load->view('header');
			$this->load->view('reportePdfSinFechas',$datos );
			$this->load->view('footer');
	
//			 redirect("menuController/index");					
		}
	    
	} //... fin funcion: generarComprobantePDF ...
	
	public function reporteContabilidad(){
		$reporte= $_GET['reporte']; //... lee reporte[DG:diario general/ MY:mayores/ BG:balnce general/ ER:estado resultados] ...
		if($reporte=='DG'){
			$tituloReporte='Diario General';
			$reporte='DiarioGeneral';			//... variable guarda el reporte a generar ...
		}	
		
		if($reporte=='MY'){
			$tituloReporte='Mayor';
			$reporte='Mayor';					//... variable guarda el reporte a generar ...
		}	
		
		if($reporte=='SS'){
			$tituloReporte='Balance de Sumas y Saldos';
			$reporte='SumasYsaldos';					//... variable guarda el reporte a generar ...
		}
		
		if($reporte=='BG'){
			$tituloReporte='Balance General';
			$reporte='BalanceGeneral';					//... variable guarda el reporte a generar ...
		}
		
		if($reporte=='ER'){
			$tituloReporte='Estado de Resultados';
			$reporte='EstadoResultados';					//... variable guarda el reporte a generar ...
		}
		
		$this->load->model("tablaGenerica_model");	//...carga el modelo tablagenerica
		$fechasGestiones= $this->tablaGenerica_model->getTodos('contagestion'); //..una vez cargado el modelo de la tabla llama contagestion..
			
		$datos['fechasGestiones']=$fechasGestiones;

		$datos['tituloReporte']=$tituloReporte;
		$datos['reporte']=$reporte;
		$this->load->view('header');
		$this->load->view('contabilidad/reporteContabilidad',$datos );
		$this->load->view('footer');
	}		//... fin reporteContabilidad ...
	
		
	public function generarReporteDiarioGeneral(){
		//... genera reporte de diarioGeneral en PDF
		$fechaGestion= $_POST['fechaDeGestion']; 		//... lee fechaGestion ...
		$anhoGestion=substr($fechaGestion,0,4);		//... asigna anho gestion ...			
		$mesGestion=substr($fechaGestion,4,2);		//... asigna mes gestion ...

        // Se obtienen los registros de la base de datos
        $sql ="SELECT idComprobante,fechaComprobante,cuentaComprobante,debeHaber,monto,glosa,descripcion FROM comprobantedetalle,contaplandectas 
        WHERE year(fechaComprobante)='$anhoGestion' AND month(fechaComprobante)='$mesGestion' AND cuentaComprobante=cuenta ORDER BY idComprobante";

 		$registros = $this->db->query($sql);
 
 		$contador= $registros->num_rows; //...contador de registros que satisfacen la consulta ..
 		
 		if($contador==0){
			$datos['mensaje']='No hay registros en el Diario General para la gestión '.mesLiteral( intval($mesGestion) ).' de '.substr($fechaGestion,0,4);
			$this->load->view('header');
			$this->load->view('mensaje',$datos );
			$this->load->view('footer');
 		}else{
 			// Se carga la libreria fpdf
        	$this->load->library('contabilidad/DiarioGeneralPdf');
		
 			// Creacion del PDF
	        /*
	        * Se crea un objeto de la clase SalAlmacenPdf, recordar que la clase Pdf
	        * heredó todos las variables y métodos de fpdf
	        */
	         
	        ob_clean(); // cierra si es se abrio el envio de pdf...
	        $this->pdf = new DiarioGeneralPdf('L');		//... ('L') sentido horizontal de la hoja ...
			
			$this->pdf->fechaGestion=$fechaGestion;      			//...pasando variable para el header del PDF
			$this->pdf->gestion= mesLiteral( intval($mesGestion) ).' de '.substr($fechaGestion,0,4); 		 	//...pasando variable para el header del PDF
			
	        // Agregamos una página
	        $this->pdf->AddPage('L');
	        // Define el alias para el número de página que se imprimirá en el pie
	        $this->pdf->AliasNbPages();
	 
	        /* Se define el titulo, márgenes izquierdo, derecho y
	         * el color de relleno predeterminado
	         */
	         
	        $this->pdf->SetLeftMargin(10);
	        $this->pdf->SetRightMargin(10);
	        $this->pdf->SetFillColor(200,200,200);
	 
	        // Se define el formato de fuente: Arial, negritas, tamaño 9
	        //$this->pdf->SetFont('Arial', 'B', 9);
	        $this->pdf->SetFont('Arial', '', 9);
	        
	       
	        $diaAnterior ='';		 	// ... corte de control por dia...				
			$totalDebeDia=0.00;			//...inicaliza $totalDebeDia ...
			$totalHaberDia=0.00;		//...inicaliza $totalHaberDia ...
			$totalDebeMes=0.00;			//...inicaliza $totalDebeMes ...
			$totalHaberMes=0.00;		//...inicaliza $totalHaberMes ...
	        foreach ($registros->result() as $reg) {
	            // se imprime el numero actual y despues se incrementa el valor de $x en uno
	            // Se imprimen los datos de cada registro
	            if( $diaAnterior != substr($reg->fechaComprobante,8,2) && $diaAnterior !='' ){   //...corte de control por dia ...
	            	$this->pdf->Ln(5);  //Se agrega un salto de linea...
	            	$this->pdf->Cell(180,5,'','',0,'L',0);
		        	$this->pdf->Cell(15,5,utf8_decode('Totales del día ...'),'',0,'L',0);
					$this->pdf->Cell(20,5,'','',0,'L',0);
		            $this->pdf->Cell(27,5,number_format($totalDebeDia,2),'',0,'R',0);
		            $this->pdf->Cell(5,5,'','',0,'L',0);
		       		$this->pdf->Cell(27,5,number_format($totalHaberDia,2),'',0,'R',0);
					$totalDebeMes= $totalDebeMes + $totalDebeDia;		//...incrementa $totalDebeMes ...
					$totalHaberMes=$totalHaberMes +$totalHaberDia;		//...incrementa $totalHaberMes ...
			
					$totalDebeDia=0.00;		//...inicaliza $totalDebeDia ...
					$totalHaberDia=0.00;	//...inicaliza $totalHaberDia ...
					
	            	$this->pdf->Ln(4);		//Se agrega un salto de linea
					$this->pdf->Ln(4);		//Se agrega un salto de linea
					$this->pdf->Ln(4);		//Se agrega un salto de linea
	            }
	            
				$this->pdf->Cell(1,5,'','',0,'L',0);
				$this->pdf->Cell(3,5,substr($reg->fechaComprobante,8,2),'',0,'L',0);
	            $this->pdf->Cell(5,5,'','',0,'L',0);
				$this->pdf->Cell(15,5,substr($reg->idComprobante,0,6).'-'.substr($reg->idComprobante,6,3),'',0,'L',0);
				$this->pdf->Cell(7,5,'','',0,'L',0);
				$this->pdf->Cell(10,5,$reg->cuentaComprobante,'',0,'L',0);
				$this->pdf->Cell(10,5,'','',0,'L',0);
				$this->pdf->Cell(73,5,$reg->descripcion,'',0,'L',0);
				$this->pdf->Cell(10,5,'','',0,'L',0);
				$this->pdf->Cell(75,5,utf8_decode($reg->glosa),'',0,'L',0);
				
				if($reg->debeHaber=='D'){					//...discrimina si es columna DEBE o HABER ...
					$this->pdf->Cell(8,5,'','',0,'L',0);
					$totalDebeDia=$totalDebeDia + $reg->monto;
				}else{
					$this->pdf->Cell(40,5,'','',0,'L',0);
					$totalHaberDia=$totalHaberDia + $reg->monto;
				}
				
	            $this->pdf->Cell(25,5,number_format($reg->monto,2),'',0,'R',0);
				$diaAnterior = substr($reg->fechaComprobante,8,2);		//..asigna diaAnterior ...
	            //Se agrega un salto de linea
	            $this->pdf->Ln(4);
	        } //...fin foreach ...
	        
        	$this->pdf->Ln(4);	//Se agrega un salto de linea
        	$this->pdf->Cell(180,5,'','',0,'L',0);
        	$this->pdf->Cell(15,5,utf8_decode('Totales del día ...'),'',0,'L',0);
			$this->pdf->Cell(20,5,'','',0,'L',0);
            $this->pdf->Cell(27,5,number_format($totalDebeDia,2),'',0,'R',0);
            $this->pdf->Cell(5,5,'','',0,'L',0);
       		$this->pdf->Cell(27,5,number_format($totalHaberDia,2),'',0,'R',0);
			
			$totalDebeMes= $totalDebeMes + $totalDebeDia;		//...incrementa $totalDebeMes ...
			$totalHaberMes=$totalHaberMes +$totalHaberDia;		//...incrementa $totalHaberMes ...
			
			$this->pdf->Ln(4);	//Se agrega un salto de linea
			$this->pdf->Ln(4);	//Se agrega un salto de linea
        	$this->pdf->Cell(180,5,'','',0,'L',0);
        	$this->pdf->Cell(15,5,utf8_decode('Totales del mes ...'),'',0,'L',0);
			$this->pdf->Cell(20,5,'','',0,'L',0);
            $this->pdf->Cell(27,5,number_format($totalDebeMes,2),'',0,'R',0);
            $this->pdf->Cell(5,5,'','',0,'L',0);
       		$this->pdf->Cell(27,5,number_format($totalHaberMes,2),'',0,'R',0);
			
	         /* PDF Output() settings
	         * Se manda el pdf al navegador
	         *
	         * $this->pdf->Output(nombredelarchivo, destino);
	         *
	         * I = Muestra el pdf en el navegador
	         * D = Envia el pdf para descarga
			 * F: save to a local file
			 * S: return the document as a string. name is ignored.
			 * $pdf->Output(); //default output to browser
			 * $pdf->Output('D:/example2.pdf','F');
			 * $pdf->Output("example2.pdf", 'D');
			 * $pdf->Output('', 'S'); //... Returning the PDF file content as a string:
	         */
	  
	  		$this->pdf->Output('pdfsArchivos/contabilidad/diarioGeneral.pdf', 'F');
	  		
			$datos['documento']="pdfsArchivos/contabilidad/diarioGeneral.pdf";	
			$datos['titulo']='DIARIO GENERAL período de gestión: '.substr($fechaGestion,0,4).'-'.substr($fechaGestion,4,2);	// ... titulo ...
		
			$this->load->view('header');
			$this->load->view('reportePdfSinFechas',$datos );
			$this->load->view('footer');	
 		}
        
	} //... fin funcion: generarReporteDiarioGeneral ...
	
	
	public function generarReporteMayor(){
		//... genera reporte de Mayor en PDF
		$fechaGestion= $_POST['fechaDeGestion']; 	//... lee fechaGestion ...
		$anhoGestion=substr($fechaGestion,0,4);		//... asigna anho gestion ...			
		$mesGestion=substr($fechaGestion,4,2);		//... asigna mes gestion ...
		$mesConsulta=intval($mesGestion);						//...convierte a numero un string....

        // Se obtienen los registros de la base de datos   
        
        $sql="SELECT cuentaComprobante,fechaComprobante,idComprobante,glosa,debeHaber,monto,cuenta,descripcion,debeAcumulado,haberAcumulado,debeMes,haberMes
		 FROM comprobantedetalle,contaplandectas WHERE cast(MONTH(fechaComprobante) as CHAR(";      
        
        $cantDigitos=0;		//..cantidad de digitos como argumento para la consulta ...
        if($mesConsulta<10){
        	$cantDigitos=1;
			$sql=$sql.$cantDigitos;
        }else{
        	$cantDigitos=2;
        	$sql=$sql.$cantDigitos;
        }
        
        $sql=$sql."))='$mesConsulta' AND cast(YEAR(fechaComprobante) as CHAR(4))='$anhoGestion' AND cuentaComprobante=cuenta ORDER BY cuentaComprobante, idComprobante ASC";
     
       
//		$sql="SELECT cuentaComprobante,fechaComprobante,idComprobante,glosa,debeHaber,monto,cuenta,descripcion,debeAcumulado,haberAcumulado,debeMes,haberMes
//		 FROM comprobantedetalle,contaplandectas WHERE cast(MONTH(fechaComprobante) as CHAR(1))='$mesConsulta' AND cast(YEAR(fechaComprobante) as CHAR(4))='$anhoGestion' AND cuentaComprobante=cuenta ORDER BY cuentaComprobante, idComprobante ASC";

 		$registros = $this->db->query($sql);
 
 		$contador= $registros->num_rows; //...contador de registros que satisfacen la consulta ..
 		
 		if($contador==0){
			$datos['mensaje']='No hay registros en el Mayor para la gestión '.mesLiteral( intval($mesGestion) ).' de '.substr($fechaGestion,0,4);
			$this->load->view('header');
			$this->load->view('mensaje',$datos );
			$this->load->view('footer');
 		}else{
 			// Se carga la libreria fpdf
        	$this->load->library('contabilidad/MayorPdf');
		
 			// Creacion del PDF
	        /*
	        * Se crea un objeto de la clase SalAlmacenPdf, recordar que la clase Pdf
	        * heredó todos las variables y métodos de fpdf
	        */
	         
	        ob_clean(); // cierra si es se abrio el envio de pdf...
	        $this->pdf = new MayorPdf();		//... ('L') sentido horizontal de la hoja ...
			
			$this->pdf->fechaGestion=$fechaGestion;      			//...pasando variable para el header del PDF
			$this->pdf->gestion= mesLiteral( intval($mesGestion) ).' de '.substr($fechaGestion,0,4); 		 	//...pasando variable para el header del PDF
			
	        // Agregamos una página
	        $this->pdf->AddPage();			//... ('L') sentido horizontal de la hoja ...
	        // Define el alias para el número de página que se imprimirá en el pie
	        $this->pdf->AliasNbPages();
	 
	        /* Se define el titulo, márgenes izquierdo, derecho y
	         * el color de relleno predeterminado
	         */
	         
	        $this->pdf->SetLeftMargin(10);
	        $this->pdf->SetRightMargin(10);
	        $this->pdf->SetFillColor(200,200,200);
	 
	        // Se define el formato de fuente: Arial, negritas, tamaño 9
	        //$this->pdf->SetFont('Arial', 'B', 9);
	        $this->pdf->SetFont('Arial', '', 8);
	        
	        $cuentaAnterior ='';		// ... corte de control por cuenta comprobante...	
	        $cuentaMayorAnterior=''; 	// ... corte de control por cuenta mayor distinta de la anterior...			
			$totalDebeDia=0.00;			//...inicaliza $totalDebeDia ...
			$totalHaberDia=0.00;		//...inicaliza $totalHaberDia ...
			$espaciado=0;				//...para la columna del saldo ...
			$debeAnterior=0.00;			//...recupera de la tabla contaplandectas ...
			$haberAnterior=0.00;		//...recupera de la tabla contaplandectas ...
			$saldo=0.00;				//...calcula el saldo de la cuenta ...
			
			$this->load->model("tablaGenerica_model");	//...carga el modelo tabla generica ...
			
	        foreach ($registros->result() as $reg) {	 // Se imprimen los datos de cada registro 
	        	$cuentaMayor=substr($reg->cuentaComprobante,0,4).'0000';      							//...pasando variable para el header del PDF
	            $result = $this->tablaGenerica_model->buscar('contaplandectas','cuenta',$cuentaMayor);  //..una vez cargado el modelo de la tabla llama cotizacionvalores..
	   			$descripcionCtaMayor= $result["descripcion"];	// ... forma de asignar cuando se utliza funcion ...buscar ... de tablaGenerica_model ...
	  			$debeAnterior=$reg->debeAcumulado-$reg->debeMes;
	            $haberAnterior=$reg->haberAcumulado-$reg->haberMes;
				  
	            if( $cuentaAnterior != $reg->cuentaComprobante  ){   //...corte de control por dia ...
	            	if($cuentaAnterior !=''){	//... imprime totales ...
	            		$this->pdf->Ln(4);  //Se agrega un salto de linea...
		            	$this->pdf->Cell(85,5,'','',0,'L',0);
			        	$this->pdf->Cell(20,5,utf8_decode('Totales ...'),'',0,'L',0);
						$this->pdf->Cell(8,5,'','',0,'L',0);
			            $this->pdf->Cell(20,5,number_format($totalDebeDia,2),'',0,'R',0);
			            $this->pdf->Cell(8,5,'','',0,'L',0);
			       		$this->pdf->Cell(20,5,number_format($totalHaberDia,2),'',0,'R',0);
						$this->pdf->Cell(7,5,'','',0,'L',0);
		       			$this->pdf->Cell(20,5,number_format($totalDebeDia-$totalHaberDia,2),'',0,'R',0);
						
						$totalDebeDia=0.00;		//...inicaliza $totalDebeDia ...
						$totalHaberDia=0.00;	//...inicaliza $totalHaberDia ...
						
						
						if( $cuentaMayor != $cuentaMayorAnterior && $cuentaMayorAnterior !='' ){   //...corte de control por dia ...
	            	 		$this->pdf->AddPage();			//... salto de pagina ...
						}else{
							$this->pdf->Ln(4);		//Se agrega un salto de linea
							$this->pdf->Ln(4);		//Se agrega un salto de linea
							$this->pdf->Ln(4);		//Se agrega un salto de linea
						}
	   
						
	            	}		//..fin corte de control por cuentaAnterior != '' ...
					
	            	$this->pdf->Cell(80,10,'-------------------------------------------------------------------------------------',0,0,'L');
					$this->pdf->Cell(80,10,'-------------------------------------------------------------------------------------',0,0,'L');
					$this->pdf->Cell(22,10,'----------------------------',0,0,'L');
	            	$this->pdf->Ln(4);
				
			        $this->pdf->Cell(20);
					$this->pdf->Cell(30,10,utf8_decode('Cuenta No.: ').substr($cuentaMayor,0,2).'-'.substr($cuentaMayor,2,2).'-00-00',0,0,'L');
					$this->pdf->Cell(10);
					$this->pdf->Cell(15,10,utf8_decode('Descripción: '),0,0,'L');
					$this->pdf->Cell(3);
					$this->pdf->Cell(30,10,utf8_decode(strtoupper($descripcionCtaMayor)),0,0,'L');
					$this->pdf->Ln(4);
					
					$this->pdf->Cell(30);
					if(substr($reg->cuentaComprobante,6,2)=='00'){
						$this->pdf->Cell(30,10,utf8_decode('Sub-cuenta No.: ').substr($reg->cuentaComprobante,0,2).'-'.substr($reg->cuentaComprobante,2,2).'-'.substr($reg->cuentaComprobante,4,2).'-'.substr($reg->cuentaComprobante,6,2),0,0,'L');
					}else{
						$this->pdf->Cell(35,10,utf8_decode('Sub-sub-cuenta No.: ').substr($reg->cuentaComprobante,0,2).'-'.substr($reg->cuentaComprobante,2,2).'-'.substr($reg->cuentaComprobante,4,2).'-'.substr($reg->cuentaComprobante,6,2),0,0,'L');
					}
					
					$this->pdf->Cell(10);
					$this->pdf->Cell(15,10,utf8_decode('Descripción: '),0,0,'L');
					$this->pdf->Cell(3);
					$this->pdf->Cell(30,10,utf8_decode($reg->descripcion),0,0,'L');
					$this->pdf->Ln(7);
					
					/*
			         * TITULOS DE COLUMNAS
			         *
			         * $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
			        */	 
			        $this->pdf->Cell(3,7,'','TBL',0,'L','0');
			        $this->pdf->Cell(10,7,'fecha','TB',0,'C','0');
					$this->pdf->Cell(10,7,'','TB',0,'L','0');
					$this->pdf->Cell(10,7,'no.Dcto.','TB',0,'C','0');
					$this->pdf->Cell(20,7,'','TB',0,'L','0');
			        $this->pdf->Cell(25,7,utf8_decode('detalle - asiento'),'TB',0,'L','0');
					$this->pdf->Cell(43,7,'','TB',0,'L','0');
					$this->pdf->Cell(10,7,'debe','TB',0,'L','0');
					$this->pdf->Cell(17,7,'','TB',0,'L','0');
					$this->pdf->Cell(10,7,'haber','TB',0,'L','0');
					$this->pdf->Cell(11,7,'','TB',0,'L','0');
					$this->pdf->Cell(15,7,'saldo','TB',0,'R','0');
					$this->pdf->Cell(4,7,'','TBR',0,'R','0');
			        $this->pdf->Ln(6);
	            
					$this->pdf->Cell(21);
					$this->pdf->Cell(60,10,utf8_decode('S a l d o    A n t e r i o r   .   .   .   .   .   .   .   .   .   .   .   .'),0,0,'L');
					$this->pdf->Cell(32,5,'','',0,'L',0);
		            $this->pdf->Cell(20,10,number_format($debeAnterior,2),'',0,'R',0);
		            $this->pdf->Cell(8,10,'','',0,'L',0);
		       		$this->pdf->Cell(20,10,number_format($haberAnterior,2),'',0,'R',0);
					$this->pdf->Cell(7,10,'','',0,'L',0);
					
					$saldo= $debeAnterior - $haberAnterior;
					
		       		$this->pdf->Cell(20,10,number_format($saldo,2),'',0,'R',0);
					$this->pdf->Ln(6);					
	            }
	            
				$this->pdf->Cell(1,5,'','',0,'L',0);
				$this->pdf->Cell(15,5,fechaMysqlParaLatina($reg->fechaComprobante),'',0,'L',0);
	            $this->pdf->Cell(5,5,'','',0,'L',0);
				$this->pdf->Cell(15,5,substr($reg->idComprobante,0,6).'-'.substr($reg->idComprobante,5,3),'',0,'L',0);
				$this->pdf->Cell(5,5,'','',0,'L',0);
				$this->pdf->Cell(60,5,utf8_decode($reg->glosa),'',0,'L',0);
				
				if($reg->debeHaber=='D'){					//...discrimina si es columna DEBE o HABER ...
					$this->pdf->Cell(7,5,'','',0,'L',0);
					$saldo= $saldo + $reg->monto;
					$totalDebeDia=$totalDebeDia + $reg->monto;
					$espaciado=35;				//..para la columna del saldo ...
				}else{
					$this->pdf->Cell(35,5,'','',0,'L',0);
					$saldo= $saldo - $reg->monto;
					$totalHaberDia=$totalHaberDia + $reg->monto;
					$espaciado=7;				//..para la columna del saldo ...
				}
				
	            $this->pdf->Cell(25,5,number_format($reg->monto,2),'',0,'R',0);
				
				
				$this->pdf->Cell($espaciado,5,'','',0,'L',0);
				$this->pdf->Cell(20,5,number_format($saldo,2),'',0,'R',0);
				$cuentaAnterior = $reg->cuentaComprobante;		//..asigna cuentaAnterior ...
				$cuentaMayorAnterior=$cuentaMayor;		//..asigna cuentaMayor ...
	            //Se agrega un salto de linea
	            $this->pdf->Ln(4);
	        } //...fin foreach ...
	        
        	$this->pdf->Ln(4);	//Se agrega un salto de linea
        	$this->pdf->Cell(85,5,'','',0,'L',0);
        	$this->pdf->Cell(20,5,utf8_decode('Totales ...'),'',0,'L',0);
			$this->pdf->Cell(8,5,'','',0,'L',0);
            $this->pdf->Cell(20,5,number_format($totalDebeDia,2),'',0,'R',0);
            $this->pdf->Cell(8,5,'','',0,'L',0);
       		$this->pdf->Cell(20,5,number_format($totalHaberDia,2),'',0,'R',0);
			$this->pdf->Cell(7,5,'','',0,'L',0);
		    $this->pdf->Cell(20,5,number_format($totalDebeDia-$totalHaberDia,2),'',0,'R',0);
			
	         /* PDF Output() settings
	         * Se manda el pdf al navegador
	         *
	         * $this->pdf->Output(nombredelarchivo, destino);
	         *
	         * I = Muestra el pdf en el navegador
	         * D = Envia el pdf para descarga
			 * F: save to a local file
			 * S: return the document as a string. name is ignored.
			 * $pdf->Output(); //default output to browser
			 * $pdf->Output('D:/example2.pdf','F');
			 * $pdf->Output("example2.pdf", 'D');
			 * $pdf->Output('', 'S'); //... Returning the PDF file content as a string:
	         */
	  
	  		$this->pdf->Output('pdfsArchivos/contabilidad/mayor.pdf', 'F');
	  		
			$datos['documento']="pdfsArchivos/contabilidad/mayor.pdf";	
			$datos['titulo']='MAYOR período de gestión: '.substr($fechaGestion,0,4).'-'.substr($fechaGestion,4,2);	// ... titulo ...
		
			$this->load->view('header');
			$this->load->view('reportePdfSinFechas',$datos );
			$this->load->view('footer');	
 		}
        
	} //... fin funcion: generarReporteMayor ...
	
	
	public function generarReporteSumasYsaldos(){
		//... genera reporte PlanDeCuentas en PDF
		
		//... control de permisos de acceso ....
		$permisoUserName=$this->session->userdata('userName');
		$permisoMenu=$this->session->userdata('usuarioMenu');
		$permisoProceso3=$this->session->userdata('usuarioProceso3');
		if($permisoUserName!='superuser' && $permisoUserName!='developer' && $permisoMenu!='contabilidad'){  //... valida permiso de userName y de menu...
			$datos['mensaje']='Usuario NO autorizado para operar Sistema de Contabilidad';
			$this->load->view('header');
			$this->load->view('mensaje',$datos );
			$this->load->view('footer');
		}	//... fin control de permisos de acceso ....
		else {		//... usuario validado ...
			$fechaGestion= $_POST['fechaDeGestion']; 	//... lee fechaGestion ...
			$anhoGestion=substr($fechaGestion,0,4);		//... asigna anho gestion ...			
			$mesGestion=substr($fechaGestion,4,2);		//... asigna mes gestion ...
			// Se carga la libreria fpdf
			$this->load->library('contabilidad/SumasSaldosPdf');
			
			// Se obtienen los registros de la base de datos
			$sql="SELECT * FROM contaplandectas WHERE nivel>='4' AND ( debeacumulado!=0.00 || haberacumulado!=0.00) ";
			
			$registros = $this->db->query($sql);
			 
			$contador= $registros->num_rows; //...contador de registros que satisfacen la consulta ..
			
			if($contador==0){
				$datos['mensaje']='No hay registros para el reporte del BALANCE DE SUMAS Y SALDOS.';
				$this->load->view('header');
				$this->load->view('mensaje',$datos );
				$this->load->view('footer');
			}else{
				// Creacion del PDF
			    /*
			    * Se crea un objeto de la clase SalAlmacenPdf, recordar que la clase Pdf
			    * heredó todos las variables y métodos de fpdf
			    */
			     
			    ob_clean(); // cierra si es se abrio el envio de pdf...
			    $this->pdf = new SumasSaldosPdf();
				
				$this->pdf->fechaGestion=$fechaGestion;      			//...pasando variable para el header del PDF
				$this->pdf->gestion= mesLiteral( intval($mesGestion) ).' de '.substr($fechaGestion,0,4); 		 	//...pasando variable para el header del PDF
		
				
			    // Agregamos una página
			    $this->pdf->AddPage();
			    // Define el alias para el número de página que se imprimirá en el pie
			    $this->pdf->AliasNbPages();
			 
			    /* Se define el titulo, márgenes izquierdo, derecho y
			    * el color de relleno predeterminado
			    */
			         
			    // Se define el formato de fuente: Arial, negritas, tamaño 9
			    //$this->pdf->SetFont('Arial', 'B', 9);
			    $this->pdf->SetFont('Arial', '', 7);
			    $espacio=2; 			//... epacio variable para imprimir ...
				$contador=0;		//... cuenta registros que son sub-sub.cuenta ...	
				$cuentaActual='';			//... asigna cuenta actual ...		    
		    	$subCuentaAnterior='';		//... para hacer corte de control por diferencias de subCuentas..
		    	$subCuentaAnteriorDescripcion='';
				$subCuentaAnteriorDebeMes=0.00;
				$subCuentaAnteriorHaberMes=0.00;
				$subCuentaAnteriorDebeAcumulado=0.00;
				$subCuentaAnteriorHaberAcumulado=0.00;
				
				$cuentaMayorAnterior='';		//... para hacer corte de control por diferencias de cuentaMayor..
				$cuentaMayorDescripcion='';
				$cuentaMayorDebeMes=0.00;
				$cuentaMayorHaberMes=0.00;
				$cuentaMayorDebeAcumulado=0.00;
				$cuentaMayorHaberAcumulado=0.00;
				
				$totalDebeMes=0.00;					//...acumula total debeMes ...
				$totalHaberMes=0.00;				//...acumula total haberMes ...
				$totalDebeAcumulado=0.00;			//...acumula total debeAcumulado ...
				$totalHaberAcumulado=0.00;			//...acumula total haberAcumulado ...
				
			    foreach ($registros->result() as $registro) {
			       	// Se imprimen los datos de cada registro
			       	$cuentaActual=$registro->cuenta;
			        if(substr($cuentaActual,0,6)!=$subCuentaAnterior && $subCuentaAnterior!=''   ){			//... corte de control por diferencias de subCuentas ...
			        	//$this->pdf->Ln(2);		//Se agrega un salto de linea
			        	$this->pdf->Cell(1,5,'----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------','',0,'L',0);
						$this->pdf->Ln(3);		//Se agrega un salto de linea
						$this->pdf->Cell(18,5,'','',0,'L',0);
						$this->pdf->Cell(56,5,utf8_decode($subCuentaAnteriorDescripcion),'',0,'L',0);
						$this->pdf->Cell(5,5,'','',0,'L',0);
						$this->pdf->Cell(17,5,number_format($subCuentaAnteriorDebeMes,2),'',0,'R',0);
						$this->pdf->Cell(6,5,'','',0,'L',0);
						$this->pdf->Cell(17,5,number_format($subCuentaAnteriorHaberMes,2),'',0,'R',0);
						$this->pdf->Cell(6,5,'','',0,'L',0);
						$this->pdf->Cell(17,5,number_format($subCuentaAnteriorDebeAcumulado,2),'',0,'R',0);
						$this->pdf->Cell(6,5,'','',0,'L',0);
			       		$this->pdf->Cell(17,5,number_format($subCuentaAnteriorHaberAcumulado,2),'',0,'R',0);
			          	$this->pdf->Cell(6,5,'','',0,'L',0);
			       		$this->pdf->Cell(17,5,number_format($subCuentaAnteriorDebeAcumulado - $subCuentaAnteriorHaberAcumulado ,2),'',0,'R',0);
						$this->pdf->Ln(2);		//Se agrega un salto de linea
			        	$this->pdf->Cell(1,5,'----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------','',0,'L',0);
						
						$this->pdf->Ln(3);		//Se agrega un salto de linea
			        }

					if(substr($cuentaActual,0,4).'0000'!=$cuentaMayorAnterior && $cuentaMayorAnterior!=''   ){			//... corte de control por diferencias de cuentaMayor ...
			        	//$this->pdf->Ln(2);		//Se agrega un salto de linea
			        	$this->pdf->Cell(1,5,'----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------','',0,'L',0);
						$this->pdf->Ln(3);		//Se agrega un salto de linea
						$this->pdf->Cell(18,5,'','',0,'L',0);
						$this->pdf->Cell(56,5,utf8_decode( strtoupper($cuentaMayorDescripcion) ),'',0,'L',0);
						$this->pdf->Cell(5,5,'','',0,'L',0);
						$this->pdf->Cell(17,5,number_format($cuentaMayorDebeMes,2),'',0,'R',0);
						$this->pdf->Cell(6,5,'','',0,'L',0);
						$this->pdf->Cell(17,5,number_format($cuentaMayorHaberMes,2),'',0,'R',0);
						$this->pdf->Cell(6,5,'','',0,'L',0);
						$this->pdf->Cell(17,5,number_format($cuentaMayorDebeAcumulado,2),'',0,'R',0);
						$this->pdf->Cell(6,5,'','',0,'L',0);
			       		$this->pdf->Cell(17,5,number_format($cuentaMayorHaberAcumulado,2),'',0,'R',0);
			          	$this->pdf->Cell(6,5,'','',0,'L',0);
			       		$this->pdf->Cell(17,5,number_format($cuentaMayorDebeAcumulado - $cuentaMayorHaberAcumulado ,2),'',0,'R',0);
						$this->pdf->Ln(2);		//Se agrega un salto de linea
			        	$this->pdf->Cell(1,5,'=================================================================================================================================','',0,'L',0);
						
						$this->pdf->Ln(6);		//Se agrega un salto de linea
			        }


					$this->pdf->Cell($espacio*($registro->nivel)-7,5,'','',0,'L',0);
					$this->pdf->Cell(2,5,$registro->cuenta,'',0,'L',0);
					$this->pdf->Cell(15,5,'','',0,'L',0);
					$this->pdf->Cell(56,5,utf8_decode($registro->descripcion),'',0,'L',0);
					
					if($registro->nivel=='4'){					//... espaciado ...
						$this->pdf->Cell(5,5,'','',0,'L',0);
						$subCuentaAnteriorDescripcion=$registro->descripcion;
						$subCuentaAnteriorDebeMes=$registro->debemes;
						$subCuentaAnteriorHaberMes=$registro->habermes;
						$subCuentaAnteriorDebeAcumulado=$registro->debeacumulado;
						$subCuentaAnteriorhaberAcumulado=$registro->haberacumulado;
						$cuenta=substr($registro->cuenta,0,6);
						$sql="SELECT * FROM contaplandectas WHERE cuenta LIKE '$cuenta%' AND nivel='5' ";
						$result = $this->db->query($sql);
						$contador= $result->num_rows;
						$subCuentaAnterior='';
					}else{
						$this->pdf->Cell(3,5,'','',0,'L',0);
						$contador=0;			//... variable para ssaber si tiene sub-sub-cuenta ...
						$subCuentaAnterior=substr($registro->cuenta,0,6);
					}
					
					if($contador==0){		//... imprime saldos cuando son sub-cuentas SIN sub-sub-cuentas O son sub-sub-cuentas ...
						$this->pdf->Cell(17,5,number_format($registro->debemes,2),'',0,'R',0);
						$this->pdf->Cell(6,5,'','',0,'L',0);
						$this->pdf->Cell(17,5,number_format($registro->habermes,2),'',0,'R',0);
						$this->pdf->Cell(6,5,'','',0,'L',0);
						$this->pdf->Cell(17,5,number_format($registro->debeacumulado,2),'',0,'R',0);
						$this->pdf->Cell(6,5,'','',0,'L',0);
			       		$this->pdf->Cell(17,5,number_format($registro->haberacumulado,2),'',0,'R',0);
			          	$this->pdf->Cell(6,5,'','',0,'L',0);
			       		$this->pdf->Cell(17,5,number_format($registro->debeacumulado - $registro->haberacumulado ,2),'',0,'R',0);
						$totalDebeMes=$totalDebeMes + $registro->debemes;						//...acumula total debeMes ...
						$totalHaberMes=$totalHaberMes + $registro->habermes;					//...acumula total haberMes ...
						$totalDebeAcumulado=$totalDebeAcumulado + $registro->debeacumulado;		//...acumula total debeAcumulado ...
						$totalHaberAcumulado=$totalHaberAcumulado + $registro->haberacumulado;	//...acumula total haberAcumulado ...
					}
					
					$cuentaMayorAnterior=substr($registro->cuenta,0,4).'0000';		//...asigna cuenta mayor anterior
					$this->load->model("tablaGenerica_model");	//...carga el modelo tabla generica ...
					$regCtaMayor= $this->tablaGenerica_model->buscar('contaplandectas','cuenta',$cuentaMayorAnterior); //..una vez cargado el modelo para la tabla contaplandectas..
					$cuentaMayorDescripcion= $regCtaMayor["descripcion"];		// ... forma de asignar cuando se utliza funcion ...buscar ... de tablaGenerica_model ...
					$cuentaMayorDebeMes=$regCtaMayor["debemes"];				// ... forma de asignar cuando se utliza funcion ...buscar ... de tablaGenerica_model ...
					$cuentaMayorHaberMes=$regCtaMayor["habermes"];				// ... forma de asignar cuando se utliza funcion ...buscar ... de tablaGenerica_model ...
					$cuentaMayorDebeAcumulado=$regCtaMayor["debeacumulado"];	// ... forma de asignar cuando se utliza funcion ...buscar ... de tablaGenerica_model ...
					$cuentaMayorHaberAcumulado=$regCtaMayor["haberacumulado"];	// ... forma de asignar cuando se utliza funcion ...buscar ... de tablaGenerica_model ...
					
		        	$this->pdf->Ln(4);		//Se agrega un salto de linea
					
			    }		//...fin foreach ...
			    
			    if( $subCuentaAnterior!=''){			//... corte de control por diferencias de subCuentas ...
		        	//$this->pdf->Ln(2);		//Se agrega un salto de linea
		        	$this->pdf->Cell(1,5,'----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------','',0,'L',0);
					$this->pdf->Ln(3);		//Se agrega un salto de linea
					$this->pdf->Cell(18,5,'','',0,'L',0);
					$this->pdf->Cell(56,5,utf8_decode($subCuentaAnteriorDescripcion),'',0,'L',0);
					$this->pdf->Cell(5,5,'','',0,'L',0);
					$this->pdf->Cell(17,5,number_format($subCuentaAnteriorDebeMes,2),'',0,'R',0);
					$this->pdf->Cell(6,5,'','',0,'L',0);
					$this->pdf->Cell(17,5,number_format($subCuentaAnteriorHaberMes,2),'',0,'R',0);
					$this->pdf->Cell(6,5,'','',0,'L',0);
					$this->pdf->Cell(17,5,number_format($subCuentaAnteriorDebeAcumulado,2),'',0,'R',0);
					$this->pdf->Cell(6,5,'','',0,'L',0);
		       		$this->pdf->Cell(17,5,number_format($subCuentaAnteriorHaberAcumulado,2),'',0,'R',0);
		          	$this->pdf->Cell(6,5,'','',0,'L',0);
		       		$this->pdf->Cell(17,5,number_format($subCuentaAnteriorDebeAcumulado - $subCuentaAnteriorHaberAcumulado ,2),'',0,'R',0);
					$this->pdf->Ln(2);		//Se agrega un salto de linea
		        	$this->pdf->Cell(1,5,'----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------','',0,'L',0);
					
					$this->pdf->Ln(3);		//Se agrega un salto de linea
			    }
			    
			    //... imprime totales de la cuenta mayor ........
			    $this->pdf->Cell(1,5,'----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------','',0,'L',0);
				$this->pdf->Ln(3);		//Se agrega un salto de linea
				$this->pdf->Cell(18,5,'','',0,'L',0);
				$this->pdf->Cell(56,5,utf8_decode( strtoupper($cuentaMayorDescripcion) ),'',0,'L',0);
				$this->pdf->Cell(5,5,'','',0,'L',0);
				$this->pdf->Cell(17,5,number_format($cuentaMayorDebeMes,2),'',0,'R',0);
				$this->pdf->Cell(6,5,'','',0,'L',0);
				$this->pdf->Cell(17,5,number_format($cuentaMayorHaberMes,2),'',0,'R',0);
				$this->pdf->Cell(6,5,'','',0,'L',0);
				$this->pdf->Cell(17,5,number_format($cuentaMayorDebeAcumulado,2),'',0,'R',0);
				$this->pdf->Cell(6,5,'','',0,'L',0);
	       		$this->pdf->Cell(17,5,number_format($cuentaMayorHaberAcumulado,2),'',0,'R',0);
	          	$this->pdf->Cell(6,5,'','',0,'L',0);
	       		$this->pdf->Cell(17,5,number_format($cuentaMayorDebeAcumulado - $cuentaMayorHaberAcumulado ,2),'',0,'R',0);
				$this->pdf->Ln(2);		//Se agrega un salto de linea
	        	$this->pdf->Cell(1,5,'=================================================================================================================================','',0,'L',0);
				
				$this->pdf->Ln(6);		//Se agrega un salto de linea
				
				//... imprime totales ........
			    $this->pdf->Cell(1,5,'=================================================================================================================================','',0,'L',0);
				$this->pdf->Ln(3);		//Se agrega un salto de linea
				$this->pdf->Cell(18,5,'','',0,'L',0);
				$this->pdf->Cell(56,5,utf8_decode( 'Totales' ),'',0,'L',0);
				$this->pdf->Cell(5,5,'','',0,'L',0);
				$this->pdf->Cell(17,5,number_format($totalDebeMes,2),'',0,'R',0);
				$this->pdf->Cell(6,5,'','',0,'L',0);
				$this->pdf->Cell(17,5,number_format($totalHaberMes,2),'',0,'R',0);
				$this->pdf->Cell(6,5,'','',0,'L',0);
				$this->pdf->Cell(17,5,number_format($totalDebeAcumulado,2),'',0,'R',0);
				$this->pdf->Cell(6,5,'','',0,'L',0);
	       		$this->pdf->Cell(17,5,number_format($totalHaberAcumulado,2),'',0,'R',0);
	          	$this->pdf->Cell(6,5,'','',0,'L',0);
	       		$this->pdf->Cell(17,5,number_format($totalDebeAcumulado - $totalHaberAcumulado ,2),'',0,'R',0);
				$this->pdf->Ln(2);		//Se agrega un salto de linea
	        	$this->pdf->Cell(1,5,'=================================================================================================================================','',0,'L',0);			
				//... fin impresion totales  ........
				
				     /* PDF Output() settings
				     * Se manda el pdf al navegador
				     *
				     * $this->pdf->Output(nombredelarchivo, destino);
				     *
				     * I = Muestra el pdf en el navegador
				     * D = Envia el pdf para descarga
					 * F: save to a local file
					 * S: return the document as a string. name is ignored.
					 * $pdf->Output(); //default output to browser
					 * $pdf->Output('D:/example2.pdf','F');
					 * $pdf->Output("example2.pdf", 'D');
					 * $pdf->Output('', 'S'); //... Returning the PDF file content as a string:
				     */
				  
				  	$this->pdf->Output('pdfsArchivos/contabilidad/balancesumasysaldos.pdf', 'F');
					
					$datos['documento']="pdfsArchivos/contabilidad/balancesumasysaldos.pdf";	
					$datos['titulo']=' BALANCE DE SUMAS Y SALDOS período de gestión: '.substr($fechaGestion,0,4).'-'.substr($fechaGestion,4,2);	// ... titulo ...
					
					$this->load->view('header');
					$this->load->view('reportePdfSinFechas',$datos );
					$this->load->view('footer');	
				}
			}	//.. fin IF validar usuario ...
	    
	} //... fin funcion: generarReporteSumasYsaldos ...
	
	
	public function generarReporteBalanceGeneral(){
		//... genera reporte de diarioGeneral en PDF
		$fechaGestion= $_POST['fechaDeGestion']; 		//... lee fechaGestion ...
		$anhoGestion=substr($fechaGestion,0,4);		//... asigna anho gestion ...			
		$mesGestion=substr($fechaGestion,4,2);		//... asigna mes gestion ...

        		//... control de permisos de acceso ....
		$permisoUserName=$this->session->userdata('userName');
		$permisoMenu=$this->session->userdata('usuarioMenu');
		$permisoProceso3=$this->session->userdata('usuarioProceso3');
		if($permisoUserName!='superuser' && $permisoUserName!='developer' && $permisoMenu!='contabilidad'){  //... valida permiso de userName y de menu...
			$datos['mensaje']='Usuario NO autorizado para operar Sistema de Contabilidad';
			$this->load->view('header');
			$this->load->view('mensaje',$datos );
			$this->load->view('footer');
		}	//... fin control de permisos de acceso ....
		else {		//... usuario validado ...
			// Se carga la libreria fpdf
			$this->load->library('contabilidad/BalanceGeneralPdf');
			
			// Se obtienen los registros de la base de datos
			$sql="SELECT cuenta,descripcion,debeacumulado,haberacumulado,nivel FROM contaplandectas WHERE nivel<='3' AND cuenta<='39999999' AND(debeacumulado!=0.00 || haberacumulado!=0.00) ";
			
			$registros = $this->db->query($sql);
			$contador= $registros->num_rows; //...contador de registros que satisfacen la consulta ..
			
			if($contador==0){
				$datos['mensaje']='No hay registros seleccionados para el BALANCE GENERAL.';
				$this->load->view('header');
				$this->load->view('mensaje',$datos );
				$this->load->view('footer');
			}else{
				// Creacion del PDF
			    /*
			    * Se crea un objeto de la clase SalAlmacenPdf, recordar que la clase Pdf
			    * heredó todos las variables y métodos de fpdf
			    */
			     
			    ob_clean(); // cierra si es se abrio el envio de pdf...
			    $this->pdf = new BalanceGeneralPdf();
				$this->pdf->fechaGestion=$fechaGestion;      											 //...pasando variable para el header del PDF
				$this->pdf->gestion= mesLiteral( intval($mesGestion) ).' de '.substr($fechaGestion,0,4); //...pasando variable para el header del PDF
		
			    // Agregamos una página
			    $this->pdf->AddPage();
			    // Define el alias para el número de página que se imprimirá en el pie
			    $this->pdf->AliasNbPages();
			 
			    /* Se define el titulo, márgenes izquierdo, derecho y
			    * el color de relleno predeterminado
			    */
			         
			    // Se define el formato de fuente: Arial, negritas, tamaño 9
			    //$this->pdf->SetFont('Arial', 'B', 9);
			    $this->pdf->SetFont('Arial', '', 9);
			    $espacio=1; 			//... epacio variable para imprimir ...
			    $cuentaAnteriorSubGrupo='';		//...para corte de control por cuentaSubGrupo ...
			    $nivelAnterior='';				//...para corte de control por cuentaSubGrupo ...
			    $saldoSubGrupo=0.00;			//...saldoSubGrupo ...
			    $totalActivo=0.00;				//...acumula saldos cuentas del activo ...
			    $totalPasivoPatrimonio=0.00;	//...acumula saldos cuentas del pasivo y patrimonio ...
			    $numeroLineas=0; 	//...numero de lineas de impresion ...
			    
			    foreach ($registros->result() as $registro) {
			        // Se imprimen los datos de cada registro
			        $numeroLineas = $numeroLineas +1;
					
			        if(substr($registro->cuenta,0,2)!=$cuentaAnteriorSubGrupo && $cuentaAnteriorSubGrupo!='' && $nivelAnterior>'1' ) {
						$this->pdf->Cell(12,5,'','',0,'L',0);
						$this->pdf->Cell(16,5,number_format($saldoSubGrupo ,2),'',0,'R',0);
			        }
					if($cuentaAnteriorSubGrupo!=''){
						 $this->pdf->Ln(5);
					}
			       
			       	$this->pdf->Cell($espacio*($registro->nivel)*($registro->nivel),5,'','',0,'L',0);
					
			       	if($registro->nivel=='1'){		//... si es nivel=1 imprime en mayusculas ...
			       		$this->pdf->Cell(67,5,strtoupper(utf8_decode($registro->descripcion)),'',0,'L',0);
			       	}else{
			       		$this->pdf->Cell(67,5,utf8_decode($registro->descripcion),'',0,'L',0);
			       	}
					
					if($registro->nivel=='2'){			//... acumula saldos por SubGrupo ...
						$saldoSubGrupo= $registro->debeacumulado - $registro->haberacumulado;
						if($registro->cuenta<='19999999'){
							$totalActivo= $totalActivo + ($registro->debeacumulado - $registro->haberacumulado);
						}else{
							$totalPasivoPatrimonio= $totalPasivoPatrimonio + ($registro->debeacumulado - $registro->haberacumulado);
						}			
					}
					
		       		if($registro->nivel=='3'){
		       			if($registro->cuenta<='19999999'){
			        		$this->pdf->Cell($espacio*($registro->nivel)*($registro->nivel),5,'','',0,'L',0);
			        	}else{
			        		$this->pdf->Cell(54+$espacio*($registro->nivel)*($registro->nivel),5,'','',0,'L',0);
			        	}
						
		       			$this->pdf->Cell(6,5,'','',0,'L',0);
						$this->pdf->Cell(16,5,number_format($registro->debeacumulado - $registro->haberacumulado ,2),'',0,'R',0);
		       		}
		         
				  	$cuentaAnteriorSubGrupo=substr($registro->cuenta,0,2);		//...par corte de control por cuentaSubGrupo ...
				  	$nivelAnterior= $registro->nivel;							//...par corte de control por cuentaSubGrupo ...
				  							
			    }			//... fin foreach ....
			    
			    
			    $this->pdf->Cell(12,5,'','',0,'L',0);
				$this->pdf->Cell(16,5,number_format($saldoSubGrupo ,2),'',0,'R',0);		//... saldo del subGrupo ...
			    
			    //... imprime totales ........
			    $this->pdf->Ln(5);
			    $this->pdf->Cell(1,5,'=====================================================================================================','',0,'L',0);
				$this->pdf->Ln(3);		//Se agrega un salto de linea
				$this->pdf->Cell(18,5,'','',0,'L',0);
				$this->pdf->Cell(56,5,utf8_decode( 'Totales' ),'',0,'L',0);
				$this->pdf->Cell(44,5,'','',0,'L',0);
				$this->pdf->Cell(17,5,number_format($totalActivo,2),'',0,'R',0);
				$this->pdf->Cell(37,5,'','',0,'L',0);
	       		$this->pdf->Cell(17,5,number_format($totalPasivoPatrimonio ,2),'',0,'R',0);
				$this->pdf->Ln(2);		//Se agrega un salto de linea
	        	$this->pdf->Cell(1,5,'=====================================================================================================','',0,'L',0);			
				//... fin impresion totales  ........
				
				
				for($x=$numeroLineas; $x<45; $x++){
					$this->pdf->Ln('5');				//... imprime lineas en blanco ...
				}
				
				$this->pdf->Cell(40,5,'','',0,'L',0);
				$this->pdf->Cell(20,5,'____________________','',0,'L',0);
				$this->pdf->Cell(50,5,'','',0,'L',0);
				$this->pdf->Cell(20,5,'____________________','',0,'L',0);
				
				$this->pdf->Ln('5');
				$this->pdf->Cell(49,5,'','',0,'L',0);
				$this->pdf->Cell(20,5,'Contador general','',0,'C',0);
				$this->pdf->Cell(50,5,'','',0,'L',0);
				$this->pdf->Cell(20,5,'Gerente general','',0,'C',0);
				
					
					
				     /* PDF Output() settings
				     * Se manda el pdf al navegador
				     *
				     * $this->pdf->Output(nombredelarchivo, destino);
				     *
				     * I = Muestra el pdf en el navegador
				     * D = Envia el pdf para descarga
					 * F: save to a local file
					 * S: return the document as a string. name is ignored.
					 * $pdf->Output(); //default output to browser
					 * $pdf->Output('D:/example2.pdf','F');
					 * $pdf->Output("example2.pdf", 'D');
					 * $pdf->Output('', 'S'); //... Returning the PDF file content as a string:
				     */
				  
				  	$this->pdf->Output('pdfsArchivos/contabilidad/balanceGeneral.pdf', 'F');
					
					$datos['documento']="pdfsArchivos/contabilidad/balanceGeneral.pdf";	
					$datos['titulo']=' BALANCE GENERAL período de gestión: '.substr($fechaGestion,0,4).'-'.substr($fechaGestion,4,2);	// ... titulo ...
					
					$this->load->view('header');
					$this->load->view('reportePdfSinFechas',$datos );
					$this->load->view('footer');	
				}
			}	//.. fin IF validar usuario ...
        
	} //... fin funcion: generarReporteBalanceGeneral ...
	
	
		public function generarReporteEstadoResultados(){
		//... genera reporte de diarioGeneral en PDF
		$fechaGestion= $_POST['fechaDeGestion']; 		//... lee fechaGestion ...
		$anhoGestion=substr($fechaGestion,0,4);		//... asigna anho gestion ...			
		$mesGestion=substr($fechaGestion,4,2);		//... asigna mes gestion ...

        		//... control de permisos de acceso ....
		$permisoUserName=$this->session->userdata('userName');
		$permisoMenu=$this->session->userdata('usuarioMenu');
		$permisoProceso3=$this->session->userdata('usuarioProceso3');
		if($permisoUserName!='superuser' && $permisoUserName!='developer' && $permisoMenu!='contabilidad'){  //... valida permiso de userName y de menu...
			$datos['mensaje']='Usuario NO autorizado para operar Sistema de Contabilidad';
			$this->load->view('header');
			$this->load->view('mensaje',$datos );
			$this->load->view('footer');
		}	//... fin control de permisos de acceso ....
		else {		//... usuario validado ...
			// Se carga la libreria fpdf
			$this->load->library('contabilidad/EstadoResultadosPdf');
			
			// Se obtienen los registros de la base de datos
			$sql="SELECT cuenta,descripcion,debeacumulado,haberacumulado,nivel FROM contaplandectas WHERE nivel<='3' AND cuenta>='40000000' AND cuenta<='69999999' AND(debeacumulado!=0.00 || haberacumulado!=0.00) ";
			
			$registros = $this->db->query($sql);
			$contador= $registros->num_rows; //...contador de registros que satisfacen la consulta ..
			
			if($contador==0){
				$datos['mensaje']='No hay registros seleccionados para el ESTADO DE RESULTADOS';
				$this->load->view('header');
				$this->load->view('mensaje',$datos );
				$this->load->view('footer');
			}else{
				// Creacion del PDF
			    /*
			    * Se crea un objeto de la clase SalAlmacenPdf, recordar que la clase Pdf
			    * heredó todos las variables y métodos de fpdf
			    */
			     
			    ob_clean(); // cierra si es se abrio el envio de pdf...
			    $this->pdf = new EstadoResultadosPdf();
				$this->pdf->fechaGestion=$fechaGestion;      											 //...pasando variable para el header del PDF
				$this->pdf->gestion= mesLiteral( intval($mesGestion) ).' de '.substr($fechaGestion,0,4); //...pasando variable para el header del PDF
		
			    // Agregamos una página
			    $this->pdf->AddPage();
			    // Define el alias para el número de página que se imprimirá en el pie
			    $this->pdf->AliasNbPages();
			 
			    /* Se define el titulo, márgenes izquierdo, derecho y
			    * el color de relleno predeterminado
			    */
			         
			    // Se define el formato de fuente: Arial, negritas, tamaño 9
			    //$this->pdf->SetFont('Arial', 'B', 9);
			    $this->pdf->SetFont('Arial', '', 9);
			    $espacio=1; 			//... epacio variable para imprimir ...
			    $cuentaAnteriorSubGrupo='';		//...para corte de control por cuentaSubGrupo ...
			    $nivelAnterior='';				//...para corte de control por cuentaSubGrupo ...
			    $saldoSubGrupo=0.00;			//...saldoSubGrupo ...
			    $totalActivo=0.00;				//...acumula saldos cuentas del activo ...
			    $totalPasivoPatrimonio=0.00;	//...acumula saldos cuentas del pasivo y patrimonio ...
			    $numeroLineas=0; 	//...numero de lineas de impresion ...
			    
			    foreach ($registros->result() as $registro) {
			        // Se imprimen los datos de cada registro
			        $numeroLineas = $numeroLineas +1;
					
			        if(substr($registro->cuenta,0,2)!=$cuentaAnteriorSubGrupo && $cuentaAnteriorSubGrupo!='' && $nivelAnterior>'1' ) {
						$this->pdf->Cell(12,5,'','',0,'L',0);
						$this->pdf->Cell(16,5,number_format($saldoSubGrupo ,2),'',0,'R',0);
			        }
					if($cuentaAnteriorSubGrupo!=''){
						 $this->pdf->Ln(5);
					}
			       
			       	$this->pdf->Cell($espacio*($registro->nivel)*($registro->nivel),5,'','',0,'L',0);
					
			       	if($registro->nivel=='1'){		//... si es nivel=1 imprime en mayusculas ...
			       		$this->pdf->Cell(67,5,strtoupper(utf8_decode($registro->descripcion)),'',0,'L',0);
			       	}else{
			       		$this->pdf->Cell(67,5,utf8_decode($registro->descripcion),'',0,'L',0);
			       	}
					
					if($registro->nivel=='2'){			//... acumula saldos por SubGrupo ...
						$saldoSubGrupo= $registro->debeacumulado - $registro->haberacumulado;
						if($registro->cuenta<='49999999'){
							$totalActivo= $totalActivo + ($registro->debeacumulado - $registro->haberacumulado);
						}else{
							$totalPasivoPatrimonio= $totalPasivoPatrimonio + ($registro->debeacumulado - $registro->haberacumulado);
						}			
					}
					
		       		if($registro->nivel=='3'){
		       			if($registro->cuenta<='49999999'){
			        		$this->pdf->Cell($espacio*($registro->nivel)*($registro->nivel),5,'','',0,'L',0);
			        	}else{
			        		$this->pdf->Cell(54+$espacio*($registro->nivel)*($registro->nivel),5,'','',0,'L',0);
			        	}
						
		       			$this->pdf->Cell(6,5,'','',0,'L',0);
						$this->pdf->Cell(16,5,number_format($registro->debeacumulado - $registro->haberacumulado ,2),'',0,'R',0);
		       		}
		         
				  	$cuentaAnteriorSubGrupo=substr($registro->cuenta,0,2);		//...par corte de control por cuentaSubGrupo ...
				  	$nivelAnterior= $registro->nivel;							//...par corte de control por cuentaSubGrupo ...
				  							
			    }			//... fin foreach ....
			    
			    
			    $this->pdf->Cell(12,5,'','',0,'L',0);
				$this->pdf->Cell(16,5,number_format($saldoSubGrupo ,2),'',0,'R',0);		//... saldo del subGrupo ...
			    
			    //... imprime totales ........
			    $this->pdf->Ln(5);
			    $this->pdf->Cell(1,5,'=====================================================================================================','',0,'L',0);
				$this->pdf->Ln(3);		//Se agrega un salto de linea
				$this->pdf->Cell(18,5,'','',0,'L',0);
				$this->pdf->Cell(56,5,utf8_decode( 'Totales' ),'',0,'L',0);
				$this->pdf->Cell(44,5,'','',0,'L',0);
				$this->pdf->Cell(17,5,number_format($totalActivo,2),'',0,'R',0);
				$this->pdf->Cell(37,5,'','',0,'L',0);
	       		$this->pdf->Cell(17,5,number_format($totalPasivoPatrimonio ,2),'',0,'R',0);
				$this->pdf->Ln(2);		//Se agrega un salto de linea
	        	$this->pdf->Cell(1,5,'=====================================================================================================','',0,'L',0);			
				//... fin impresion totales  ........
				
				
				for($x=$numeroLineas; $x<45; $x++){
					$this->pdf->Ln('5');				//... imprime lineas en blanco ...
				}
				
				$this->pdf->Cell(40,5,'','',0,'L',0);
				$this->pdf->Cell(20,5,'____________________','',0,'L',0);
				$this->pdf->Cell(50,5,'','',0,'L',0);
				$this->pdf->Cell(20,5,'____________________','',0,'L',0);
				
				$this->pdf->Ln('5');
				$this->pdf->Cell(49,5,'','',0,'L',0);
				$this->pdf->Cell(20,5,'Contador general','',0,'C',0);
				$this->pdf->Cell(50,5,'','',0,'L',0);
				$this->pdf->Cell(20,5,'Gerente general','',0,'C',0);
				
					
					
				     /* PDF Output() settings
				     * Se manda el pdf al navegador
				     *
				     * $this->pdf->Output(nombredelarchivo, destino);
				     *
				     * I = Muestra el pdf en el navegador
				     * D = Envia el pdf para descarga
					 * F: save to a local file
					 * S: return the document as a string. name is ignored.
					 * $pdf->Output(); //default output to browser
					 * $pdf->Output('D:/example2.pdf','F');
					 * $pdf->Output("example2.pdf", 'D');
					 * $pdf->Output('', 'S'); //... Returning the PDF file content as a string:
				     */
				  
				  	$this->pdf->Output('pdfsArchivos/contabilidad/estadoResultados.pdf', 'F');
					
					$datos['documento']="pdfsArchivos/contabilidad/estadoResultados.pdf";	
					$datos['titulo']=' ESTADO DE RESULTADOS período de gestión: '.substr($fechaGestion,0,4).'-'.substr($fechaGestion,4,2);	// ... titulo ...
					
					$this->load->view('header');
					$this->load->view('reportePdfSinFechas',$datos );
					$this->load->view('footer');	
				}
			}	//.. fin IF validar usuario ...
        
	} //... fin funcion: generarReporteEstadoResultados ...
	
	
	function verComprobante(){
		//... control de permisos de acceso ....
		$permisoUserName=$this->session->userdata('userName');
		$permisoMenu=$this->session->userdata('usuarioMenu');
		$permisoProceso1=$this->session->userdata('usuarioProceso1');
		if($permisoUserName!='superuser' && $permisoUserName!='developer' && $permisoMenu!='contabilidad'){  //... valida permiso de userName y de menu ...
			$datos['mensaje']='Usuario NO autorizado para operar Sistema de Contabilidad';
			$this->load->view('header');
			$this->load->view('mensaje',$datos );
			$this->load->view('footer');
		}			// ... fin control permiso de accesos...
		else {
			$this->load->model("tablaGenerica_model");
			
			/* URL a la que se desea agregar la paginación*/
	    	$config['base_url'] = base_url().'contabilidad/verComprobante';
			
			/*Obtiene el total de registros a paginar */
	    	$config['total_rows'] = $this->tablaGenerica_model->get_total_registros('comprobantecabecera');
		
			$contador= $this->tablaGenerica_model->get_total_registros('comprobantecabecera'); //...contador de registros  ...		
			if($contador==0){
				$datos['mensaje']='No hay registros para mostrar ';
				$this->load->view('header');
				$this->load->view('mensaje',$datos );
				$this->load->view('footer');
			}else{
				/*Obtiene el numero de registros a mostrar por pagina */
				$config['per_page'] = '1';
				
				/*Indica que segmento de la URL tiene la paginación, por default es 3*/
				$config['uri_segment'] = '3';
				$desde = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
			  
				/*Se personaliza la paginación para que se adapte a bootstrap*/
			    $config['cur_tag_open'] = '<li class="active"><a href="#">';
			    $config['cur_tag_close'] = '</a></li>';
			    $config['num_tag_open'] = '<li>';
			    $config['num_tag_close'] = '</li>';
			    $config['last_link'] = FALSE;
			    $config['first_link'] = FALSE;
			    $config['next_link'] = '&raquo;';
			    $config['next_tag_open'] = '<li>';
			    $config['next_tag_close'] = '</li>';
			    $config['prev_link'] = '&laquo;';
			    $config['prev_tag_open'] = '<li>';
			    $config['prev_tag_close'] = '</li>';
				
				/* Se inicializa la paginacion*/
				$this->pagination->initialize($config);
			
				/* Se obtienen los registros a mostrar*/ 
				$datos['listaComprobante'] = $this->tablaGenerica_model->get_registros('comprobantecabecera',$config['per_page'], $desde); 
			
				$datos['consultaComprobante'] ='';
				
				/*Se llama a la vista para mostrar la información*/
				$this->load->view('header');
				$this->load->view('contabilidad/verComprobantes', $datos);
				$this->load->view('footer');
			}//..fin IF contador registros mayor que cero ..
			
		}	//... fin IF validar usuario ...
		
	}	//...fin funcion: verComprobante ...
	
	
	public function ubicarComprobante(){
		//... buscar los registros que coincidan con el patron busqueda ingresado ...
	    $campo1='numComprobante';   //... el campo por elcual se va hacer la búsqueda ...
		
		if(isset($_POST['inputBuscarPatron'])){
			$consultaComprobante=$_POST['inputBuscarPatron'];
			
			// Escribimos una primera línea en consultaComprobante.txt
			$fp = fopen("pdfsArchivos/consultaComprobante.txt", "w");
			fputs($fp, $consultaComprobante);
			fclose($fp); 

		}else{
			// Leemos la primera línea de consultaComprobante.txt
			// fichero.txt es un archivo de texto normal creado con notepad, por ejemplo.
			$fp = fopen("pdfsArchivos/consultaComprobante.txt", "r");
			$consultaComprobante = fgets($fp);
			fclose($fp); 
		}	
		
		$this-> load -> model("tablaGenerica_model");
		
		$totalRegistrosEncontrados=0;		
		$totalRegistrosEncontrados=$this->tablaGenerica_model->getTotalRegistrosBuscar('comprobantecabecera',$campo1,$consultaComprobante);
		//echo"total registros econtrados".$totalRegistrosEncontrados;
		if($totalRegistrosEncontrados==0){
		//	$datos['mensaje']='No hay registros grabados en la tabla '.$nombreTabla;
		//	$this->load->view('mensaje',$datos );
		//	redirect('produccion/crudVerCotizaciones');
			redirect('menuController/index');
		}else{
			/* URL a la que se desea agregar la paginación*/
	    	$config['base_url'] = base_url().'contabilidad/ubicarComprobante';
			
			/*Obtiene el total de registros a paginar */
	    	$config['total_rows'] = $this->tablaGenerica_model->getTotalRegistrosBuscar('comprobantecabecera',$campo1,$consultaComprobante);
		
			/*Obtiene el numero de registros a mostrar por pagina */
	    	$config['per_page'] = '1';
			
			/*Indica que segmento de la URL tiene la paginación, por default es 3*/
	    	$config['uri_segment'] = '3';
			$desde = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
	  
			/*Se personaliza la paginación para que se adapte a bootstrap*/
		    $config['cur_tag_open'] = '<li class="active"><a href="#">';
		    $config['cur_tag_close'] = '</a></li>';
		    $config['num_tag_open'] = '<li>';
		    $config['num_tag_close'] = '</li>';
		    $config['last_link'] = FALSE;
		    $config['first_link'] = FALSE;
		    $config['next_link'] = '&raquo;';
		    $config['next_tag_open'] = '<li>';
		    $config['next_tag_close'] = '</li>';
		    $config['prev_link'] = '&laquo;';
		    $config['prev_tag_open'] = '<li>';
		    $config['prev_tag_close'] = '</li>';
			
			/* Se inicializa la paginacion*/
	    	$this->pagination->initialize($config);
	
			/* Se obtienen los registros a mostrar*/ 
			$datos['listaComprobante'] = $this-> tablaGenerica_model -> buscarPaginacion('comprobantecabecera',$campo1,$consultaComprobante, $config['per_page'], $desde );
			$datos['consultaComprobante'] =$consultaComprobante;
			
			/*Se llama a la vista para mostrar la información*/
			$this->load->view('header');
			$this->load->view('contabilidad/verComprobantes', $datos);
			$this->load->view('footer');
		}		//... fin IF total registros encintrados ...
		
	}		//... fin funcion: buscarComprobante ...
	
	
}

/* End of file contabilidad.php */
/* Location: ./application/controllers/contabilidad.php */