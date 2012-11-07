<?php

namespace Galerie\Controller; 

use Zend\Mvc\Controller\AbstractActionController; 
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController 
{

    private $_galerieTable;
    private $_galerieInfoTable;


    private function _getGalerieTable()
    {
        if (!$this->_galerieTable) {
            $sm = $this->getServiceLocator();
            $this->_galerieTable = $sm->get('Galerie\Model\GalerieTable');
        }
        return $this->_galerieTable;
    }

    private function _getGalerieInfoTable()
    {
        if (!$this->_galerieInfoTable) {
            $sm = $this->getServiceLocator();
            $this->_galerieInfoTable = $sm->get('Galerie\Model\GalerieInfoTable');
        }
        return $this->_galerieInfoTable;
    }


    public function indexAction() 
    { 
        return new ViewModel(array(
            'all' => $this->_getGalerieInfoTable()->all(),
        )); 
    } 

    public function editAction() 
    { 
        return array(); 
    } 

    public function delAction() 
    { 
        return array(); 
    } 

    public function viewAction() 
    { 
        return array(); 
    } 
} 
