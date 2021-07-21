<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdminPageQuestionnairesBean
 * @author Hugues
 * @version 1.21.06.21
 * @since 1.21.06.09
 */
class AdminPageQuestionnairesBean extends AdminPageBean
{
  protected $urlTemplatePageQuestionnaireAdmin = 'web/pages/admin/board-questionnaires.php';
  protected $urlTemplateForm = 'web/pages/admin/fragments/form-questionnaire.php';

  /**
   * Class Constructor
   */
  public function __construct($urlParams=null)
  {
    parent::__construct();
    $this->QuestionnaireServices = new QuestionnaireServices();
    $this->Services = new QuestionnaireServices();
    // Initialisation de la Division sélectionnée s'il y en a une.
    if ($urlParams!=null && isset($urlParams[self::FIELD_CONFIG_KEY])) {
      $Questionnaires = $this->QuestionnaireServices->getQuestionnairesWithFilters(array(self::FIELD_CONFIG_KEY=>$urlParams[self::FIELD_CONFIG_KEY]));
      if (empty($Questionnaires)) {
        $this->LocalObject = new Questionnaire();
      } else {
        $this->LocalObject = array_shift($Questionnaires);
      }
    } else {
      $this->LocalObject = new Questionnaire();
    }
    // On stocke les paramètres
    $this->urlParams = $urlParams;
    // On prépare le stockage pour les ids multiples si existants.
    $this->arrIds = array();
    $this->subMenuValue = self::PAGE_QUESTIONNAIRE;
  }
  /**
   * Retourne le Questionnaire
   * @return Division
   * @version 1.21.07.21
   * @since 1.21.06.09
   */
  public function getObject()
  { return $this->LocalObject; }
  /**
   * Retourne le Service
   * @return QuestionnaireService
   * @version 1.21.07.21
   * @since 1.21.06.09
   */
  public function getServices()
  { return $this->Services; }

  /**
   * @param array $urlParams
   * @return string
   * @version 1.21.06.09
   * @since 1.21.06.09
   */
  public static function getStaticContentPage($urlParams)
  {
    ///////////////////////////////////////////:
    // Initialisation des valeurs par défaut
    $Bean = new AdminPageQuestionnairesBean($urlParams);
    return $Bean->getContentPage();
  }
  /**
   * @param array $urlParams
   * @return string
   * @version 1.21.06.09
   * @since 1.21.06.09
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
    $this->msgConfirmDelete = sprintf(self::MSG_CONFIRM_SUPPR_QUESTIONNAIRE, $this->LocalObject->getFullName());
    $this->tagConfirmDeleteMultiple = self::MSG_CONFIRM_SUPPR_QUESTIONNAIRES;

    $this->attributesFormNew = array('', '');
    $this->attributesFormEdit = array(
      // ConfigKey de l'objet sélectionné - 1
      $this->LocalObject->getConfigKey(),
      // ConfigValue de l'objet sélectionné - 2
      $this->LocalObject->getConfigValue(),
    );

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
    $this->LocalObject->setConfigKey($this->urlParams[self::FIELD_CONFIG_KEY]);
    $this->LocalObject->setConfigValue(stripslashes($this->urlParams[self::FIELD_CONFIG_VALUE]));
  }
  /**
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public function initLocalObject()
  { $this->LocalObject = new Questionnaire(); }

  /**
   * Gestion de l'affichage de la page.
   * @return string
   * @version 1.21.06.06
   * @since 1.21.06.01
   */
  public function getListingPage()
  {
    /////////////////////////////////////////////////////////////////////////////
    // On récupère tous les Questionnaires puis on concatène les rows.
    $strRows = '';
    $Questionnaires = $this->QuestionnaireServices->getQuestionnairesWithFilters();
    foreach ($Questionnaires as $Questionnaire) {
      $Bean = $Questionnaire->getBean();
      $strRows .= $Bean->getRowForAdminPage(in_array($Questionnaire->getConfigKey(), $this->arrIds));
    }

    /////////////////////////////////////////////////////////////////////////////
    // On restitue le template enrichi.
    $attributes = array(
      // Liste des matières affichées - 1
      $strRows,
      // Notification suite à soumission formulaire - 2
      $this->notifications,
      // Card C(R)UD - 3
      $this->getCardCRUD($this->crudType, $this->attributesCardCRUD),
      // Card Importation - 4
      $this->getCardImport(self::PAGE_QUESTIONNAIRE),
    );
    return $this->getRender($this->urlTemplatePageQuestionnaireAdmin, $attributes);
  }

  /**
   * Intialise les panneaux latéraux à afficher
   * @param string $action
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public function initPanels($action)
  {
    if ($action==self::CST_BULK_TRASH) {
      $this->crudType = self::CST_DELETE;
      // Construction des listings suite à la sélection multiple.
      $arrIds = array();
      $arrLabels = array();
      foreach($this->urlParams[self::CST_POST] as $key=> $value) {
        $Objs = $this->Services->getQuestionnairesWithFilters(array(self::FIELD_CONFIG_KEY=>$value));
        $Obj = array_shift($Objs);
        $arrLabels[] = $Obj->getFullName();
        $arrIds[] = $value;
      }
      $this->arrIds                   = $arrIds;
      // Définition des attributs de la Card CRUD
      $this->attributesCardCRUD = array(
        // Message de confirmation à afficher - 1
        sprintf($this->tagConfirmDeleteMultiple, implode(', ', $arrLabels)),
        // Id de l'objet ou des objets à supprimer - 2
        implode(',', $arrIds),
        // Url d'annulation de l'opération - 3
        $this->getQueryArg(array(self::CST_ONGLET=>$this->subMenuValue)),
      );
    } else {
      parent::initPanels($action);
    }
  }
}
