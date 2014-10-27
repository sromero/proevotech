<?php

class AdministradorController extends Zend_Controller_Action
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
    }
}