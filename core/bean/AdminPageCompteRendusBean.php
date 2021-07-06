<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdminPageCompteRendusBean
 * @author Hugues
 * @version 1.21.07.05
 * @since 1.21.06.01
 */
class AdminPageCompteRendusBean extends AdminPageBean
{
  protected $urlTemplatePageCompteRenduAdmin = 'web/pages/admin/board-comptes-rendus.php';
  /**
   * Class Constructor
   */
  public function __construct($urlParams=null)
  {
    parent::__construct();
    $this->DivisionServices = new DivisionServices();
    $this->CompteRenduServices = new CompteRenduServices();
    $this->Services       = new CompteRenduServices();
    // Initialisation du Compte Rendu sélectionné s'il y en a un.
    $this->LocalObject = ($urlParams!=null && isset($urlParams[self::FIELD_ID]) ? $this->CompteRenduServices->selectLocal($urlParams[self::FIELD_ID]) : new CompteRendu());
    // On stocke les paramètres
    $this->urlParams = $urlParams;
    // On prépare le stockage pour les ids multiples si existants.
    $this->arrIds = array();
    $this->subMenuValue = self::PAGE_COMPTE_RENDU;
  }
  /**
   * Retourne le Compte Rendu
   * @return CompteRendu
   * @version 1.21.07.05
   * @since 1.21.07.05
   */
  public function getObject()
  { return $this->CompteRendu; }
  /**
   * Retourne le Service
   * @return CompteRenduService
   * @version 1.21.07.05
   * @since 1.21.07.05
   */
  public function getServices()
  { return $this->CompteRenduService; }

  /**
   * @param array $urlParams
   * @return string
   * @version 1.21.07.05
   * @since 1.21.06.01
   */
  public static function getStaticContentPage($urlParams)
  {
    ///////////////////////////////////////////:
    // Initialisation des valeurs par défaut
    $Bean = new AdminPageCompteRendusBean($urlParams);
    return $Bean->getContentPage();
  }
  /**
   * @param array $urlParams
   * @return string
   * @version 1.21.07.05
   * @since 1.21.07.05
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
        if ($this->urlParams[self::CST_POSTACTION]==self::CST_BULK && $this->urlParams[self::CST_ACTION]==self::CST_CREATION) {
// Pas de contrôle car déjà fait à la soumission.
// On a une Année Scolaire. On a un Trimestre, ou tous (-1), on a une division, ou toutes (-1) et pour l'ensemble des couples trimestre/division, on va créer un CR.
          $CompteRendu = new CompteRendu();
          $anneeScolaireId = $this->urlParams[self::FIELD_ANNEESCOLAIRE_ID];
          $CompteRendu->setField(self::FIELD_ANNEESCOLAIRE_ID, $anneeScolaireId);

          $trimestre = $this->urlParams[self::FIELD_TRIMESTRE];
          if ($trimestre=='') {
            $arrTrimestre = array(1, 2, 3);
          } else {
            $arrTrimestre = array($trimestre);
          }

          $divisionId = $this->urlParams[self::FIELD_DIVISION_ID];
          if ($divisionId =='') {
            $Divisions = $this->DivisionServices->getDivisionsWithFilters();
          } else {
            $Division = $this->DivisionServices->selectLocal($divisionId);
            $Divisions = array($Division);
          }

    foreach ($arrTrimestre as $trimestre) {
              foreach ($Divisions as $Division) {
                  $CpteRdus = $this->CompteRenduServices->getCompteRendusWithFilters(array(self::FIELD_ANNEESCOLAIRE_ID=>$anneeScolaireId, self::FIELD_TRIMESTRE=>$trimestre, self::FIELD_DIVISION_ID=>$Division->getId()));
                  if (empty($CpteRdus)) {
                      $CompteRendu->setField(self::FIELD_TRIMESTRE, $trimestre);
                      $CompteRendu->setField(self::FIELD_DIVISION_ID, $Division->getId());
                      $this->CompteRenduServices->insertLocal($CompteRendu);
                  } else {
                      $notif = self::NOTIF_DANGER;
                      $msg = 'Au moins un compte-rendu existant pour un triplet (année scolaire, trimestre, division) a été rencontré. Création partielle ou nulle.';
                  }
              }
          }
        } else {
            $this->parseUrlParams($initPanel, $notif, $msg);
        }
    }

    ///////////////////////////////////////////
    // Si $msg est renseigné, on a une notification à afficher.
    if ($msg!='') {
      $this->createNotification($notif, $msg);
    }

    $AnneeScolaireBean = new AnneeScolaireBean();
    $DivisionBean = new DivisionBean();
    $attributes = array(
      self::ATTR_CLASS => self::CST_MD_SELECT,
      self::ATTR_NAME  => self::FIELD_TRIMESTRE,
    );
    $strOptions  = $this->getDefaultOption(-1, 'Tous');
    $strOptions .= $this->getLocalOption('T1', '1', -1);
    $strOptions .= $this->getLocalOption('T2', '2', -1);
    $strOptions .= $this->getLocalOption('T3', '3', -1);
    $strSelectTrimestre = $this->getBalise(self::TAG_SELECT, $strOptions, $attributes);

    ///////////////////////////////////////////:
    // On initialise les panneaux latéraux droit
    $this->msgConfirmDelete = ''; // TODO
    $this->tagConfirmDeleteMultiple = ''; //TODO
    $this->urlTemplateForm = 'web/pages/admin/fragments/card-compterendu-create.php';
    $this->attributesFormEdit  = array('','','','','','','','','','','','','','',);

    switch ($initPanel) {
      case self::CST_CREATE :
        $this->crudType = self::CST_CREATE;
        // Définition des attributs de la Card CRUD
        $this->attributesCardCRUD = array(
        // Select sur les Années Scolaires - 1
          $AnneeScolaireBean->getSelect(array('tag'=>self::FIELD_ANNEESCOLAIRE_ID, 'required'=>'')),
        // Select sur les Trimestres - 2
          $strSelectTrimestre,
        // Select sur les Divisions - 3
          $DivisionBean->getSelect(array('tag'=>self::FIELD_DIVISION_ID, 'label'=>'Toutes')),
  // Url d'annulation de l'opération - 4
          $this->getQueryArg(array(self::CST_ONGLET=>$this->subMenuValue)),
        );
      break;
      default :
  $this->initPanels($initPanel);
      break;
    }
    ///////////////////////////////////////////:
    // On retourne le listing et les panneaux latéraux droit
    return $this->getListingPage();

  }

  /**
   * @version 1.21.07.05
   * @since 1.21.07.05
   */
  public function setLocalObject()
  {
    // On met à jour les attributs de l'objet
  }
  /**
   * @version 1.21.07.05
   * @since 1.21.07.05
   */
  public function initLocalObject()
  { $this->LocalObject = new CompteRendu(); }

  /**
   * @return string
   */
  public function getListingPage()
  {
    /////////////////////////////////////////////////////////////////////////////
    // On récupère les données éventuelles des filtres.
    $argFilters = array();
    // Les Années Scolaires
    $filterAnneeScolaireId = (isset($this->urlParams[self::FIELD_ANNEESCOLAIRE_ID]) ? $this->urlParams[self::FIELD_ANNEESCOLAIRE_ID] : '');
    $argFilters[self::FIELD_ANNEESCOLAIRE_ID] = $filterAnneeScolaireId ;
    // Les Trimestres
    $filterTrimestre = (isset($this->urlParams[self::FIELD_TRIMESTRE]) ? $this->urlParams[self::FIELD_TRIMESTRE] : '');
    $argFilters[self::FIELD_TRIMESTRE] = $filterTrimestre;
  // La Division
    $filterDivisionId = (isset($this->urlParams[self::FIELD_DIVISION_ID]) ? $this->urlParams[self::FIELD_DIVISION_ID] : '');
    $argFilters[self::FIELD_DIVISION_ID] = $filterDivisionId;
  // Le Statut
    $filterStatus = (isset($this->urlParams[self::FIELD_STATUS]) ? $this->urlParams[self::FIELD_STATUS] : '');
    $argFilters[self::FIELD_STATUS] = $filterStatus;
    // Fin récupèration des données éventuelles des filtres
    //////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////////////
    // Construction des filtres utilisés
    $strFiltres = '';
    // L'Année Scolaire
    $AnneeScolaireBean = new AnneeScolaireBean();
    $argAsSelect = array(
      'tag'        => self::FIELD_ANNEESCOLAIRE_ID,
      'selectedId' => $filterAnneeScolaireId,
    );
    $strFiltres .= $AnneeScolaireBean->getSelect($argAsSelect);
    $strFiltres .= '<label for="anneeScolaireId">Années Scolaires</label>';
    $strFiltres .= '</div></div><div class="col-md"><div class="form-floating">';
    /////////////////////////////////////////////////////////////////////////////
  // Les Trimestres
    $attributes = array(
      self::ATTR_CLASS => self::CST_MD_SELECT,
      self::ATTR_NAME  => self::FIELD_TRIMESTRE,
    );
    $strOptions  = $this->getDefaultOption(-1, 'Choisir...');
    $strOptions .= $this->getLocalOption('T1', '1', $filterTrimestre);
    $strOptions .= $this->getLocalOption('T2', '2', $filterTrimestre);
    $strOptions .= $this->getLocalOption('T3', '3', $filterTrimestre);
    $strFiltres .= $this->getBalise(self::TAG_SELECT, $strOptions, $attributes);
    $strFiltres .= '<label for="trimestre">Trimestres</label>';
    $strFiltres .= '</div></div><div class="col-md"><div class="form-floating">';
    /////////////////////////////////////////////////////////////////////////////
  // La Division
    $DivisionBean = new DivisionBean();
    $argDivisionSelect = array(
      'tag'        => self::FIELD_DIVISION_ID,
      'selectedId' => $filterDivisionId,
    );
    $strFiltres .= $DivisionBean->getSelect($argDivisionSelect);
    $strFiltres .= '<label for="divisionId">Divisions</label>';
    $strFiltres .= '</div></div><div class="col-md"><div class="form-floating">';
    /////////////////////////////////////////////////////////////////////////////
  // Le Statut
    $attributes = array(
      self::ATTR_CLASS => self::CST_MD_SELECT,
      self::ATTR_NAME  => self::FIELD_STATUS,
    );
    $strOptions  = $this->getDefaultOption(-1, 'Choisir...');
    $strOptions .= $this->getLocalOption('Futur', 'future', $filterStatus);
    $strOptions .= $this->getLocalOption('Publié', 'published', $filterStatus);
    $strOptions .= $this->getLocalOption('Archivé', 'archived', $filterStatus);
    $strFiltres .= $this->getBalise(self::TAG_SELECT, $strOptions, $attributes);
    $strFiltres .= '<label for="statut">Statuts</label>';
    // Fin construction des filtres utilisés
    /////////////////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////////////
    // On récupère tous les Comptes Rendus et on construit la base de la pagination et on restreint l'affichage
    $nbPerPage = 10;
    $orderby = $this->initVar(self::WP_ORDERBY, self::FIELD_DIVISION_ID);
    $order = $this->initVar(self::WP_ORDER, self::ORDER_ASC);
    $ComptesRendus = $this->CompteRenduServices->getCompteRendusWithFilters($argFilters, $orderby, $order);
    $nbElements = count($ComptesRendus);
    $nbPages = ceil($nbElements/$nbPerPage);
    $curPage = $this->initVar(self::WP_CURPAGE, 1);
    $curPage = max(1, min($curPage, $nbPages));
    $queryArg = array_merge(
      array(
        self::CST_ONGLET => self::PAGE_COMPTE_RENDU,
        self::WP_ORDERBY => $orderby,
        self::WP_ORDER   => $order,
        self::WP_CURPAGE => $curPage,
      ),
      $argFilters,
    );

    $DisplayedComptesRendus = array_slice($ComptesRendus, ($curPage-1)*$nbPerPage, $nbPerPage);
    if (empty($DisplayedComptesRendus)) {
      $strRows = '<tr><td colspan="7"><em>Aucun résultat</em></td></tr>';
    } else {
      $strRows = '';
      while (!empty($DisplayedComptesRendus)) {
        $CompteRendu = array_shift($DisplayedComptesRendus);
        $strRows .= $CompteRendu->getBean()->getRowForAdminPage(in_array($CompteRendu->getId(), $this->arrIds), $queryArg);
      }
    }
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
      '',//$this->getCardImport(self::PAGE_COMPTE_RENDU),
      // La Pagination - 5
      $strPagination,
      // Les Filtres - 6
      $strFiltres,
    );
    return $this->getRender($this->urlTemplatePageCompteRenduAdmin, $attributes);
  }

  /**
   * Ajout de l'interface CRUD
   * @param string $importType
   * @return string
   * @version 1.21.06.06
   * @since 1.21.06.06
   */
  public function getCardCRUD($crudType, $attributes=array())
  {
    switch ($crudType) {
      case self::CST_CREATE :
      // Vérifier le nombre d'éléments (3) dans $attributes pour matcher le Template ?
        $urlTemplateCard = 'web/pages/admin/fragments/card-compterendu-create.php';
      break;
      default :
        return parent::getCardCRUD($crudType, $attributes);
      break;
    }
    return $this->getRender($urlTemplateCard, $attributes);
  }
}
