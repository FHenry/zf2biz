<?php

namespace Galerie\Controller; 


use Zend\Mvc\Controller\AbstractActionController; 
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use Zend\View\Renderer\PhpRenderer;

use Zend\Session\Container;

use Zend\Feed\Writer\FeedFactory;
use Zend\Feed\Reader\Reader as FeedReader;

use Galerie\Model\Galerie;
use Galerie\Model\Photo;

class IndexController extends AbstractActionController 
{

    private $_galerieTable;
    private $_photoTable;
    private $_galerieInfoTable;
    private $_galerieForm;
    private $_galerieInfoExporter;
    private $_galerieMailSender;
    private $_viewResolver;
    private $_galeriePairTable;
    private $_myPie;

    private $_translator;
    private $_log;
    private $_rss;


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

    private function _getGalerieInfoRssTable()
    {
        if (!$this->_galerieInfoTable) {
            $sm = $this->getServiceLocator();
            $this->_galerieInfoTable = $sm->get('Galerie\Model\GalerieInfoRssTable');
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
    
    private function _getPhotoTable()
    {
        if (!$this->_photoTable) {
            $sm = $this->getServiceLocator();
            $this->_photoTable = $sm->get('Galerie\Model\PhotoTable');
        }
        return $this->_photoTable;
    }

    private function _getGalerieInfoExporter()
    {
        if (!$this->_galerieInfoExporter) {
            $sm = $this->getServiceLocator();
            $this->_galerieInfoExporter = $sm->get('Galerie\Export\GalerieWorkbook');
        }
        return $this->_galerieInfoExporter;
    }

    private function _getGalerieMailSender()
    {
        if (!$this->_galerieMailSender) {
            $sm = $this->getServiceLocator();
            $this->_galerieMailSender = $sm->get('Galerie\Mail\MailSender');
        }
        return $this->_galerieMailSender;
    }
    
    private function _getViewResolver()
    {
        if (!$this->_viewResolver) {
            $sm = $this->getServiceLocator();
            $this->_viewResolver = $sm->get('ViewResolver');
        }
        return $this->_viewResolver;
    }
    
    private function _getMyPie()
    {
        if (!$this->_myPie) {
            $sm = $this->getServiceLocator();
            $this->_myPie = $sm->get('Galerie\Graph\MyPie');
        }
        return $this->_myPie;
    }

    private function _getGaleriePairTable()
    {
        if (!$this->_galeriePairTable) {
            $sm = $this->getServiceLocator();
            $this->_galeriePairTable = $sm->get('Galerie\Model\GaleriePairTable');
        }
        return $this->_galeriePairTable;
    }

    private function _getLog()
    {
        if (!$this->_log) {
            $sm = $this->getServiceLocator();
            $this->_log = $sm->get('Zend\Log');
        }
        return $this->_log;
    }

    private function _getRss()
    {
        if (!$this->_rss) {
            $sm = $this->getServiceLocator();
            $config = $sm->get('Config');
            $this->_rss = FeedFactory::factory($config['rss']);
        }
        return $this->_rss;
    }




    public function indexAction() 
    {
        $this->_getLog()->info('Acces à la liste des galeries');
        $session = new Container('lastview');
        if ($session->offsetExists('last')) {
            $last = $session->offsetGet('last');
        } else {
            $last = null;
        }
        
        $messenger = $this->flashMessenger();
        $messenger->setNamespace('infos');
        $messenger->addMessage('Ceci est un test');
        
        $varView  = array_merge($this->MessageGetter(), array('last' => $last));
        
        return new ViewModel($varView);
    }

    public function rssAction() {
        // Récupération des informations brutes
        $modelManager = $this->_getGalerieInfoRssTable();
        $datas = $modelManager->all();

        // Création du fil RSS
        $rss = $this->_getRss();
        
        foreach($datas as $d) {
            $entry = $rss->createEntry();
            $entry->setTitle($d->name);
            
            $entry->setLink($this->url()->fromRoute(
                'galerie/view',
                array('id' => $d->id)),
                array('force_canonical' => true)
            );

            $entry->addAuthor(array(
                'name'  => $d->username,
            ));
            $date = new \DateTime();
            $entry->setDateModified(
                $date->setTimestamp(intval($d->updated))
            );
            $entry->setDateCreated(
                $date->setTimestamp(intval($d->created))
            );
            $entry->setDescription($d->description);
            $entry->setContent("{$d->nb} photos.");
            $rss->addEntry($entry);
        }

        //echo '<pre>'; print_r($rss->export('rss')); die('</pre>');

        // Création de la réponse
        $response = $this->getResponse();
        $response->setStatusCode(200);

        // Modification des entêtes
        $headers = $this->getResponse()->getHeaders();
        $headers->addHeaderLine('Content-Type', 'application/rss+xml; charset=utf-8');

        $response->setContent($rss->export('rss'));

        return $response;
    }

    public function csvAction() {
        // Récupération des informations brutes
        $modelManager = $this->_getGalerieInfoTable();
        $datas = $modelManager->all();

        // Mise en forme des résultats
        $content = array($modelManager->csvHeader());
        foreach($datas as $d) {
            $content[] = $d->csvFormat();
        }

        // Création de la réponse
        $response = $this->getResponse();
        $response->setStatusCode(200);

        // Modification des entêtes
        $headers = $this->getResponse()->getHeaders();
        $headers->addHeaderLine('Content-Type', 'text/csv; charset=utf-8');
        $headers->addHeaderLine('Content-Disposition', 'attachment; filename="export_galerie.csv"');

        $response->setContent(implode("\r\n", $content));

        return $response;
    }
    
    public function rsscheckAction()
    {
        $url = $this->url()->fromRoute('galerie/rss', array(), array('force_canonical' => true));
        $channel = FeedReader::import($url);
        $title = $channel->getTitle();
        $author = $channel->getAuthor();
        $username = $author['name'];
        $galeries = array();
        foreach ($channel as $item) {
            $galeries[] = "{$item->getTitle()} : {$item->getDescription()}";
        }

        return new ViewModel(array(
            'title' => $title,
            'username' => $username,
            'galeries' => $galeries,
        ));
    }


    public function excelAction() {
        // Récupération des informations brutes
        $modelManager = $this->_getGalerieInfoTable();
        $datas = $modelManager->all();

        $exporter = $this->_getGalerieInfoExporter();
        $exporter->build($datas);

        // Renvoi d'une réponse vide pour désactiver le rendu de la vue
        return $this->getResponse();
    }
    
    public function photoAction() {
        $id_gallery = $this->params()->fromRoute('idgallery', null);
        $filename = $this->params()->fromRoute('filename', null);
        $extension = $this->params()->fromRoute('ext', null);
        
        // Récupération du contenu de l'image
        $imageContent = file_get_contents("data/images/{$id_gallery}/{$filename}.{$extension}");
            
        // get image content
        $response = $this->getResponse();
    
        $response->setContent($imageContent);
        $response
            ->getHeaders()
            ->addHeaderLine('Content-Transfer-Encoding', 'binary')
            ->addHeaderLine('Content-Type', 'image/png')
            ->addHeaderLine('Content-Length', mb_strlen($imageContent));
    
        return $response;
    }
    


    public function mailAction()
    {
        
        //Construction du courriel au format HTML.
        $mail_viewmodel = new ViewModel(array(
            'who' => 'World',
        ));
        $mail_viewmodel->setTemplate('galerie/mail/test');
        $renderer = new PhpRenderer;
        $renderer->setResolver($this->_getViewResolver());
        $body_html = $renderer->render($mail_viewmodel);

        // Envoi du courriel
        $mailSender = $this->_getGalerieMailSender();
        $mailSender->send(
            'formation@tipunk.fr', 'Moi',//'sender@example.com', 'Moi',
            'formation@tipunk.fr', 'Toi',//'to@example.com', 'Toi',
            'Test', 'Hello World 4.', $body_html ,'toto.png'
        );

        // Création de la réponse
        $response = $this->getResponse();
        $response->setStatusCode(200);
        $response->setContent('Mail Sent.');

        return $response;
    }

    public function pieAction()
    {
        // Accès aux modèles
        $modelManager = $this->_getGalerieInfoTable();
        $datas = $modelManager->all();

        // Mise en forme des résultats
        $nombres = array();
        $noms = array();
        foreach($datas as $d) {
            $nombres[] = $d->nb;
            $noms[] = $d->name;
        }

        
        // Construction (et envoi) du diagramme
        $pie = $this->_getMyPie();
        $pie->render($nombres,$noms);
        
        $response = $this->getResponse();
        $response->setStatusCode(200);
        $response->setContent('');

        return $response;
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
        $modelManager->logger=$this->_getLog();

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
                "<strong>{$g->name}</strong><em>{$g->description}</em>",
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
            if (!$galerie) {
                return $this->redirect()->toRoute('galerie');
            } else {
                $photos = $this->_getPhotoTable()->all_by_gallery($id);
            }
        }

        // Sommes-nous en ajout ou en édition ?
        if (!$galerie) {
            // Nous sommes en ajout
            $form->get('submit')->setValue('Ajouter');
            // Il faut créer un nouveau objet Galerie
            $galerie = new Galerie;
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

        if (!$photos && !$is_new) {
            $translator = $this->_getTranslator();
            $messenger = $this->flashMessenger();
            $messenger->setNamespace('infos');
            $messenger->addMessage($translator->translate('Photo_info_no_photo', 'galerie'));
        }

        // Récupération de l'objet requête
        $request = $this->getRequest();
        if ($request->isPost()) {
            // Mise en place pour la validation du formulaire
            $form->setInputFilter($galerie->getInputFilter());
            
            $post = array_merge_recursive(
            	$request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            $form->setData($post);
            
            // Validation des données
            if ($form->isValid()) {
               
                // Sauvegarde des données
                if ($is_new) {
                	$galerie->exchangeArray($form->getData());
                    // Si l'objet n'est pas nouveau, les autres paramètres restent inchangés
                    // Si l'objet est nouveau, il faut renseigner l'id de l'utilisateur courant
                    $galerie->id_user = 1; //TODO: Mettre ici le user connecté
                } else {
                	$galerie = $form->getData();
                	$fileArr = $this->params()->fromFiles('fileUpload');
                	
                	
                	$adapter = new \Zend\File\Transfer\Adapter\Http();
                    $size = new \Zend\Validator\File\Size(array('min' => 1 )); // minimum bytes filesize, max too..
                	$extension = new \Zend\Validator\File\Extension(array('extension' => array('jpg', 'png')));
                	$adapter->setValidators(array($size, $extension), $fileArr[0]['name']);
                	/**
                	 * Valid Upload
                	*/
                	if($adapter->isValid()) {
                	    $adapter->setDestination(realpath('./data/images/'.$galerie->id.'/'));
                	    if($adapter->receive($fileArr[0]['name'])) {
                	        $filename = $adapter->getFileName();
                	        $photo = new Photo();
                	        $photo->filename=reset(explode('.', $fileArr[0]['name']));
                	        $photo->id_gallery=$galerie->id;
                	        $photo->name=$fileArr[0]['name'];
                	        $photo->description=$fileArr[0]['name'];
                	        $photo->extension=end(explode('.', $fileArr[0]['name']));
                	        $this->_getPhotoTable()->save($photo);
                	    }
                	}
                	
                }
                $this->_getGalerieTable()->save($galerie);               
                $translator = $this->_getTranslator();
                $messenger = $this->flashMessenger();
                $messenger->setNamespace('infos');
                if ($is_new) {
                    $messenger->addMessage($translator->translate('Galerie_added', 'galerie'));
                } else {
                    $messenger->addMessage($translator->translate('Galerie_updated', 'galerie'));
                }
         
                // Redirection à l'ancienne mode
                //return $this->redirect()->toRoute('galerie');
                // Redirection vers le contrôleur courant
                //return $this->postRedirectGet();
                // Redirection vers la page Galerie/Index
                return $this->postRedirectGet('galerie');
            }else {
                $translator = $this->_getTranslator();
                $messenger = $this->flashMessenger();
                $messenger->setNamespace('errors');
                $messenger->addMessage($translator->translate('Galerie_form_error', 'galerie'));
            }
        }

        // On prépare l'affichage du formulaire
        if ($is_new) {
            $form->setAttribute('action', $this->url()->fromRoute('galerie/add'));
        } else {
            $form->setAttribute('action', $this->url()->fromRoute('galerie/edit', array('id' => $id)));
        }
        $form->prepare();
        
        $session = new Container('lastview');
        $session->offsetSet('last', $galerie->id);

        $varView  = array_merge($this->MessageGetter(), array(
            'id' => $id,
            'form' => $form,
            'is_new' => $is_new,
            'photos' => $photos,
        ));
        
        
        // On passe la main à la vue
        return new ViewModel($varView);
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
        $photos = $this->_getPhotoTable()->all_by_gallery($id);
        if (!$galerie) {
            return $this->redirect()->toRoute('galerie');
        }
        if (!$photos) {
                $translator = $this->_getTranslator();
                $messenger = $this->flashMessenger();
                $messenger->setNamespace('infos');
                $messenger->addMessage($translator->translate('Photo_info_no_photo', 'galerie'));
        } else {
            // Aff
        }

        $pairs = $this->_getGaleriePairTable()->all();
        unset($pairs[$id]); // Pour ne pas afficher la galeire courante dans le nav

        $session = new Container('lastview');
        $session->offsetSet('last', $id);

        return new ViewModel(array_merge($this->MessageGetter(), array(
            'id' => $id,
            'galerie' => $galerie,
            'pairs' => $pairs,
            'photos' => $photos,
        )));
    } 

} 
