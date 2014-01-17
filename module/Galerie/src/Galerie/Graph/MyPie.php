<?php

/**
 * Core Application.
 *
 * PHP version 5.3
 *
 * @category  Core
 * @package   Core_View_Test
 * @author    Stéphane Ciaravolo <stephane.ciaravolo@finances.gouv.fr>
 * @author    Sébastien Chazallet <sebastien.chazallet@laposte.net>
 * @copyright 2011 DGFiP
 * @license   GNU GPL http://www.gnu.org/licenses/gpl.html
 * @link      http://core.noisiel.dgfip
 * @since     0.0.0.alpha1
 *
 */

namespace Galerie\Graph;

use Custom\Graph\Pie;
use Zend\I18n\Translator\TranslatorAwareInterface;
use Zend\I18n\Translator\Translator;

/**
 * Cette vue peut écrire dans une image pour générer un diagramme de Gantt.
 *
 * @category  Core
 * @package   Core_View_Test
 * @author    Stéphane Ciaravolo <stephane.ciaravolo@finances.gouv.fr>
 * @author    Sébastien Chazallet <sebastien.chazallet@laposte.net>
 * @copyright 2011 DGFiP
 * @license   GNU GPL http://www.gnu.org/licenses/gpl.html
 * @link      http://core.noisiel.dgfip
 * @since     0.0.0.alpha1
 *
 */
class MyPie extends Pie
{

    protected $title = "Nombre de photos par galerie";
    private $_translator = null;
    private $_translator_enabled = true;

    /**
     * On surcharge cette propriété afin d'avoir un graphique 3D
     * @var int
     */
    protected $dim3D = true;


    /**
     * On définit le fait que certaines données doivent être mises en évidence.
     *
     * @see Coresocle_View_Statgraph_Default::getSlices
     *
     * @return array
     */
    public function getSlices()
    {
        return array(0);
    }

    /**
     * On définit un nouveau format pour rajouter un mot après le chiffre
     *
     * @see Coresocle_View_Statgraph_Default::getFormat
     *
     * @return array
     */
    public function getFormat()
    {
        return '%d ' . $this->_translator->translate('nb_photo', 'galerie');
    }
    
    public function setTranslator(Translator $translator = null, $textDomain = null)
    {
        if ($translator) {
            $this->_translator = $translator;
            $this->_translator_enabled = true;
        }
        if ($textDomain) {
            $this->_textDomain = $textDomain;
        }
    }

}
