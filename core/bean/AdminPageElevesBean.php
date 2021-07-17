<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdminPageElevesBean
 * @author Hugues
 * @version 1.21.07.06
 * @since 1.21.06.01
 */
class AdminPageElevesBean extends AdminPageBean
{
  protected $urlTemplatePageAdmin = 'web/pages/admin/board-eleves.php';
  protected $urlTemplateForm = 'web/pages/admin/fragments/form-eleve.php';

  /**
   * Class Constructor
   */
  public function __construct($urlParams=null)
  {
    parent::__construct();
    $this->EleveServices  = new EleveServices();
    $this->Services       = new EleveServices();
    // Initialisation de l'Elève sélectionné s'il y en a un.
    $this->LocalObject    = ($urlParams!=null && isset($urlParams[self::FIELD_ID]) ? $this->EleveServices->selectLocal($urlParams[self::FIELD_ID]) : new Eleve());
    // On stocke les paramètres
    $this->urlParams = $urlParams;
    // On prépare le stockage pour les ids multiples si existants.
    $this->arrIds = array();
    $this->subMenuValue = self::PAGE_ELEVE;
    $this->AnneeScolaireServices = new AnneeScolaireServices();
    $this->DivisionServices = new DivisionServices();
  }
  /**
   * Retourne l'Elève
   * @return Eleve
   * @version 1.21.07.07
   * @since 1.21.06.01
   */
  public function getObject()
  { return $this->LocalObject; }
  /**
   * Retourne le Service
   * @return EleveServices
   * @version 1.21.07.07
   * @since 1.21.06.11
   */
  public function getServices()
  { return $this->Services; }

  /**
   * @param array $urlParams
   * @return string
   * @version 1.21.06.11
   * @since 1.21.06.01
   */
  public static function getStaticContentPage($urlParams)
  {
    ///////////////////////////////////////////:
    // Initialisation des valeurs par défaut
    $Bean = new AdminPageElevesBean($urlParams);
    return $Bean->getContentPage();
  }
  /**
   * @param array $urlParams
   * @return string
   * @version 1.21.07.07
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
    $this->msgConfirmDelete = sprintf(self::MSG_CONFIRM_SUPPR_ELEVE, $this->LocalObject->getFullName());
    $this->tagConfirmDeleteMultiple = self::MSG_CONFIRM_SUPPR_ELEVES;

    $DivisionBean = new DivisionBean();

    $argSelect = array(
      'tag'        => self::FIELD_DIVISION_ID,
      self::ATTR_REQUIRED => '',
    );
    $this->attributesFormNew = array('', '', $DivisionBean->getSelect($argSelect), '');
    $argSelect['selectedId'] = $this->LocalObject->getDivisionId();
    $this->attributesFormEdit  = array(
      // Nom de l'Elève - 1
      $this->LocalObject->getNomEleve(),
      // Prénom de l'Eleve - 2
      $this->LocalObject->getPrenomEleve(),
      // Division de l'Eleve - 3
      $DivisionBean->getSelect($argSelect),
      // Est délégué ? - 4
      ($this->LocalObject->isDelegue() ? self::CST_BLANK.self::CST_CHECKED : ''),
    ) ;

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
    $this->LocalObject->setNomEleve($this->urlParams[self::FIELD_NOMELEVE]);
    $this->LocalObject->setPrenomEleve($this->urlParams[self::FIELD_PRENOMELEVE]);
    $this->LocalObject->setDivisionId($this->urlParams[self::FIELD_DIVISION_ID]);
    $this->LocalObject->setDelegue(isset($this->urlParams[self::FIELD_DELEGUE]));
  }
  /**
   * @version 1.21.07.07
   * @since 1.21.07.07
   */
  public function initLocalObject()
  { $this->LocalObject = new Eleve(); }

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
    $filterDivisionId = (isset($this->urlParams[self::FIELD_DIVISION_ID]) ? $this->urlParams[self::FIELD_DIVISION_ID] : '');
    $argFilters[self::FIELD_DIVISION_ID] = $filterDivisionId;
    $searchTerm = (isset($this->urlParams['searchTerm']) ? $this->urlParams['searchTerm'] : '');
    $argFilters[self::FIELD_NOMELEVE] = $searchTerm;
    $argFilters[self::FIELD_PRENOMELEVE] = $searchTerm;
    // Fin gestion des filtres

    //////////////////////////////////////////////////////////////////
    // On récupère tous les Elèves et on construit la base de la pagination et on restreint l'affichage
    $nbPerPage = (isset($this->urlParams[self::FIELD_DIVISION_ID]) ? 50 : 10);
    $orderby = $this->initVar(self::WP_ORDERBY, self::FIELD_NOMELEVE);
    $order = $this->initVar(self::WP_ORDER, self::ORDER_ASC);
    if ($searchTerm!='') {
      $Eleves = $this->EleveServices->getElevesWithFilteredSearch($argFilters, $orderby, $order);
    } else {
      $Eleves = $this->EleveServices->getElevesWithFilters($argFilters, $orderby, $order);
    }
    $nbElements = count($Eleves);
    $nbPages = ceil($nbElements/$nbPerPage);
    $curPage = $this->initVar(self::WP_CURPAGE, 1);
    $curPage = max(1, min($curPage, $nbPages));
    $queryArg = array_merge(
      array(
        self::CST_ONGLET => self::PAGE_ELEVE,
        self::WP_ORDERBY => $orderby,
        self::WP_ORDER   => $order,
        self::WP_CURPAGE => $curPage,
      ),
      $argFilters,
    );

    $DisplayedEleves = array_slice($Eleves, ($curPage-1)*$nbPerPage, $nbPerPage);
    if (empty($DisplayedEleves)) {
      $strRows = '<tr><td colspan="6"><em>Aucun résultat</em></td></tr>';
    } else {
      $strRows = '';
      while (!empty($DisplayedEleves)) {
        $Eleve = array_shift($DisplayedEleves);
        $strRows .= $Eleve->getBean()->getRowForAdminPage(in_array($Eleve->getId(), $this->arrIds), $queryArg);
      }
    }
    //////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////////////
    // Construction des filtres utilisés
    $strFiltres = '';
    $DivisionBean = new DivisionBean();
    $argSelect = array(
      'tag'        => self::FIELD_DIVISION_ID,
      'selectedId' => $filterDivisionId,
    );
    $strFiltres .= $DivisionBean->getSelect($argSelect);
    //////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////////////
    // Pagination
    $strPagination = $this->getPagination($queryArg, $curPage, $nbPages, $nbElements);
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
      $this->getCardImport(self::PAGE_ELEVE),
      // La Pagination - 5
      $strPagination,
      // Les Filtres - 6
      $strFiltres,
    );
    return $this->getRender($this->urlTemplatePageAdmin, $attributes);
  }



}
