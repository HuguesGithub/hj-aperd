<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe AdminPageBean
 * @author Hugues
 * @version 1.21.06.10
 * @since 1.21.06.01
 */
class AdminPageBean extends MainPageBean
{
  public $Services;

  /**
   * Class Constructor
   * @version 1.21.06.06
   * @since 1.21.06.01
   */
  public function __construct()
  {
    parent::__construct();
    $this->analyzeUri();
  }

  /**
   * @return string
   * @version 1.21.06.06
   * @since 1.21.06.01
   */
  public function analyzeUri()
  {
    $uri = $_SERVER['REQUEST_URI'];
    $pos = strpos($uri, '?');
    if ($pos!==false) {
      $arrParams = explode('&', substr($uri, $pos+1, strlen($uri)));
      if (!empty($arrParams)) {
        foreach ($arrParams as $param) {
          list($key, $value) = explode('=', $param);
          $this->urlParams[$key] = $value;
        }
      }
      $uri = substr($uri, 0, $pos-1);
    }
    $pos = strpos($uri, '#');
    if ($pos!==false) {
      $this->anchor = substr($uri, $pos+1, strlen($uri));
    }
    if (isset($_POST)) {
      foreach ($_POST as $key => $value) {
        $this->urlParams[$key] = $value;
      }
    }
    return $uri;
  }


  /**
   * @return string
   * @version 1.21.06.10
   * @since 1.21.06.01
   */
  public function getContentPage()
  {
    if (self::isAdmin() || current_user_can('editor')) {
      switch ($this->urlParams[self::CST_ONGLET]) {
        case self::PAGE_ADMINISTRATION :
          $returned = AdminPageAdministrationsBean::getStaticContentPage($this->urlParams);
        break;
        case self::PAGE_ANNEE_SCOLAIRE :
          $returned = AdminPageAnneeScolairesBean::getStaticContentPage($this->urlParams);
        break;
        case self::PAGE_COMPO_DIVISION :
          $returned = AdminPageCompoDivisionsBean::getStaticContentPage($this->urlParams);
        break;
        case self::PAGE_COMPTE_RENDU :
          $returned = AdminPageCompteRendusBean::getStaticContentPage($this->urlParams);
        break;
        case self::PAGE_CONFIGURATION :
          $returned = AdminPageConfigurationsBean::getStaticContentPage($this->urlParams);
        break;
        case self::PAGE_DIVISION   :
          $returned = AdminPageDivisionsBean::getStaticContentPage($this->urlParams);
        break;
        case self::PAGE_ELEVE   :
          $returned = AdminPageElevesBean::getStaticContentPage($this->urlParams);
        break;
        case self::PAGE_ENSEIGNANT   :
          $returned = AdminPageEnseignantsBean::getStaticContentPage($this->urlParams);
        break;
        case self::PAGE_MATIERE      :
          $returned = AdminPageMatieresBean::getStaticContentPage($this->urlParams);
        break;
        case self::PAGE_PARENT :
          $returned = AdminPageParentsBean::getStaticContentPage($this->urlParams);
        break;
        case self::PAGE_PARENT_DELEGUE :
          $returned = AdminPageParentDeleguesBean::getStaticContentPage($this->urlParams);
        break;
        case self::PAGE_QUESTIONNAIRE :
          $returned = AdminPageQuestionnairesBean::getStaticContentPage($this->urlParams);
        break;
        default       :
          $returned = $this->getBoard();
        break;
      }
    }
    return $returned;
  }
  public function getBoard()
  {
    $urlTemplatePageAdmin = 'web/pages/admin/board-schema-table.php';
    return $this->getRender($urlTemplatePageAdmin, array());
  }
  /**
   * Retourne la Notification
   * @param string $typeAlert
   * @param string $msg
   * @return string
   * @version 1.21.06.06
   * @since 1.21.06.01
   */
  public function createNotification($typeAlert, $msg)
  {
    $this->notifications  = '<div class="alert alert-'.$typeAlert.' alert-dismissible fade show" role="alert">';
    $this->notifications .= $msg.'<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
    $this->notifications .= '<span aria-hidden="true">&times;</span></button></div>';
  }

  /**
   * Gestion de la suppression, unitaire ou multiple
   * @param string &$notif
   * @param string &$msg
   * @version 1.21.06.06
   * @since 1.21.06.01
   */
  public function delete(&$notif, &$msg)
  {
    if (strpos($this->urlParams[self::FIELD_ID], ',')!==false) {
      // Si présence d'une virgule dans le paramètre, on a une sélection multiple.
      $this->getServices()->deleteIn($this->urlParams[self::FIELD_ID]);
      $msg   = self::MSG_BULK_DELETE_SUCCESS;
      $notif = self::NOTIF_SUCCESS;
    } else {
      // Sinon, une sélection simple.
      $this->getObject()->delete($notif, $msg);
      $this->createNotification($notif, $msg);
    }
  }

  /**
   * Gestion de l'import de masse.
   * @param string &$notif
   * @param string &$msg
   * @version 1.21.06.06
   * @since 1.21.06.01
   */
  public function import(&$notif, &$msg)
  {
    $msg   = 'Importation du fichier échouée.';
    $notif = self::NOTIF_DANGER;
    $importType = $this->urlParams['importType'];

    if (is_uploaded_file($_FILES['fileToImport']['tmp_name'])) {
      $dir_name  = dirname(__FILE__).'/../../web/rsc/csv-files/';
      $file_name = 'import_'.$importType.'.csv';
      if (rename($_FILES['fileToImport']['tmp_name'], $dir_name.$file_name)) {
        ImportActions::dealWithStaticImport($importType, $notif, $msg);
      }
    }
  }




















  public function initPanels($action)
  {
    switch ($action) {
      case 'bulk-export' :
      case self::CST_CREATION :
        $this->cardCreationEditionTitre = 'Création';
        $this->showCardEditionCreation = true;
        $this->showCardSuppression     = false;
      break;
      case 'Création' :
      case 'Édition' :
      case self::CST_EDIT :
        $this->cardCreationEditionTitre = 'Édition';
        $this->showCardEditionCreation = true;
        $this->showCardSuppression     = false;
      break;
      case 'bulk-trash' :
        $this->cardCreationEditionTitre = '';
        $this->showSuppressionSimple    = false;
        $this->showCardEditionCreation  = false;
        $this->showCardSuppression      = true;
        $arrIds = array();
        $arrLabels = array();
        foreach($this->urlParams['post'] as $key=> $value) {
          $Matiere = $this->MatiereServices->selectLocal($value);
          $arrLabels[] = $Matiere->getLabelMatiere();
          $arrIds[] = $value;
        }
        $this->arrIds                   = $arrIds;
        $this->ids                      = implode(',', $arrIds);
        $this->libellesMultiples        = implode(', ', $arrLabels);
      break;
      case self::CST_DELETE :
        $this->ids = $this->Matiere->getId();
        $this->cardCreationEditionTitre = 'Suppression';
        $this->showSuppressionSimple    = true;
        $this->showCardEditionCreation  = false;
        $this->showCardSuppression      = true;
      break;
      default :
        $this->cardCreationEditionTitre = 'WIP - '.$action.' -';
        $this->showCardEditionCreation = true;
        $this->showCardSuppression     = false;

      break;
    }
  }

  /**
   * Ajout de l'interface d'Importation
   * @param string $importType
   * @return string
   * @version 1.21.06.05
   * @since 1.21.06.05
   */
  public function getCardImport($importType)
  {
    $urlTemplateCardImportation = 'web/pages/admin/fragments/card-import.php';
    $attributes = array(
      // importType pour différencier l'import - 1
      $importType,
    );
    return $this->getRender($urlTemplateCardImportation, $attributes);
  }

  /**
   * Ajout de l'interface CRUD
   * @param string $importType
   * @return string
   * @version 1.21.06.06
   * @since 1.21.06.06
   */
  public function getCardCRUD($crudType, $attributes=array())
  {
    switch ($crudType) {
      case self::CST_CREATE :
      // Vérifier le nombre d'éléments (3) dans $attributes pour matcher le Template ?
        $urlTemplateCard = 'web/pages/admin/fragments/card-create.php';
      break;
      case self::CST_DELETE :
      // Vérifier le nombre d'éléments (3) dans $attributes pour matcher le Template ?
        $urlTemplateCard = 'web/pages/admin/fragments/card-delete.php';
      break;
      case self::CST_EDIT :
      // Vérifier le nombre d'éléments (3) dans $attributes pour matcher le Template ?
        $urlTemplateCard = 'web/pages/admin/fragments/card-edit.php';
      break;
      default :
        return 'WIP Card CRUD ['.$crudType.']';
      break;
    }
    return $this->getRender($urlTemplateCard, $attributes);
  }
}
