<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdminPageQuestionnairesBean
 * @author Hugues
 * @version 1.21.06.12
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
        $this->Questionnaire = new Questionnaire();
      } else {
        $this->Questionnaire = array_shift($Questionnaires);
      }
    } else {
      $this->Questionnaire = new Questionnaire();
    }
    // On stocke les paramètres
    $this->urlParams = $urlParams;
    // On prépare le stockage pour les ids multiples si existants.
    $this->arrIds = array();
  }
  /**
   * Retourne le Questionnaire
   * @return Division
   * @version 1.21.06.09
   * @since 1.21.06.09
   */
  public function getObject()
  { return $this->Questionnaire; }
  /**
   * Retourne le Service
   * @return QuestionnaireService
   * @version 1.21.06.09
   * @since 1.21.06.09
   */
  public function getServices()
  { return $this->QuestionnaireServices; }

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
      switch($this->urlParams[self::CST_POSTACTION]) {
        case self::CST_CREATION :
          // Exécution de la création
          $this->Questionnaire->setConfigKey($this->urlParams[self::FIELD_CONFIG_KEY]);
          $this->Questionnaire->setConfigValue(stripslashes($this->urlParams[self::FIELD_CONFIG_VALUE]));
          $this->Questionnaire->insert($notif, $msg);
          $this->Questionnaire = new Questionnaire();
        break;
        case self::CST_EDITION :
          // Exécution de la mise à jour
          $this->Questionnaire->setConfigKey($this->urlParams[self::FIELD_CONFIG_KEY]);
          $this->Questionnaire->setConfigValue(stripslashes($this->urlParams[self::FIELD_CONFIG_VALUE]));
          $this->Questionnaire->update($notif, $msg);
          $initPanel = self::CST_EDIT;
        break;
        case self::CST_SUPPRESSION :
          // Exécution de la suppression unitaire ou groupée
          $this->delete($notif, $msg);
          $this->Questionnaire = new Questionnaire();
        break;
        case self::CST_IMPORT :
          // Exécution de l'import
          $this->import($notif, $msg);
          $this->Questionnaire = new Questionnaire();
        break;
        case self::CST_BULK :
          // Gestion des Actions groupées
          switch ($this->urlParams[self::CST_ACTION]) {
            case self::CST_TRASH :
              // Confirmation de la Suppression de masse
              if (empty($this->urlParams[self::CST_POST])) {
                $msg = self::MSG_BULK_DELETE_IMPOSSIBLE;
                $notif = self::NOTIF_WARNING;
              } else {
                $initPanel = self::CST_BULK_TRASH;
              }
            break;
            case self::CST_EXPORT :
              // Exécution de l'exportation
              if (empty($this->urlParams[self::CST_POST])) {
                $msg = self::MSG_BULK_EXPORT_IMPOSSIBLE;
                $notif = self::NOTIF_WARNING;
              } else {
                $msg = ExportActions::dealWithStaticExport(self::PAGE_QUESTIONNAIRE, $this->urlParams[self::CST_POST]);
                $notif = self::NOTIF_SUCCESS;
                $initPanel = self::CST_BULK_EXPORT;
              }
            break;
            default :
              // Erreur sur l'action groupée, non reconnue
              $notif = self::NOTIF_WARNING;
              $msg   = sprintf(self::MSG_BULK_ACTION_INDEFINIE, array($this->urlParams[self::CST_ACTION]));
            break;
          }
        break;
        default :
          // Affichage des écrans simples : création ou édition
          $initPanel = $this->urlParams[self::CST_POSTACTION];
        break;
      }
    }

    ///////////////////////////////////////////
    // Si $msg est renseigné, on a une notification à afficher.
    if ($msg!='') {
      $this->createNotification($notif, $msg);
    }
    ///////////////////////////////////////////:
    // On initialise les panneaux latéraux droit
    $this->initPanels($initPanel);
    ///////////////////////////////////////////:
    // On retourne le listing et les panneaux latéraux droit
    return $this->getListingPage();
  }

  /**
   * Intialise les panneaux latéraux à afficher
   * @param string $action
   * @version 1.21.06.12
   * @since 1.21.06.01
   */
  public function initPanels($action)
  {
    switch ($action) {
      case self::CST_DELETE :
        $this->crudType = self::CST_DELETE;
        // Définition des attributs de la Card CRUD
        $this->attributesCardCRUD = array(
          // Message de confirmation à afficher - 1
          sprintf(self::MSG_CONFIRM_SUPPR_QUESTIONNAIRE, $this->Questionnaire->getConfigKey()),
          // Id de l'objet ou des objets à supprimer - 2
          $this->Questionnaire->getConfigKey(),
          // Url d'annulation de l'opération - 3
          $this->getQueryArg(array(self::CST_ONGLET=>self::PAGE_QUESTIONNAIRE)),
        );
      break;
      case self::CST_CREATION :
      case self::CST_EDITION  :
      case self::CST_EDIT     :
        $this->crudType = self::CST_EDIT;
        $attributesForm  = array(
          // Clé de la config - 1
          $this->Questionnaire->getConfigKey(),
          // Valeur de la config - 2
          $this->Questionnaire->getConfigValue(),
        ) ;
        // Définition des attributs de la Card CRUD
        $this->attributesCardCRUD = array(
          // Contenu du Formulaire - 1
          $this->getRender($this->urlTemplateForm, $attributesForm),
          // Id de l'objet ou des objets à supprimer - 2
          $this->Questionnaire->getConfigKey(),
          // Url d'annulation de l'opération - 3
          $this->getQueryArg(array(self::CST_ONGLET=>self::PAGE_QUESTIONNAIRE)),
        );
      break;
      case self::CST_BULK_TRASH :
        $this->crudType = self::CST_DELETE;
        // Construction des listings suite à la sélection multiple.
        $arrIds = array();
        $arrLabels = array();
        foreach($this->urlParams[self::CST_POST] as $key=> $value) {
          $Questionnaire = $this->QuestionnaireServices->getQuestionnaireWithFilters(array(self::FIELD_CONFIG_KEY=>$key));
          $arrLabels[] = $Questionnaire->getConfigKey();
          $arrIds[] = $value;
        }
        $this->arrIds                   = $arrIds;
        // Définition des attributs de la Card CRUD
        $this->attributesCardCRUD = array(
          // Message de confirmation à afficher - 1
          sprintf(self::MSG_CONFIRM_SUPPR_QUESTIONNAIRES, implode(', ', $arrLabels)),
          // Id de l'objet ou des objets à supprimer - 2
          implode(',', $arrIds),
          // Url d'annulation de l'opération - 3
          $this->getQueryArg(array(self::CST_ONGLET=>self::PAGE_QUESTIONNAIRE)),
        );
      break;
      case self::CST_BULK_EXPORT :
        foreach($this->urlParams[self::CST_POST] as $key=> $value) {
          $arrIds[] = $value;
        }
        $this->arrIds                   = $arrIds;
      case self::CST_CREATE :
      default :
        $this->crudType = self::CST_CREATE;
        $attributesForm  = array('','',);
        // Définition des attributs de la Card CRUD
        $this->attributesCardCRUD = array(
          // Contenu du Formulaire - 1
          $this->getRender($this->urlTemplateForm, $attributesForm),
          // Url d'annulation de l'opération - 2
          $this->getQueryArg(array(self::CST_ONGLET=>self::PAGE_QUESTIONNAIRE)),
        );
      break;
    }
  }

  /**
   * Gestion de l'affichage de la page.
   * @return string
   * @version 1.21.06.06
   * @since 1.21.06.01
   */
  public function getListingPage()
  {
    /////////////////////////////////////////////////////////////////////////////
    // On récupère toutes les matières puis on concatène les rows.
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

}
