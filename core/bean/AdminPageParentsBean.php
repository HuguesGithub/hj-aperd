<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdminPageParentsBean
 * @author Hugues
 * @version 1.21.06.29
 * @since 1.21.06.10
 */
class AdminPageParentsBean extends AdminPageBean
{
  protected $urlTemplatePageAdulteAdmin = 'web/pages/admin/board-parents.php';
  protected $urlTemplateForm = 'web/pages/admin/fragments/form-parent.php';

  /**
   * Class Constructor
   */
  public function __construct($urlParams=null)
  {
    parent::__construct();
    $this->AdulteServices = new AdulteServices();
    $this->Services       = new AdulteServices();
    // Initialisation du Parent sélectionné s'il y en a une.
    $this->LocalObject = ($urlParams!=null && isset($urlParams[self::FIELD_ID]) ? $this->AdulteServices->selectLocal($urlParams[self::FIELD_ID]) : new Adulte());
    // On stocke les paramètres
    $this->urlParams = $urlParams;
    // On prépare le stockage pour les ids multiples si existants.
    $this->arrIds = array();
    $this->subMenuValue = self::PAGE_PARENT;
  }
  /**
   * Retourne le Parent
   * @return Adulte
   * @version 1.21.06.29
   * @since 1.21.06.10
   */
  public function getObject()
  { return $this->LocalObject; }
  /**
   * Retourne le Service
   * @return AdulteService
   * @version 1.21.06.29
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
    $Bean = new AdminPageParentsBean($urlParams);
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
    ///////////////////////////////////////////:
    // On initialise les panneaux latéraux droit
    $this->msgConfirmDelete = sprintf(self::MSG_CONFIRM_SUPPR_PARENT, $this->Adulte->getFullName());
    $this->tagConfirmDeleteMultiple = self::MSG_CONFIRM_SUPPR_PARENTS;
    $this->attributesFormNew = array('','','','');
    $this->attributesFormEdit  = array(
      // Nom du Parent - 1
      $this->Adulte->getNomParent(),
      // Préom du Parent - 2
      $this->Adulte->getPrenomParent(),
      // Mail du Parent - 3
      $this->Adulte->getMailParent(),
      // Est adhérent ? - 4
      ($this->Adulte->isAdherent() ? self::CST_BLANK.self::CST_CHECKED : ''),
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
    $this->LocalObject->setNomParent($this->urlParams[self::FIELD_NOMPARENT]);
    $this->LocalObject->setPrenomParent($this->urlParams[self::FIELD_PRENOMPARENT]);
    $this->LocalObject->setMailParent($this->urlParams[self::FIELD_MAILPARENT]);
    $this->LocalObject->setAdherent(isset($this->urlParams[self::FIELD_ADHERENT]));
  }
  /**
   * @version 1.21.06.29
   * @since 1.21.06.29
   */
  public function initLocalObject()
  { $this->LocalObject = new Adulte(); }

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
    $Adultes = $this->AdulteServices->getAdultesWithFilters();
    foreach ($Adultes as $Adulte) {
      $Bean = $Adulte->getBean();
      $strRows .= $Bean->getRowForAdminPage(in_array($Adulte->getId(), $this->arrIds));
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
      $this->getCardImport(self::PAGE_PARENT),
    );
    return $this->getRender($this->urlTemplatePageAdulteAdmin, $attributes);
  }

}
