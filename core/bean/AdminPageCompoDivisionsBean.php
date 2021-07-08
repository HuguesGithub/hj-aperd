<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdminPageCompoDivisionsBean
 * @author Hugues
 * @version 1.21.07.08
 * @since 1.21.06.01
 */
class AdminPageCompoDivisionsBean extends AdminPageBean
{
  protected $urlTemplatePageAdmin = 'web/pages/admin/board-compo-divisions.php';
  protected $urlTemplateForm = 'web/pages/admin/fragments/form-compo-division.php';

  /**
   * Class Constructor
   */
  public function __construct($urlParams=null)
  {
    parent::__construct();
    $this->CompoDivisionServices = new CompoDivisionServices();
    $this->Services       = new CompoDivisionServices();
    // Initialisation de la Compo Division sélectionnée s'il y en a une.
    $this->LocalObject = ($urlParams!=null && isset($urlParams['id']) ? $this->CompoDivisionServices->selectLocal($urlParams['id']) : new CompoDivision());
    // On stocke les paramètres
    $this->urlParams = $urlParams;
     // On prépare le stockage pour les ids multiples si existants.
    $this->arrIds = array();
    $this->subMenuValue = self::PAGE_COMPO_DIVISION;
  }
  /**
   * @return CompoDivision
   * @version 1.21.07.07
   * @since 1.21.06.01
   */
  public function getObject()
  { return $this->LocalObject; }
  /**
   * Retourne le Service
   * @return CompoDivisionServices
   * @version 1.21.07.07
   * @since 1.21.07.07
   */
  public function getServices()
  { return $this->Services; }
  /**
   * @param array $urlParams
   * @return $Bean
   * @version 1.21.07.07
   * @since 1.21.06.01
   */
  public static function getStaticContentPage($urlParams)
  {
    ///////////////////////////////////////////:
    // Initialisation des valeurs par défaut
    $Bean = new AdminPageCompoDivisionsBean($urlParams);
    return $Bean->getContentPage();
  }
  /**
   * @param array $urlParams
   * @return string
   * @version 1.21.07.07
   * @since 1.21.07.07
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
    //$this->msgConfirmDelete = sprintf(self::MSG_CONFIRM_SUPPR_COMPO_DIVISION, $this->LocalObject->getFullName());
    //$this->tagConfirmDeleteMultiple = self::MSG_CONFIRM_SUPPR_COMPO_DIVISIONS;

    $DivisionBean = new DivisionBean();
    $EnseignantMatiereBean = new EnseignantMatiereBean();
    $argDivSelect = array(
      'tag'        => self::FIELD_DIVISION_ID,
      self::ATTR_REQUIRED => '',
    );
    $argEnsMatSelect = array(
      'tag'        => self::FIELD_ENSEIGNANT_MATIERE_ID,
      self::ATTR_REQUIRED => '',
    );
    $this->attributesFormNew = array(
      $DivisionBean->getSelect($argDivSelect),
      $EnseignantMatiereBean->getSelect($argEnsMatSelect),
    );


    $argDivSelect['selectedId'] = $this->LocalObject->getDivisionId();
    $argEnsMatSelect['selectedId'] = $this->LocalObject->getEnseignantMatiereId();
    $this->attributesFormEdit = array(
      $DivisionBean->getSelect($argDivSelect),
      $EnseignantMatiereBean->getSelect($argEnsMatSelect),
    );

    $this->initPanels($initPanel);

    ///////////////////////////////////////////:
    // On retourne le listing et les panneaux latéraux droit
    return $this->getListingPage();
  }

  /**
   * @version 1.21.07.07
   * @since 1.21.07.07
   */
  public function setLocalObject()
  {
    $this->LocalObject->setDivisionId($this->urlParams[self::FIELD_DIVISION_ID]);
    $this->LocalObject->setEnseignantMatiereId($this->urlParams[self::FIELD_ENSEIGNANT_MATIERE_ID]);
  }
  /**
   * @version 1.21.07.07
   * @since 1.21.07.07
   */
  public function initLocalObject()
  { $this->LocalObject = new CompoDivision(); }

  /**
   * Gestion de l'affichage de la page.
   * @return string
   * @version 1.21.07.08
   * @since 1.21.06.01
   */
  public function getListingPage()
  {
    /////////////////////////////////////////////////////////////////////////////
    // On récupère les filtres éventuels.
    $argFilters = array();
    $filterDivisionId = (isset($this->urlParams[self::FIELD_DIVISION_ID]) ? $this->urlParams[self::FIELD_DIVISION_ID] : '');
    $argFilters[self::FIELD_DIVISION_ID] = $filterDivisionId;
    $filterMatiereId = (isset($this->urlParams[self::FIELD_MATIERE_ID]) ? $this->urlParams[self::FIELD_MATIERE_ID] : '');
    $argFilters[self::FIELD_MATIERE_ID] = $filterMatiereId;
    $filterEnseignantId = (isset($this->urlParams[self::FIELD_ENSEIGNANT_ID]) ? $this->urlParams[self::FIELD_ENSEIGNANT_ID] : '');
    $argFilters[self::FIELD_ENSEIGNANT_ID] = $filterEnseignantId;
    // Fin gestion des filtres

    //////////////////////////////////////////////////////////////////
    // On récupère tous les Compos et on construit la base de la pagination et on restreint l'affichage
    $nbPerPage = 10;
    $orderby = $this->initVar(self::WP_ORDERBY, self::FIELD_ID);
    $order = $this->initVar(self::WP_ORDER, self::ORDER_ASC);

    $CompoDivisions = $this->CompoDivisionServices->getCompoDivisionsWithFilters($argFilters, $orderby, $order);
    $nbElements = count($CompoDivisions);
    $nbPages = ceil($nbElements/$nbPerPage);
    $curPage = $this->initVar(self::WP_CURPAGE, 1);
    $curPage = max(1, min($curPage, $nbPages));
    $queryArg = array_merge(
      array(
        self::CST_ONGLET => self::PAGE_COMPO_DIVISION,
        self::WP_ORDERBY => $orderby,
        self::WP_ORDER   => $order,
        self::WP_CURPAGE => $curPage,
      ),
      $argFilters,
    );

    $DisplayedCompoDivisions = array_slice($CompoDivisions, ($curPage-1)*$nbPerPage, $nbPerPage);
    if (empty($DisplayedCompoDivisions)) {
      $strRows = '<tr><td colspan="5"><em>Aucun résultat</em></td></tr>';
    } else {
      $strRows = '';
      while (!empty($DisplayedCompoDivisions)) {
        $CompoDivision = array_shift($DisplayedCompoDivisions);
        $strRows .= $CompoDivision->getBean()->getRowForAdminPage(false, $queryArg);
      }
    }
    //////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////////////
    // Construction des filtres utilisés
    $strFiltres = '';
    // La Division
    $DivisionBean = new DivisionBean();
    $argSelect = array(
      'tag'        => self::FIELD_DIVISION_ID,
      'selectedId' => $filterDivisionId,
    );
    $strFiltres .= $DivisionBean->getSelect($argSelect);
    $strFiltres .= '<label for="divisionId">Divisions</label>';
    $strFiltres .= '</div></div><div class="col-md"><div class="form-floating">';
    // L'Enseignant
    $EnseignantBean = new EnseignantBean();
    $argSelect = array(
      'tag'        => self::FIELD_ENSEIGNANT_ID,
      'selectedId' => $filterEnseignantId,
    );
    $strFiltres .= $EnseignantBean->getSelect($argSelect);
    $strFiltres .= '<label for="enseignantId">Enseignants</label>';
    $strFiltres .= '</div></div><div class="col-md"><div class="form-floating">';
    // La Matière
    $MatiereBean = new MatiereBean();
    $argSelect = array(
      'tag'        => self::FIELD_MATIERE_ID,
      'selectedId' => $filterMatiereId,
    );
    $strFiltres .= $MatiereBean->getSelect($argSelect);
    $strFiltres .= '<label for="matiereId">Matière</label>';
    //////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////////////
    // Pagination
    $strPagination = $this->getPagination($queryArg, $post_status, $curPage, $nbPages, $nbElements);
    //////////////////////////////////////////////////////////////////

    $attributes = array(
      // Liste des matières affichées - 1
      $strRows,
      // Notification suite à soumission formulaire - 2
      $this->notifications,
      // Card C(R)UD - 3
      $this->getCardCRUD($this->crudType, $this->attributesCardCRUD),
      // Card Importation - 4
      $this->getCardImport(self::PAGE_COMPO_DIVISION),
      // La Pagination - 5
      $strPagination,
      // Les Filtres - 6
      $strFiltres,
    );
    return $this->getRender($this->urlTemplatePageAdmin, $attributes);
  }

}
