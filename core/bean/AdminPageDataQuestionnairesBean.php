<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdminPageDataQuestionnairesBean
 * @author Hugues
 * @version 1.21.07.21
 * @since 1.21.07.21
 */
class AdminPageDataQuestionnairesBean extends AdminPageBean
{
  protected $urlTemplateBoardAdmin = 'web/pages/admin/board-data-questionnaires.php';

  /**
   * Class Constructor
   */
  public function __construct($urlParams=null)
  {
    parent::__construct();
    $this->Services = new DataQuestionnaireServices();
    // Initialisation des Données si un Objet est sélectionné
    $this->LocalObject = ($urlParams!=null && isset($urlParams[self::FIELD_ID]) ? $this->Services->selectLocal($urlParams[self::FIELD_ID]) : new DataQuestionnaire());
    // On stocke les paramètres
    $this->urlParams = $urlParams;
    // On prépare le stockage pour les ids multiples si existants.
    $this->arrIds = array();
    $this->subMenuValue = self::PAGE_DATA_QUESTIONS;
    $this->ConfigQuestionnaireServices = new QuestionnaireServices();
  }
  /**
   * Retourne le DataQuestionnaire
   * @return DataQuestionnaire
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public function getObject()
  { return $this->LocalObject; }
  /**
   * Retourne le Service
   * @return DataQuestionnaireService
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public function getServices()
  { return $this->Services; }

  /**
   * @param array $urlParams
   * @return string
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public static function getStaticContentPage($urlParams)
  {
    ///////////////////////////////////////////:
    // Initialisation des valeurs par défaut
    $Bean = new AdminPageDataQuestionnairesBean($urlParams);
    return $Bean->getContentPage();
  }
  /**
   * @param array $urlParams
   * @return string
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public function getContentPage()
  {
    ///////////////////////////////////////////
    // Initialisation des valeurs par défaut
    $msg = '';
    $initPanel = self::CST_CREATE;

    ///////////////////////////////////////////
    // Analyse de l'action éventuelle.
    if (isset($this->urlParams[self::CST_POSTACTION])) {
      $this->parseUrlParams($initPanel, $notif, $msg);
    }

    ///////////////////////////////////////////
    // Si $msg est renseigné, on a une notification à afficher.
    if ($msg!='') {
      $this->createNotification($notif, $msg);
    }

    ///////////////////////////////////////////
    // On initialise les panneaux latéraux droit
    $this->msgConfirmDelete = sprintf(self::MSG_CONFIRM_SUPPR_DATA_QUESTION, $this->LocalObject->getFullName());
    $this->tagConfirmDeleteMultiple = self::MSG_CONFIRM_SUPPR_DATA_QUESTIONS;

    // Pas de création ou d'édition pour ce type d'objet.

    $this->initPanels($initPanel);

    ///////////////////////////////////////////:
    // On retourne le listing et les panneaux latéraux droit
    return $this->getListingPage();
  }

  /**
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public function setLocalObject()
  {
    $this->LocalObject->setData($this->urlParams[self::FIELD_DATA]);
  }
  /**
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public function initLocalObject()
  { $this->LocalObject = new DataQuestionnaire(); }

  /**
   * Gestion de l'affichage de la page.
   * @return string
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public function getListingPage()
  {
    /////////////////////////////////////////////////////////////////////////////
    // On récupère tous les Questionnaires puis on concatène les rows.
    $strRows = '';
    $DataQuestionnaires = $this->Services->getDataQuestionnairesWithFilters();
    foreach ($DataQuestionnaires as $DataQuestionnaire) {
      $Bean = $DataQuestionnaire->getBean();
      $strRows .= $Bean->getRowForAdminPage(in_array($DataQuestionnaire->getId(), $this->arrIds));
    }

    $strHeader = '';
    $strFooter = '';

    $strHeader .= '<th scope="col" id="" class="manage-column">Division</th>';
    $strFooter .= '<th scope="col" class="manage-column">Division</th>';
    $strHeader .= '<th scope="col" id="" class="manage-column">Elève</th>';
    $strFooter .= '<th scope="col" class="manage-column">Elève</th>';
    $strHeader .= '<th scope="col" id="" class="manage-column">Parent</th>';
    $strFooter .= '<th scope="col" class="manage-column">Parent</th>';
    /*
    $ConfigQuestionnaires = $this->ConfigQuestionnaireServices->getQuestionnairesWithFilters(array(), self::FIELD_DISPLAY_ORDER);
    foreach ($ConfigQuestionnaires as $ConfigQuestionnaire) {
      $strHeader .= '<th scope="col" id="'.$ConfigQuestionnaire->getConfigKey().'" class="manage-column">'.$ConfigQuestionnaire->getConfigValue().'</th>';
      $strFooter .= '<th scope="col" class="manage-column">'.$ConfigQuestionnaire->getConfigValue().'</th>';
    }
    */

    /////////////////////////////////////////////////////////////////////////////
    // On restitue le template enrichi.
    $attributes = array(
      // Liste des matières affichées - 1
      $strRows,
      // Header de la table - 2
      $strHeader,
      // Footer de la table - 3
      $strFooter,
      // Notification suite à soumission formulaire - 2
      $this->notifications,
      // Card Importation - 3
      $this->getCardImport(self::PAGE_DATA_QUESTIONS),
    );
    return $this->getRender($this->urlTemplateBoardAdmin, $attributes);
  }

}
