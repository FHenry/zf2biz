<?php

namespace Galerie\Controller; 

use Zend\Mvc\Controller\AbstractActionController; 
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController 
{

    private $_galerieArrayTable;
    private $_galerieATable;
    private $_galerieBTable;
    private $_galerieCTable;
    private $_galerieCTable2;


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

    private function _getGalerieCTable()
    {
        if (!$this->_galerieCTable) {
            $sm = $this->getServiceLocator();
            $this->_galerieCTable = $sm->get('Galerie\Model\GalerieCTable');
        }
        return $this->_galerieCTable;
    }

    private function _getGalerieCTable2()
    {
        if (!$this->_galerieCTable2) {
            $sm = $this->getServiceLocator();
            $this->_galerieCTable2 = $sm->get('Galerie\Model\GalerieCTable2');
        }
        return $this->_galerieCTable2;
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
            'GalerieC_all' => $this->_getGalerieCTable()->fetchAll(),
            'GalerieC_one' => $this->_getGalerieCTable()->getGalerie(1),
            'GalerieC2_all' => $this->_getGalerieCTable2()->fetchAll(),
            'GalerieC2_one' => $this->_getGalerieCTable2()->getGalerie(1),
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
