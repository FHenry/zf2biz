<?php

namespace Galerie\Controller; 

use Zend\Mvc\Controller\AbstractActionController; 
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController 
{

    private $_galerieArrayTable;
    private $_galerieATable;

    private function _getGalerieArrayTable()
    {
        if (!$this->_galerieArrayTable) {
            $sm = $this->getServiceLocator();
            $this->_galerieArrayTable = $sm->get('Galerie\Model\GalerieArrayTable');
        }
        return $this->_galerieArrayTable;
    }

    private function _getGalerieATable()
    {
        if (!$this->_galerieATable) {
            $sm = $this->getServiceLocator();
            $this->_galerieATable = $sm->get('Galerie\Model\GalerieATable');
        }
        return $this->_galerieATable;
    }

    private function _getGalerieBTable()
    {
        if (!$this->_galerieBTable) {
            $sm = $this->getServiceLocator();
            $this->_galerieBTable = $sm->get('Galerie\Model\GalerieBTable');
        }
        return $this->_galerieBTable;
    }

    public function indexAction() 
    { 
        return new ViewModel(array(
            'GalerieArray_all' => $this->_getGalerieArrayTable()->fetchAll(),
            'GalerieArray_one' => $this->_getGalerieArrayTable()->getGalerie(1),
            'GalerieA_all' => $this->_getGalerieATable()->fetchAll(),
            'GalerieA_one' => $this->_getGalerieATable()->getGalerie(1),
            'GalerieB_all' => $this->_getGalerieBTable()->fetchAll(),
            'GalerieB_one' => $this->_getGalerieBTable()->getGalerie(1),
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
