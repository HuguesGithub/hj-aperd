<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdminPageEnseignantsBean
 * @author Hugues
 * @version 1.21.07.06
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
    $this->LocalObject = ($urlParams!=null && isset($urlParams[self::FIELD_ID]) ? $this->EnseignantServices->selectLocal($urlParams[self::FIELD_ID]) : new Enseignant());
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
   * @version 1.21.06.22
   * @since 1.21.06.06
   */
  public function getObject()
  { return $this->LocalObject; }
  /**
   * Retourne le Service
   * @return EnseignantServices
   * @version 1.21.06.22
   * @since 1.21.06.06
   */
  public function getServices()
  { return $this->Services; }
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
   * @version 1.21.06.22
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
    $this->msgConfirmDelete = sprintf(self::MSG_CONFIRM_SUPPR_ENSEIGNANT, $this->LocalObject->getFullName());
    $this->tagConfirmDeleteMultiple = self::MSG_CONFIRM_SUPPR_ENSEIGNANTS;

    $MatiereBean = new MatiereBean();
    $DivisionBean = new DivisionBean();
    $AnneeScolaireBean = new AnneeScolaireBean();

    $arrIds = array();
    if ($this->LocalObject->getId()!='') {
      $Matieres = $this->LocalObject->getMatieres();
      while (!empty($Matieres)) {
        $Matiere = array_shift($Matieres);
        array_push($arrIds, $Matiere->getId());
      }
    }
    $argMatSelect = array(
      'tag'        => self::FIELD_MATIERE_ID.'s[]',
      self::ATTR_MULTIPLE => '',
      'selectedId' => $arrIds,
    );
    $argDivSelect = array(
      'tag'        => self::FIELD_DIVISION_ID,
    );
    $this->attributesFormNew = array(
      // Genre de l'Enseignant - 1
      '',
      // Nom de l'Enseignant - 2
      '',
      // Prénom de l'Enseignant - 3
      '',
      // Liste déroulante sur la Matière enseignée par l'Enseignant - 4
      $MatiereBean->getSelect($argMatSelect),
      // Liste déroulante sur la Division où l'Enseignant est Prof Principal- 5
      $DivisionBean->getSelect($argDivSelect),
    );

    $ProfPrincipals = $this->ProfPrincipalServices->getProfPrincipalsWithFilters(array(self::FIELD_ENSEIGNANT_ID=>$this->LocalObject->getId()));
    $ProfPrincipal = (empty($ProfPrincipals) ? new ProfPrincipal() : array_shift($ProfPrincipals));

//    $argMatSelect['selectedId'] = $this->LocalObject->getMatiereId();
    $argDivSelect['selectedId'] = $ProfPrincipal->getDivisionId();

    $this->attributesFormEdit = array(
      // Genre de l'Enseignant - 1
      $this->LocalObject->getGenre(),
      // Nom de l'Enseignant - 2
      $this->LocalObject->getNomEnseignant(),
      // Prénom de l'Enseignant - 3
      $this->LocalObject->getPrenomEnseignant(),
      // Liste déroulante sur la Matière enseignée par l'Enseignant - 4
      $MatiereBean->getSelect($argMatSelect),
      // Liste déroulante sur la Division enseignée par l'Enseignant - 5
      $DivisionBean->getSelect($argDivSelect),
    ) ;

    $this->initPanels($initPanel);
    ///////////////////////////////////////////:
    // On retourne le listing et les panneaux latéraux droit
    return $this->getListingPage();
  }

  /**
   * @version 1.21.06.22
   * @since 1.21.06.22
   */
  public function setLocalObject()
  {
    $this->LocalObject->setGenre($this->urlParams[self::FIELD_GENRE]);
    $this->LocalObject->setNomEnseignant($this->urlParams[self::FIELD_NOMENSEIGNANT]);
    $this->LocalObject->setPrenomEnseignant($this->urlParams[self::FIELD_PRENOMENSEIGNANT]);
    $this->LocalObject->setField('matiereIds', $this->urlParams[self::FIELD_MATIERE_ID.'s']);
    $this->LocalObject->setField(self::FIELD_DIVISION_ID, $this->urlParams[self::FIELD_DIVISION_ID]);
  }
  /**
   * @version 1.21.06.22
   * @since 1.21.06.22
   */
  public function initLocalObject()
  { $this->LocalObject = new Enseignant(); }

  /**
   * Gestion de l'affichage de la page.
   * @return string
   * @version 1.21.07.06
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
    $prevId = '';
    $Enseignants = $this->EnseignantServices->getEnseignantsJointsWithFilters($argFilters);
    foreach ($Enseignants as $Enseignant) {
      if ($prevId==$Enseignant->getId()) {
        continue;
      }
      $prevId = $Enseignant->getId();
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
