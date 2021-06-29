<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdminPageAnneeScolairesBean
 * @author Hugues
 * @version 1.21.06.21
 * @since 1.21.06.10
 */
class AdminPageAnneeScolairesBean extends AdminPageBean
{
  protected $urlTemplatePageAnneeScolaireAdmin = 'web/pages/admin/board-annee-scolaires.php';
  protected $urlTemplateForm = 'web/pages/admin/fragments/form-annee-scolaire.php';

  /**
   * Class Constructor
   */
  public function __construct($urlParams=null)
  {
    parent::__construct();
    $this->AnneeScolaireServices = new AnneeScolaireServices();
    $this->Services              = new AnneeScolaireServices();
    // Initialisation de l'Année colaire sélectionnée s'il y en a une.
    $this->LocalObject = ($urlParams!=null && isset($urlParams[self::FIELD_ID]) ? $this->AnneeScolaireServices->selectLocal($urlParams[self::FIELD_ID]) : new AnneeScolaire());
    // On stocke les paramètres
    $this->urlParams = $urlParams;
    // On prépare le stockage pour les ids multiples si existants.
    $this->arrIds = array();
    $this->subMenuValue = self::PAGE_ANNEE_SCOLAIRE;
  }
  /**
   * Retourne l'Année Scolaire
   * @return AnneeScolaire
   * @version 1.21.06.21
   * @since 1.21.06.10
   */
  public function getObject()
  { return $this->LocalObject; }
  /**
   * Retourne le Service
   * @return AnneeScolaireService
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
    $Bean = new AdminPageAnneeScolairesBean($urlParams);
    return $Bean->getContentPage();
  }
  /**
   * @param array $urlParams
   * @return string
   * @version 1.21.06.21
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
    $this->msgConfirmDelete = sprintf(self::MSG_CONFIRM_SUPPR_ANNEESCOLAIRE, $this->LocalObject->getAnneeScolaire());
    $this->tagConfirmDeleteMultiple = self::MSG_CONFIRM_SUPPR_ANNEESCOLAIRES;
    $this->attributesFormNew = $this->LocalObject->toArrayForm();
    $this->attributesFormEdit  = $this->LocalObject->toArrayForm(false);
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
    $this->LocalObject->setAnneeScolaire($this->urlParams[self::FIELD_ANNEESCOLAIRE]);
  }
  /**
   * @version 1.21.06.21
   * @since 1.21.06.21
   */
  public function initLocalObject()
  { $this->LocalObject = new AnneeScolaire(); }

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
    $AnneeScolaires = $this->AnneeScolaireServices->getAnneeScolairesWithFilters();
    foreach ($AnneeScolaires as $AnneeScolaire) {
      $Bean = $AnneeScolaire->getBean();
      $strRows .= $Bean->getRowForAdminPage(in_array($AnneeScolaire->getId(), $this->arrIds));
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
      $this->getCardImport(self::PAGE_ANNEE_SCOLAIRE),
    );
    return $this->getRender($this->urlTemplatePageAnneeScolaireAdmin, $attributes);
  }

}
