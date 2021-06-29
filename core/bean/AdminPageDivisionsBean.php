<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdminPageDivisionsBean
 * @author Hugues
 * @version 1.21.06.29
 * @since 1.21.06.01
 */
class AdminPageDivisionsBean extends AdminPageBean
{
  protected $urlTemplatePageDivisionAdmin = 'web/pages/admin/board-divisions.php';
  protected $urlTemplateForm = 'web/pages/admin/fragments/form-division.php';

  /**
   * Class Constructor
   */
  public function __construct($urlParams=null)
  {
    parent::__construct();
    $this->DivisionServices = new DivisionServices();
    $this->Services = new DivisionServices();
    // Initialisation de la Division sélectionnée s'il y en a une.
    $this->LocalObject = ($urlParams!=null && isset($urlParams[self::FIELD_ID]) ? $this->DivisionServices->selectLocal($urlParams[self::FIELD_ID]) : new Division());
    // On stocke les paramètres
    $this->urlParams = $urlParams;
    // On prépare le stockage pour les ids multiples si existants.
    $this->arrIds = array();
    $this->subMenuValue = self::PAGE_DIVISION;
  }
  /**
   * Retourne la Division
   * @return Division
   * @version 1.21.06.21
   * @since 1.21.06.06
   */
  public function getObject()
  { return $this->LocalObject; }
  /**
   * Retourne le Service
   * @return DivisionService
   * @version 1.21.06.21
   * @since 1.21.06.06
   */
  public function getServices()
  { return $this->Services; }

  /**
   * @param array $urlParams
   * @return string
   * @version 1.21.06.06
   * @since 1.21.06.01
   */
  public static function getStaticContentPage($urlParams)
  {
    ///////////////////////////////////////////:
    // Initialisation des valeurs par défaut
    $Bean = new AdminPageDivisionsBean($urlParams);
    return $Bean->getContentPage();
  }
  /**
   * @param array $urlParams
   * @return string
   * @version 1.21.06.29
   * @since 1.21.06.06
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
    ///////////////////////////////////////////:
    // On initialise les panneaux latéraux droit
    $this->msgConfirmDelete = sprintf(self::MSG_CONFIRM_SUPPR_DIVISION, $this->LocalObject->getFullName());
    $this->tagConfirmDeleteMultiple = self::MSG_CONFIRM_SUPPR_DIVISIONS;
    $this->attributesFormNew = array('');
    $this->attributesFormEdit  = array(
      // Libellé de la Division - 1
      $this->LocalObject->getLabelDivision(),
    ) ;
    $this->initPanels($initPanel);
    ///////////////////////////////////////////:
    // On retourne le listing et les panneaux latéraux droit
    return $this->getListingPage();
  }

  /**
   * @version 1.21.06.21
   * @since 1.21.06.21
   */
  public function setLocalObject()
  {
    $this->LocalObject->setLabelDivision($this->urlParams[self::FIELD_LABELDIVISION]);
  }
  /**
   * @version 1.21.06.21
   * @since 1.21.06.21
   */
  public function initLocalObject()
  { $this->LocalObject = new Division(); }

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
    $Divisions = $this->DivisionServices->getDivisionsWithFilters();
    foreach ($Divisions as $Division) {
      $Bean = $Division->getBean();
      $strRows .= $Bean->getRowForAdminPage(in_array($Division->getId(), $this->arrIds));
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
      $this->getCardImport(self::PAGE_DIVISION),
    );
    return $this->getRender($this->urlTemplatePageDivisionAdmin, $attributes);
  }

}
