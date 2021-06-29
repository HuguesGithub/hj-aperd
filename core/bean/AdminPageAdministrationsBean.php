<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdminPageAdministrationsBean
 * @author Hugues
 * @version 1.21.06.21
 * @since 1.21.06.10
 */
class AdminPageAdministrationsBean extends AdminPageBean
{
  protected $urlTemplatePageAdministrationAdmin = 'web/pages/admin/board-administrations.php';
  protected $urlTemplateForm = 'web/pages/admin/fragments/form-administration.php';

  /**
   * Class Constructor
   */
  public function __construct($urlParams=null)
  {
    parent::__construct();
    $this->AdministrationServices = new AdministrationServices();
    $this->Services               = new AdministrationServices();
    // Initialisation de l'Administratif sélectionné s'il y en a un.
    $this->LocalObject = ($urlParams!=null && isset($urlParams[self::FIELD_ID]) ? $this->AdministrationServices->selectLocal($urlParams[self::FIELD_ID]) : new Administration());
    // On stocke les paramètres
    $this->urlParams = $urlParams;
    // On prépare le stockage pour les ids multiples si existants.
    $this->arrIds = array();
    $this->subMenuValue = self::PAGE_ADMINISTRATION;
  }
  /**
   * Retourne l'Administration
   * @return Administration
   * @version 1.21.06.21
   * @since 1.21.06.10
   */
  public function getObject()
  { return $this->LocalObject; }
  /**
   * Retourne le Service
   * @return AdministrationService
   * @version 1.21.06.21
   * @since 1.21.06.10
   */
  public function getServices()
  { return $this->Services; }

  /**
   * @param array $urlParams
   * @return string
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public static function getStaticContentPage($urlParams)
  {
    ///////////////////////////////////////////:
    // Initialisation des valeurs par défaut
    $Bean = new AdminPageAdministrationsBean($urlParams);
    return $Bean->getContentPage();
  }
  /**
   * @param array $urlParams
   * @return string
   * @version 1.21.06.10
   * @since 1.21.06.10
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
    $this->msgConfirmDelete = sprintf(self::MSG_CONFIRM_SUPPR_ADMINISTRATION, $this->LocalObject->getFullName());
    $this->tagConfirmDeleteMultiple = self::MSG_CONFIRM_SUPPR_ADMINISTRATIONS;
    $this->attributesFormNew = $this->LocalObject->toArrayForm();
    $this->attributesFormEdit  = $this->LocalObject->toArrayForm(false);
    $this->initPanels($initPanel);
    ///////////////////////////////////////////
    // On retourne le listing et les panneaux latéraux droit
    return $this->getListingPage();
  }

  /**
   * @version 1.21.06.21
   * @since 1.21.06.21
   */
  public function setLocalObject()
  {
    $this->LocalObject->setGenre($this->urlParams[self::FIELD_GENRE]);
    $this->LocalObject->setNomTitulaire($this->urlParams[self::FIELD_NOMTITULAIRE]);
    $this->LocalObject->setLabelPoste($this->urlParams[self::FIELD_LABELPOSTE]);
  }
  /**
   * @version 1.21.06.21
   * @since 1.21.06.21
   */
  public function initLocalObject()
  { $this->LocalObject = new Administration(); }

  /**
   * Gestion de l'affichage de la page.
   * @return string
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function getListingPage()
  {
    /////////////////////////////////////////////////////////////////////////////
    // On récupère toutes les matières puis on concatène les rows.
    $strRows = '';
    $Administrations = $this->AdministrationServices->getAdministrationsWithFilters();
    foreach ($Administrations as $Administration) {
      $Bean = $Administration->getBean();
      $strRows .= $Bean->getRowForAdminPage(in_array($Administration->getId(), $this->arrIds));
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
      $this->getCardImport(self::PAGE_ADMINISTRATION),
    );
    return $this->getRender($this->urlTemplatePageAdministrationAdmin, $attributes);
  }

}
