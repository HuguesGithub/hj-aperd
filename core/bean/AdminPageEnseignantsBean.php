<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdminPageEnseignantsBean
 * @author Hugues
 * @version 1.21.06.21
 * @since 1.21.06.01
 */
class AdminPageEnseignantsBean extends AdminPageBean
{
  protected $urlTemplatePageEnseignantAdmin = 'web/pages/admin/board-enseignants.php';
  protected $urlTemplateForm = 'web/pages/admin/fragments/form-enseignant.php';

  /**
   * Class Constructor
   */
  public function __construct($urlParams=null)
  {
    parent::__construct();
    $this->EnseignantServices = new EnseignantServices();
    $this->Services           = new EnseignantServices();
    // Initialisation de l'Enseignant sélectionné s'il y en a un.
    if ($urlParams!=null && isset($urlParams['id'])) {
      $this->Enseignant = $this->EnseignantServices->selectLocal($urlParams['id']);
    } else {
      $this->Enseignant = new Enseignant();
    }
    $this->LocalObject    = $this->Enseignant;
    // On stocke les paramètres
    $this->urlParams = $urlParams;
    // On prépare le stockage pour les ids multiples si existants.
    $this->arrIds = array();
    $this->subMenuValue = self::PAGE_ENSEIGNANT;
    // Autres Services
    $this->ProfPrincipalServices = new ProfPrincipalServices();
    $this->AnneeScolaireServices = new AnneeScolaireServices();
    $this->DivisionServices = new DivisionServices();
  }
  /**
   * Retourne l'Enseignant
   * @return Enseignant
   * @version 1.21.06.06
   * @since 1.21.06.06
   */
  public function getObject()
  { return $this->Enseignant; }
  /**
   * Retourne le Service
   * @return EnseignantServices
   * @version 1.21.06.06
   * @since 1.21.06.06
   */
  public function getServices()
  { return $this->EnseignantServices; }
  /**
   * @param array $urlParams
   * @return $Bean
   * @version 1.21.06.06
   * @since 1.21.06.06
   */
  public static function getStaticContentPage($urlParams)
  {
    ///////////////////////////////////////////:
    // Initialisation des valeurs par défaut
    $Bean = new AdminPageEnseignantsBean($urlParams);
    return $Bean->getContentPage();
  }
  /**
   * @param array $urlParams
   * @return string
   * @version 1.21.06.21
   * @since 1.21.06.06
   */
  public function getContentPage()
  {
    ///////////////////////////////////////////
    // Initialisation des valeurs par défaut
    $msg = '';
    $initPanel = self::CST_CREATE;

    // Analyse de l'action éventuelle.
    if (!isset($this->urlParams['filter_action']) && isset($this->urlParams[self::CST_POSTACTION])) {
      switch($this->urlParams[self::CST_POSTACTION]) {
        case self::CST_CREATION :
          // Exécution de la création
          $this->Enseignant->setNomEnseignant($this->urlParams[self::FIELD_NOMENSEIGNANT]);
          $this->Enseignant->setMatiereId($this->urlParams[self::FIELD_MATIERE_ID]);
          $this->Enseignant->insert($notif, $msg);
          $this->Enseignant = new Enseignant();
        break;
        case self::CST_EDITION :
          // Exécution de la mise à jour
          $this->Enseignant->setNomEnseignant($this->urlParams[self::FIELD_NOMENSEIGNANT]);
          $this->Enseignant->setMatiereId($this->urlParams[self::FIELD_MATIERE_ID]);
          $this->Enseignant->update($notif, $msg);
          $initPanel = self::CST_EDIT;
        break;
        case self::CST_SUPPRESSION :
          // Exécution de la suppression unitaire ou groupée
          $this->delete($notif, $msg);
          $this->Enseignant = new Enseignant();
        break;
        case self::CST_IMPORT :
          // Exécution de l'import
          $this->import($notif, $msg);
          $this->Enseignant = new Enseignant();
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
                $msg = ExportActions::dealWithStaticExport(self::PAGE_ENSEIGNANT, $this->urlParams[self::CST_POST]);
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
    $this->msgConfirmDelete = sprintf(self::MSG_CONFIRM_SUPPR_ENSEIGNANT, $this->Enseignant->getFullName());
    $this->tagConfirmDeleteMultiple = self::MSG_CONFIRM_SUPPR_ENSEIGNANTS;

    $MatiereBean = new MatiereBean();
    $DivisionBean = new DivisionBean();
    $AnneeScolaireBean = new AnneeScolaireBean();

    $argMatSelect = array(
      'tag'        => self::FIELD_MATIERE_ID,
      self::ATTR_REQUIRED => '',
    );
    $argDivSelect = array(
      'tag'        => self::FIELD_DIVISION_ID,
      self::ATTR_REQUIRED => '',
    );
    $argAsSelect = array(
      'tag'        => self::FIELD_ANNEESCOLAIRE_ID,
      self::ATTR_REQUIRED => '',
    );
    $this->attributesFormNew = array(
      '', '', '',
      $MatiereBean->getSelect($argMatSelect),
      $DivisionBean->getSelect($argDivSelect),
      $AnneeScolaireBean->getSelect($argAsSelect),
    );

    $ProfPrincipals = $this->ProfPrincipalServices->getProfPrincipalsWithFilters(array(self::FIELD_ENSEIGNANT_ID=>$this->Enseignant->getId()));
    $ProfPrincipal = (empty($ProfPrincipals) ? new ProfPrincipal() : array_shift($ProfPrincipals));

    $argMatSelect['selectedId'] = $this->Enseignant->getMatiereId();
    $argDivSelect['selectedId'] = $ProfPrincipal->getDivisionId();
    $argAsSelect['selectedId'] = $ProfPrincipal->getAnneeScolaireId();

    $this->attributesFormEdit = array(
      // Genre de l'Enseignant - 1
      $this->Enseignant->getGenre(),
      // Nom de l'Enseignant - 2
      $this->Enseignant->getNomEnseignant(),
      // Prénom de l'Enseignant - 3
      $this->Enseignant->getPrenomEnseignant(),
      // Liste déroulante sur la Matière enseignée par l'Enseignant - 4
      $MatiereBean->getSelect($argMatSelect),
      // Liste déroulante sur la Division enseignée par l'Enseignant - 5
      $DivisionBean->getSelect($argDivSelect),
      // Liste déroulante sur l'Année Scolaire enseignée par l'Enseignant - 6
      $AnneeScolaireBean->getSelect($argAsSelect),
    ) ;

    $this->initPanels($initPanel);
    ///////////////////////////////////////////:
    // On retourne le listing et les panneaux latéraux droit
    return $this->getListingPage();
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
    // On récupère les filtres éventuels.
    $argFilters = array();
    $filterMatiereId = (isset($this->urlParams[self::FIELD_MATIERE_ID]) ? $this->urlParams[self::FIELD_MATIERE_ID] : '');
    $argFilters[self::FIELD_MATIERE_ID] = $filterMatiereId;
    // Fin gestion des filtres
    /////////////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////////////
    // On récupère toutes les matières puis on concatène les rows.
    $strRows = '';
    $Enseignants = $this->EnseignantServices->getEnseignantsWithFilters($argFilters);
    foreach ($Enseignants as $Enseignant) {
      $Bean = $Enseignant->getBean();
      $strRows .= $Bean->getRowForAdminPage(in_array($Enseignant->getId(), $this->arrIds));
    }
    /////////////////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////////////
    // Construction des filtres utilisés
    $strFiltres = '';
    $MatiereBean = new MatiereBean();
    $argSelect = array(
      'tag'        => self::FIELD_MATIERE_ID,
      'selectedId' => $filterMatiereId,
    );
    $strFiltres .= $MatiereBean->getSelect($argSelect);
    //////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////////////
    // On restitue le template enrichi.
    $attributes = array(
      // Liste des matières affichées - 1
      $strRows,
      // Filtre - 2
      $strFiltres,
      // Notification suite à soumission formulaire - 3
      $this->notifications,
      // Card C(R)UD - 4
      $this->getCardCRUD($this->crudType, $this->attributesCardCRUD),
      // Card Importation - 5
      $this->getCardImport(self::PAGE_ENSEIGNANT),
    );
    return $this->getRender($this->urlTemplatePageEnseignantAdmin, $attributes);
  }


}
