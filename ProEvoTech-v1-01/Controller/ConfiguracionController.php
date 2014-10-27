<?php

class ConfiguracionController extends Zend_Controller_Action
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
    	$this->view->headLink()->appendStylesheet('/contador/multiselect/css/ui.multiselect.css');
    	$this->view->HeadScript()->appendFile('/contador/multiselect/js/ui.multiselect.js');
    	
    	$this->view->form = new Form_Configuracion();
    	$this->view->form->setAction('save');
    }
    
    public function saveAction()
    {
    	$form = new Form_Configuracion();
    	
    	$post = $this->_request->getPost();
    	
    	if(!$form->isValid($post) || $post['Usuario'] == 0){
    		$this->view->form = $form;
    		$this->view->form->setAction('save');
    		$this->_forward('index','configuracion');
    		return false;
    	}

    	$mapper_config = new Mapper_Configuracion();
    	
    	try {
    		$mapper_config->insert(new Entity_Configuracion($form->getValues()));
    	} catch (Exception $e){
    		$this->view->error = $e->getMessage();
    	}
    }
}