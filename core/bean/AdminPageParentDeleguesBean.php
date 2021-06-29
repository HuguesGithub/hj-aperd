<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdminPageParentDeleguesBean
 * @author Hugues
 * @version 1.21.06.29
 * @since 1.21.06.11
 */
class AdminPageParentDeleguesBean extends AdminPageBean
{
  protected $urlTemplatePageAdmin = 'web/pages/admin/board-parent-delegues.php';
  protected $urlTemplateForm = 'web/pages/admin/fragments/form-parent-delegue.php';

  /**
   * Class Constructor
   */
  public function __construct($urlParams=null)
  {
    parent::__construct();
    $this->ParentDelegueServices  = new ParentDelegueServices();
    $this->Services               = new ParentDelegueServices();
    // Initialisation du Parent Délégué sélectionné s'il y en a un.
    $this->LocalObject = ($urlParams!=null && isset($urlParams[self::FIELD_ID]) ? $this->ParentDelegueServices->selectLocal($urlParams[self::FIELD_ID]) : new ParentDelegue());
    // On stocke les paramètres
    $this->urlParams = $urlParams;
    $this->arrIds = array();
    // On prépare le stockage pour les ids multiples si existants.
    $this->subMenuValue = self::PAGE_PARENT_DELEGUE;
    $this->AdulteServices = new AdulteServices();
    $this->DivisionServices = new DivisionServices();
  }
  /**
   * Retourne le Parent Délégué
   * @return ParentDelegue
   * @version 1.21.06.29
   * @since 1.21.06.11
   */
  public function getObject()
  { return $this->LocalObject; }
  /**
   * Retourne le Service
   * @return ParentDelegueService
   * @version 1.21.06.29
   * @since 1.21.06.11
   */
  public function getServices()
  { return $this->Services; }

  /**
   * @param array $urlParams
   * @return string
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  public static function getStaticContentPage($urlParams)
  {
    ///////////////////////////////////////////:
    // Initialisation des valeurs par défaut
    $Bean = new AdminPageParentDeleguesBean($urlParams);
    return $Bean->getContentPage();
  }
  /**
   * @param array $urlParams
   * @return string
   * @version 1.21.06.17
   * @since 1.21.06.11
   */
  public function getContentPage()
  {
    ///////////////////////////////////////////
    // Initialisation des valeurs par défaut
    $msg = '';
    $initPanel = self::CST_CREATE;

    ///////////////////////////////////////////
    // Analyse de l'action éventuelle.
    if (!isset($this->urlParams['filter_action']) && isset($this->urlParams[self::CST_POSTACTION])) {
      $this->parseUrlParams($initPanel, $notif, $msg);
    }

    ///////////////////////////////////////////
    // Si $msg est renseigné, on a une notification à afficher.
    if ($msg!='') {
      $this->createNotification($notif, $msg);
    }
    ///////////////////////////////////////////:
    // On initialise les panneaux latéraux droit
    $this->msgConfirmDelete = sprintf(self::MSG_CONFIRM_SUPPR_PARENT_DELEGUE, $this->ParentDelegue->getLabelComplet());
    $argSelect = array(
      'tag'        => self::FIELD_DIVISION_ID,
      self::ATTR_REQUIRED => '',
    );
    $DivisionBean = new DivisionBean();
    $this->attributesFormNew = array(
      // Choix du Parent - 1
      $this->ParentDelegue->getAdulte()->getBean()->getSelect(self::FIELD_PARENT_ID, self::CST_DEFAULT_SELECT, $this->ParentDelegue->getParentId(), true),
      // Choix de la Division - 2
      $DivisionBean->getSelect($argSelect),
    );
    $this->tagConfirmDeleteMultiple = self::MSG_CONFIRM_SUPPR_PARENT_DELEGUES;
    $argSelect = array(
      'tag'        => self::FIELD_DIVISION_ID,
      'selectedId' => $this->ParentDelegue->getDivisionId(),
      self::ATTR_REQUIRED => '',
    );
    $this->attributesFormEdit  = array(
      // Choix du Parent - 1
      $this->ParentDelegue->getAdulte()->getBean()->getSelect(self::FIELD_PARENT_ID, self::CST_DEFAULT_SELECT, $this->ParentDelegue->getParentId(), true),
      // Choix de la Division - 2
      $DivisionBean->getSelect($argSelect),
    ) ;
    $this->initPanels($initPanel);
    ///////////////////////////////////////////:
    // On retourne le listing et les panneaux latéraux droit
    return $this->getListingPage();
  }

  /**
   * @version 1.21.06.29
   * @since 1.21.06.29
   */
  public function setLocalObject()
  {
    $this->LocalObject->setParentId($this->urlParams[self::FIELD_PARENT_ID]);
    $this->LocalObject->setDivisionId($this->urlParams[self::FIELD_DIVISION_ID]);
  }
  /**
   * @version 1.21.06.29
   * @since 1.21.06.29
   */
  public function initLocalObject()
  { $this->LocalObject = new ParentDelegue(); }

  /**
   * Gestion de l'affichage de la page.
   * @return string
   * @version 1.21.06.12
   * @since 1.21.06.11
   */
  public function getListingPage()
  {
    //////////////////////////////////////////////////////////////////
    // On récupère tous les Parents Délégués et on restreint l'affichage
    $ParentDelegues = $this->ParentDelegueServices->getParentDeleguesWithFilters();
    if (empty($ParentDelegues)) {
      $strRows = '<tr><td colspan="5"><em>Aucun résultat</em></td></tr>';
    } else {
      $strRows = '';
      while (!empty($ParentDelegues)) {
        $ParentDelegue = array_shift($ParentDelegues);
        $strRows .= $ParentDelegue->getBean()->getRowForAdminPage(in_array($ParentDelegue->getId(), $this->arrIds), $queryArg);
      }
    }
    //////////////////////////////////////////////////////////////////

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
      $this->getCardImport(self::PAGE_PARENT_DELEGUE),
    );
    return $this->getRender($this->urlTemplatePageAdmin, $attributes);
  }

}
