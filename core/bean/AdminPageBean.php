<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe AdminPageBean
 * @author Hugues
 * @version 1.21.06.17
 * @since 1.21.06.01
 */
class AdminPageBean extends MainPageBean
{
  protected $urlFragmentPagination = 'web/pages/admin/fragments/fragment-pagination.php';
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


















  /**
   * Intialise les panneaux latéraux à afficher
   * @param string $action
   * @version 1.21.06.17
   * @since 1.21.06.17
   */
  public function initPanels($action)
  {
    switch ($action) {
      case self::CST_DELETE :
        $this->crudType = self::CST_DELETE;
        // Définition des attributs de la Card CRUD
        $this->attributesCardCRUD = array(
          // Message de confirmation à afficher - 1
          $this->msgConfirmDelete,
          // Id de l'objet ou des objets à supprimer - 2
          $this->LocalObject->getId(),
          // Url d'annulation de l'opération - 3
          $this->getQueryArg(array(self::CST_ONGLET=>$this->subMenuValue)),
        );
      break;
      case self::CST_CREATION :
      case self::CST_EDITION  :
      case self::CST_EDIT     :
        $this->crudType = self::CST_EDIT;
        // Définition des attributs de la Card CRUD
        $this->attributesCardCRUD = array(
          // Contenu du Formulaire - 1
          $this->getRender($this->urlTemplateForm, $this->attributesFormEdit),
          // Id de l'objet ou des objets à supprimer - 2
          $this->LocalObject->getId(),
          // Url d'annulation de l'opération - 3
          $this->getQueryArg(array(self::CST_ONGLET=>$this->subMenuValue)),
        );
      break;
      case self::CST_BULK_TRASH :
        $this->crudType = self::CST_DELETE;
        // Construction des listings suite à la sélection multiple.
        $arrIds = array();
        $arrLabels = array();
        foreach($this->urlParams[self::CST_POST] as $key=> $value) {
          $Obj = $this->Services->selectLocal($value);
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
      break;
      case self::CST_BULK_EXPORT :
        foreach($this->urlParams[self::CST_POST] as $key=> $value) {
          $arrIds[] = $value;
        }
        $this->arrIds                   = $arrIds;
      case self::CST_CREATE :
      default :
        $this->crudType = self::CST_CREATE;
        // Définition des attributs de la Card CRUD
        $this->attributesCardCRUD = array(
          // Contenu du Formulaire - 1
          $this->getRender($this->urlTemplateForm, $this->attributesFormNew),
          // Url d'annulation de l'opération - 2
          $this->getQueryArg(array(self::CST_ONGLET=>$this->subMenuValue)),
        );
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

  /**
   * @param boolean $isDisabled
   * @param int $curPage
   * @param array $queryArg
   * @param string $label
   * @return string
   * @version 1.21.06.17
   * @since 1.21.06.17
   */
  protected function getPaginationLink($isDisabled, $curpage, $queryArg, $label)
  {
    if (!$isDisabled) {
      $queryArg[self::WP_CURPAGE] = $curpage;
      $href = $this->getQueryArg($queryArg);
      $addClass = '';
    } else {
      $href = '#';
      $addClass = self::CST_BLANK.self::CST_DISABLED;
    }
    return '<li class="page-item '.$addClass.'"><a class="page-link" href="'.$href.'">'.$label.'</a></li>';
  }
  /**
   * @param array $queryArg
   * @param int $curPage
   * @param int $nbPages
   * @param int $nbElements
   * @return string
   * @version 1.21.06.17
   * @since 1.21.06.01
   */
  protected function getPagination($queryArg, $curPage, $nbPages, $nbElements)
  {
    ////////////////////////////////////////////////////////////////////////////
    // Lien vers la première page. Seulement si on n'est ni sur la première, ni sur la deuxième page.
    $strToFirst = $this->getPaginationLink($curPage<3, 1, $queryArg, '&laquo;');

    ////////////////////////////////////////////////////////////////////////////
    // Lien vers la page précédente. Seulement si on n'est pas sur la première.
    $strToPrevious = $this->getPaginationLink($curPage<2, $curPage-1, $queryArg, '&lsaquo;');

    ////////////////////////////////////////////////////////////////////////////
    // Lien vers la page suivante. Seulement si on n'est pas sur la dernière.
    $strToNext = $this->getPaginationLink($curPage>=$nbPages, $curPage+1, $queryArg, '&rsaquo;');

    ////////////////////////////////////////////////////////////////////////////
    // Lien vers la dernière page. Seulement si on n'est pas sur la dernière, ni l'avant-dernière.
    $strToLast = $this->getPaginationLink($curPage>=$nbPages-1, $nbPages, $queryArg, '&raquo;');

    $args = array(
      // Nombre d'éléments - 1
      $nbElements,
      // Lien vers la première page - 2
      $strToFirst,
      // Lien vers la page précédente - 3
      $strToPrevious,
      // Page courante - 4
      $curPage,
      // Nombre total de pages - 5
      $nbPages,
      // Lien vers la page suivante - 6
      $strToNext,
      // Lien vers la dernière page - 7
      $strToLast,
    );
    return $this->getRender($this->urlFragmentPagination, $args);
  }
  /**
   * @version 1.21.06.21
   * @since 1.21.06.21
   */
  public function parseUrlParams(&$initPanel, &$notif, &$msg)
  {
    switch($this->urlParams[self::CST_POSTACTION]) {
      case self::CST_CREATION :
        // Exécution de la création
        $this->setLocalObject();
        $this->LocalObject->insert($notif, $msg);
        $this->initLocalObject();
      break;
      case self::CST_EDITION :
        // Exécution de la mise à jour
        $this->setLocalObject();
        $this->LocalObject->update($notif, $msg);
        $initPanel = self::CST_EDIT;
      break;
      case self::CST_SUPPRESSION :
        // Exécution de la suppression unitaire ou groupée
        $this->delete($notif, $msg);
        $this->initLocalObject();
      break;
      case self::CST_IMPORT :
        // Exécution de l'import
        $this->import($notif, $msg);
        $this->initLocalObject();
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
              $msg = ExportActions::dealWithStaticExport($this->subMenuValue, $this->urlParams[self::CST_POST]);
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
}
