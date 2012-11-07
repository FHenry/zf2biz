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
            'galeries' => $this->_getGalerieInfoTable()->all(),
        )); 
    } 

    public function editAction() 
    { 
        // Récupération de l'objet de travail
        $id = $this->params()->fromRoute('id', null);
        $galerie = $this->_getGalerieTable()->any($id);

        // Récupération de l'objet requête
        $request = $this->getRequest();
        if ($request->isPost()) {
            // Validation des données

            if (true) {//TODO: Si les données sont valides
                // Sauvegarde des données
                // $this->_getGalerieTable()->save(?TODO?);

                // Redirection 
                return $this->redirect()->toRoute('galerie');
            }
        }
        return new ViewModel(array(
            'id' => $id,
            'galerie' => $galerie,
        ));
    } 

    public function delAction() 
    { 
        return $this->redirect()->toRoute('galerie/view', array(
            'id' => $id,
        ));
    } 

    public function viewAction() 
    {
        $id = $this->params()->fromRoute('id', null);
        $galerie = $this->_getGalerieInfoTable()->any($id);
        return new ViewModel(array(
            'id' => $id,
            'galerie' => $galerie,
        ));
    } 

} 
