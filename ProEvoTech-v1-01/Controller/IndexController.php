<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        if ($this->_request->isXmlHttpRequest()) {
		    $this->_helper->viewRenderer->setNoRender(true);
		    $this->_helper->layout->disableLayout();
		}
		
    	$usuario = new Zend_Session_Namespace('usuario');
    	
        if (!isset($usuario->loggingIn)) {
        	$this->_redirect('usuario/usuario');
        }
    }
    
	public function indexAction()
    {
    	$mapper_contador = new Mapper_Contador();
    	$idUsuario = $this->_request->getParam('usuario');
    	$refresh = $this->_request->getParam('refresh');
    	
    	if($refresh == ''){
    		$refresh = 10;
    	}
    	
    	$this->view->refresh = $refresh;
    	
    	if($idUsuario == ''){
    		$this->_redirect('usuario/usuario');
    	}
    	
    	$mapper_usuario = new Mapper_Usuario();
		
		$usuario = new Zend_Session_Namespace('usuario');
		$usuario->data = $mapper_usuario->fetch($idUsuario);
		
    	if($usuario->data->Rol == Bootstrap::SUPER_USER){
    		//obtengo el contador para el Super Usuario
    		$contadores = $mapper_contador->fetchAll();
    		
    		if($contadores != ''){
    			$this->view->contadores = $contadores;
    		} else {
    			$this->_forward('fuera-turno','index');
    			return false;
    		}
    	} else {
    		//Obtengo la configuracion para el usuario seleccionado
    		$mapper_config = new Mapper_Configuracion();
    		$contadores = $mapper_config->fetchByUserId($usuario->data->Id);
    		
    		if(!empty($contadores)){
    			//obtengo el contador de acuerdo a la configuracion del usuario
    			$contadoresArray = array();
    			foreach ($contadores as $contador){
    				$contadoresArray[] = $mapper_contador->fetch($contador->Contador);
    			}
    			$this->view->contadores = $contadoresArray;
    		} else {
    			$this->_forward('fuera-turno','index');
    			return false;
    		}
    	}
    	
    	$this->view->usuario = $usuario->data;
    }
    
    public function getJsonAction()
    {
		$idContador = $this->_request->getParam('contador');
    	
    	$mapper = new Mapper_Contador();
    	$contador = $mapper->getContadorById($idContador);
    	
    	//$dataTable = $mapper->getProduccionActual(date('Ymd 00:00:00'), date('Ymd 00:00:00'), $contador->IdEtapa, $contador->BaseDatos);
    	//$produccionActual = $mapper->getProduccionAcumulada(date('Ymd 00:00:00'), date('Ymd 00:00:00'), $contador->IdEtapa, $contador->BaseDatos);
    	
    	$turnoDesde = date('Ymd').' '.date('G:i:s',$contador->TurnoDesde->getTimestamp());
    	$turnoHasta = date('Ymd').' '.date('G:i:s',$contador->TurnoHasta->getTimestamp());

    	$dataTable = $mapper->getProduccionActual($turnoDesde, $turnoHasta, $contador->IdEtapa, $contador->BaseDatos);
    	$produccionActual = $mapper->getProduccionAcumulada($turnoDesde, $turnoHasta, $contador->IdEtapa, $contador->BaseDatos);
    	
    	$produccionActual = $produccionActual * $contador->Multiplicador;
    	
    	$nombreLinea = $contador->Nombre;
    		
    	$json = '';
    	for($i = 0; $i < count($dataTable); $i++){
    		$fila = $dataTable[$i];
    		$cantidad = 0;

    		if($fila['cantidad']){
    			$cantidad = $fila['cantidad'] * $contador->Multiplicador;
    		}
    		
    		$json .= '{"f": "' . date('G:i',$fila['fecha']->getTimestamp()) . '", "c": '. $cantidad .'}';
    		
    		if ($i < count($dataTable)- 1){
				$json .= ', ';
			}
    	}

    	$rangos = $mapper->getRangosByContador($idContador);

	   	require_once 'DateTimeInterval.php';
    	$intervalos = Array();

		foreach ($rangos as $rango){
			$horaDesde = date('G:i', $rango['HoraDesde']->getTimestamp());
    		$horaHasta = date('G:i', $rango['HoraHasta']->getTimestamp());
    		
			$hoy = date('Y-m-d').' '.$horaDesde.':00';
			$hoy1 = date('Y-m-d').' '.$horaHasta.':00';
			
			$intervalos[] = new DateTimeInterval($hoy, $hoy1);
		}
		$produccionDiaria = $mapper->getProduccionDiaria($contador->Id);

		$objetivo = $mapper->getProduccionIdealJson($intervalos, date('Y-m-d G:i:s'), 'MINUTE', $mapper->obtenerPPM($intervalos, $produccionDiaria)); 

		echo '{"nombre": "'. $nombreLinea .'", "fecha": "'. date('d/m/Y').'", "produccion": '. $produccionActual .', "datos": ['. $json .'], '. $objetivo .', "objetivoFinal": '. $contador->Objetivo .'}';
    }

	public function fueraTurnoAction()
	{
		$usuario = new Zend_Session_Namespace('usuario');
		
		//refresh cada 10min para ver si comenzo el turno
		$this->view->headMeta()->appendHttpEquiv('Refresh','600;URL='.$this->view->baseUrl('index/index').'?usuario='.$usuario->data->Id);
		$this->view->error = 'No hay contadores definidos para este turno ('.date('G:i:s').')';
	}
}