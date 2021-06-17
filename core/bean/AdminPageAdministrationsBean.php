<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdminPageAdministrationsBean
 * @author Hugues
 * @version 1.21.06.17
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
    if ($urlParams!=null && isset($urlParams[self::FIELD_ID])) {
      $this->Administration = $this->AdministrationServices->selectLocal($urlParams[self::FIELD_ID]);
      $this->LocalObject    = $this->Administration;
    } else {
      $this->Administration = new Administration();
      $this->LocalObject    = new Administration();
    }
    // On stocke les paramètres
    $this->urlParams = $urlParams;
    // On prépare le stockage pour les ids multiples si existants.
    $this->arrIds = array();
    $this->subMenuValue = self::PAGE_ADMINISTRATION;
  }
  /**
   * Retourne l'Administration
   * @return Administration
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function getObject()
  { return $this->Administration; }
  /**
   * Retourne le Service
   * @return AdministrationService
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function getServices()
  { return $this->AdministrationServices; }

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
      switch($this->urlParams[self::CST_POSTACTION]) {
        case self::CST_CREATION :
          // Exécution de la création
          $this->Administration->setGenre($this->urlParams[self::FIELD_GENRE]);
          $this->Administration->setNomTitulaire($this->urlParams[self::FIELD_NOMTITULAIRE]);
          $this->Administration->setLabelPoste($this->urlParams[self::FIELD_LABELPOSTE]);
          $this->Administration->insert($notif, $msg);
          $this->Administration = new Administration();
        break;
        case self::CST_EDITION :
          // Exécution de la mise à jour
          $this->Administration->setGenre($this->urlParams[self::FIELD_GENRE]);
          $this->Administration->setNomTitulaire($this->urlParams[self::FIELD_NOMTITULAIRE]);
          $this->Administration->setLabelPoste($this->urlParams[self::FIELD_LABELPOSTE]);
          $this->Administration->update($notif, $msg);
          $initPanel = self::CST_EDIT;
        break;
        case self::CST_SUPPRESSION :
          // Exécution de la suppression unitaire ou groupée
          $this->delete($notif, $msg);
          $this->Administration = new Administration();
        break;
        case self::CST_IMPORT :
          // Exécution de l'import
          $this->import($notif, $msg);
          $this->Administration = new Administration();
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
                $msg = ExportActions::dealWithStaticExport(self::PAGE_ADMINISTRATION, $this->urlParams[self::CST_POST]);
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
    ///////////////////////////////////////////
    // On initialise les panneaux latéraux droit
    $this->msgConfirmDelete = sprintf(self::MSG_CONFIRM_SUPPR_ADMINISTRATION, $this->Administration->getFullName());
    $this->attributesFormNew = array('','','');
    $this->tagConfirmDeleteMultiple = self::MSG_CONFIRM_SUPPR_ADMINISTRATIONS;
    $this->attributesFormEdit  = array(
      // Genre de l'Administratif - 1
      $this->Administration->getGenre(),
      // Nom du Titulaire - 2
      $this->Administration->getNomTitulaire(),
      // Libellé du Poste - 3
      $this->Administration->getLabelPoste(),
    ) ;

    $this->initPanels($initPanel);
    ///////////////////////////////////////////
    // On retourne le listing et les panneaux latéraux droit
    return $this->getListingPage();
  }

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
