<?php

namespace Galerie\Controller; 

use Zend\Mvc\Controller\AbstractActionController; 
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use Galerie\Model\Galerie;

class IndexController extends AbstractActionController 
{

    private $_galerieTable;
    private $_galerieInfoTable;
    private $_galerieForm;

    private $_translator;


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

    private function _getTranslator()
    {
        if (!$this->_translator) {
            $sm = $this->getServiceLocator();
            $this->_translator = $sm->get('translator');
        }
        return $this->_translator;
    }

    private function _getGalerieForm()
    {
        if (!$this->_galerieForm) {
            $sm = $this->getServiceLocator();
            $this->_galerieForm = $sm->get('Galerie\Form\GalerieForm');
        }
        return $this->_galerieForm;
    }



    public function indexAction() 
    { 
        return new ViewModel(array()); 
    } 

    public function listAction() 
    { 
        // Récupération de l'objet requête
        $request = $this->getRequest();

        $start = $request->getQuery('iDisplayStart', 0);
        $length = $request->getQuery('iDisplayLength', 10);
        $tri = $request->getQuery('iSortCol_0');
        $senstri = $request->getQuery('sSortDir_0', 'asc');
        $filtre = $request->getQuery('sSearch');

        // Preparation pour le requêtage
        $modelManager = $this->_getGalerieInfoTable();

        // Récupération des galeries sous la forme d'un tableau d'entités
        $galeries = $modelManager->getPartial($start, $length, $tri, $senstri, $filtre);

        // Préparation pour la mise en forme du résultat
        $action_template = '<a href="%s">%s</a><a href="%s">%s</a><a href="%s">%s</a>';
        $translator = $this->_getTranslator();
        $action_voir = $translator->translate('Galerie_index_table_lien_view', 'galerie');
        $action_editer = $translator->translate('Galerie_index_table_lien_edit', 'galerie');
        $action_supprimer = $translator->translate('Galerie_index_table_lien_del', 'galerie');
        $url = $this->url();
        // Mise en forme du résultat pour que cela corresponde à l'attendu, c'est à dire un tableau de tableaux
        $result = array();
        foreach($galeries as $g) {
            $result[] = array(
                "<strong>{$g->name}</strong>",
                "<em>{$g->description}</em>",
                $g->username,
                $g->nb,
                sprintf(
                    $action_template,
                    $url->fromRoute('galerie/view', array('id' => $g->id)),
                    $action_voir,
                    $url->fromRoute('galerie/edit', array('id' => $g->id)),
                    $action_editer,
                    $url->fromRoute('galerie/del', array('id' => $g->id)),
                    $action_supprimer
                ),
            );
        }

	// Construction du resultat
        return new JsonModel(array(
            "sEcho" => $request->getQuery('sEcho', 1),
            "iTotalRecords" => $modelManager->count_all(),
            "iTotalDisplayRecords" => count($result),
            "aaData" => $result,
        ));
    } 

    public function editAction() 
    {
        // Création du formulaire
        $form = $this->_getGalerieForm();
		
        // Récupération de l'objet de travail
        $id = $this->params()->fromRoute('id', null);
        if (!$id) {
            $galerie = null;
        } else {
            $galerie = $this->_getGalerieTable()->any(array('id' => $id));
        }

        // Sommes-nous en ajout ou en édition ?
        if (!$galerie) {
            // Nous sommes en ajout
            $form->get('submit')->setValue('Ajouter');
            // Il faut créer un nouveau objet Galerie
            $galerie = new Galerie();
            // Garder cette information pour la vue
            $is_new = true;
          
        } else {
            // Nous sommes en modification
            $form->get('submit')->setValue('Modifier');
            // Il faut préremplir le formulaire avec les données actuelles
            $form->bind($galerie);
            // Garder cette information pour la vue
            $is_new = false;
        }

        // Récupération de l'objet requête
        $request = $this->getRequest();
        if ($request->isPost()) {
            // Mise en place pour la validation du formulaire
            $form->setInputFilter($galerie->getInputFilter());
            $form->setData($request->getPost());
           
            // Validation des données
            if ($form->isValid()) {
                // Sauvegarde des données
                if ($is_new) {
                	$galerie->exchangeArray($form->getData());
                    // Si l'objet n'est pas nouveau, les autres paramètres restent inchangés
                    // Si l'objet est nouveau, il faut renseigner l'id de l'utilisateur courant
                    $galerie->id_user = 1; //TODO: Mettre ici le user connecté
                }else {
                	$galerie = $form->getData();
                }
               
                $this->_getGalerieTable()->save($galerie);

                // Redirection 
                return $this->redirect()->toRoute('galerie');
            }
        }

        // On prépare l'affichage du formulaire
        if ($is_new) {
            $form->setAttribute('action', $this->url()->fromRoute('galerie/add'));
        } else {
            $form->setAttribute('action', $this->url()->fromRoute('galerie/edit', array('id' => $id)));
        }
        $form->prepare();

        // On passe la main à la vue
        return new ViewModel(array(
            'id' => $id,
            'form' => $form,
            'is_new' => $is_new,
        ));
    } 

    public function delAction() 
    { 
        return $this->redirect()->toRoute('galerie/view', array(
            'id' => $this->params()->fromRoute('id', null),
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
