<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdminPageCompteRendusBean
 * @author Hugues
 * @version 1.21.07.16
 * @since 1.21.06.01
 */
class AdminPageCompteRendusBean extends AdminPageBean
{
  protected $urlTemplatePageCompteRenduAdmin = 'web/pages/admin/board-comptes-rendus.php';
  protected $urlTemplateForm = 'web/pages/admin/fragments/form-compte-rendu.php';

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
   * @version 1.21.07.16
   * @since 1.21.07.05
   */
  public function getObject()
  { return $this->LocalObject; }
  /**
   * Retourne le Service
   * @return CompteRenduService
   * @version 1.21.07.05
   * @since 1.21.07.05
   */
  public function getServices()
  { return $this->Services; }

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
   * @version 1.21.07.16
   * @since 1.21.07.16
   */
  private function dealWithSpecificCreationMode(&$notif, &$msg)
  {
    // Pas de contrôle car déjà fait à la soumission.
    // On a un Trimestre, ou tous (-1), on a une division, ou toutes (-1) et pour l'ensemble des couples trimestre/division, on va créer un CR.
    $CompteRendu = new CompteRendu();

    //////////////////////////////////////////////////////////////////////////////////////////
    // Récupération du Trimestre
    $trimestre = $this->urlParams[self::FIELD_TRIMESTRE];
    // Si aucun trimestre n'a été sélectionné, on créé un conseil de classe pour les 3 trimestres.
    $arrTrimestre = (($trimestre=='') ? array(1, 2, 3) : array($trimestre));
    //////////////////////////////////////////////////////////////////////////////////////////
    // Récupération de la Division
    $divisionId = $this->urlParams[self::FIELD_DIVISION_ID];
    // Si aucune Division n'a été sélectionnée, on créé un conseil de classe pour l'ensemble des Divisions.
    if ($divisionId =='') {
      $Divisions = $this->DivisionServices->getDivisionsWithFilters();
    } else {
      $Division = $this->DivisionServices->selectLocal($divisionId);
      $Divisions = array($Division);
    }
    //////////////////////////////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////////////////////////////////////
    // On parcourt les 2 tableaux et on créé un conseil de classe pour chaque couple.
    foreach ($arrTrimestre as $trimestre) {
      foreach ($Divisions as $Division) {
        // On vérifie que le conseil de classe n'a pas déjà été créé pour ce couple.
        $CpteRdus = $this->CompteRenduServices->getCompteRendusWithFilters(array(self::FIELD_TRIMESTRE=>$trimestre, self::FIELD_DIVISION_ID=>$Division->getId()));
        if (empty($CpteRdus)) {
          // Si ce n'est pas le cas, on le créé
          $CompteRendu->setField(self::FIELD_TRIMESTRE, $trimestre);
          $CompteRendu->setField(self::FIELD_DIVISION_ID, $Division->getId());
          $CompteRendu->setField(self::FIELD_STATUS, self::STATUS_FUTURE);
          $this->CompteRenduServices->insertLocal($CompteRendu);
        } else {
          // Sinon, on remonte une alerte, mais on ne stoppe pas le traitement.
          $notif = self::NOTIF_DANGER;
          $msg = 'Au moins un compte-rendu existant pour un couple (trimestre, division) a été rencontré. Création partielle ou nulle.';
        }
      }
    }
  }
  /**
   * @param array $urlParams
   * @return string
   * @version 1.21.07.16
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
        $this->dealWithSpecificCreationMode($notif, $msg);
      } else {
        $this->parseUrlParams($initPanel, $notif, $msg);
      }
    }

    ///////////////////////////////////////////
    // Si $msg est renseigné, on a une notification à afficher.
    if ($msg!='') {
      $this->createNotification($notif, $msg);
    }

    ///////////////////////////////////////////
    // On initialise les panneaux latéraux droit
    $this->msgConfirmDelete = sprintf(self::MSG_CONFIRM_SUPPR_COMPTE_RENDU, $this->LocalObject->getFullName());
    $this->tagConfirmDeleteMultiple = sprintf(self::MSG_CONFIRM_SUPPR_COMPTE_RENDUS, 'TODO');
    // On défini la liste déroulante pour le statut à éditer
    $attributes = array(
      self::ATTR_CLASS => self::CST_MD_SELECT,
      self::ATTR_NAME  => self::FIELD_STATUS,
    );
    $filterStatus = $this->LocalObject->getField(self::FIELD_STATUS);
    $strOptions  = $this->getLocalOption($this->getLibelleForStatus(self::STATUS_FUTURE), self::STATUS_FUTURE, $filterStatus);
    $strOptions .= $this->getLocalOption($this->getLibelleForStatus(self::STATUS_WORKING), self::STATUS_WORKING, $filterStatus);
    $strOptions .= $this->getLocalOption($this->getLibelleForStatus(self::STATUS_PENDING), self::STATUS_PENDING, $filterStatus);
    $strOptions .= $this->getLocalOption($this->getLibelleForStatus(self::STATUS_PUBLISHED), self::STATUS_PUBLISHED, $filterStatus);
    $strOptions .= $this->getLocalOption($this->getLibelleForStatus(self::STATUS_MAILED), self::STATUS_MAILED, $filterStatus);
    $strSelectStatut = $this->getBalise(self::TAG_SELECT, $strOptions, $attributes);
    $this->attributesFormEdit  = array(
      // Trimestre - 1
      $this->LocalObject->getTrimestre(),
      // Libellé de la Division - 2
      $this->LocalObject->getDivision()->getLabelDivision(),
      // Liste déroulante pour le Statut
      $strSelectStatut,
      // Identifiant de la Division - 4
      $this->LocalObject->getDivisionId(),
    );

    ///////////////////////////////////////////
    // Pour des raisons d'optimisation de code, on passe sur un ifelse.
    // Si les cas se multiplient, repasser sur un switch
    if ($initPanel==self::CST_CREATE) {
      $this->urlTemplateForm = 'web/pages/admin/fragments/card-compterendu-create.php';
      ///////////////////////////////////////////
      // On défini les listes déroulantes du panneau de création.
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

      ///////////////////////////////////////////
      $this->crudType = self::CST_CREATE;
      // Définition des attributs de la Card CRUD
      $this->attributesCardCRUD = array(
        // Select sur les Trimestres - 1
        $strSelectTrimestre,
        // Select sur les Divisions - 2
        $DivisionBean->getSelect(array('tag'=>self::FIELD_DIVISION_ID, 'label'=>'Toutes')),
        // Url d'annulation de l'opération - 3
        $this->getQueryArg(array(self::CST_ONGLET=>$this->subMenuValue)),
      );
    } else {
      $this->initPanels($initPanel);
    }
    ///////////////////////////////////////////:
    // On retourne le listing et les panneaux latéraux droit
    return $this->getListingPage();

  }

  /**
   * @version 1.21.07.16
   * @since 1.21.07.05
   */
  public function setLocalObject()
  {
    // On met à jour les attributs de l'objet
    $this->LocalObject->setField(self::FIELD_STATUS, $this->urlParams[self::FIELD_STATUS]);
  }
  /**
   * @version 1.21.07.05
   * @since 1.21.07.05
   */
  public function initLocalObject()
  { $this->LocalObject = new CompteRendu(); }

  /**
   * @return string
   * @version 1.21.07.16
   * @since 1.21.06.01
   */
  public function getListingPage()
  {
    /////////////////////////////////////////////////////////////////////////////
    // On récupère les données éventuelles des filtres.
    $argFilters = array();
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
    $strOptions .= $this->getLocalOption($this->getLibelleForStatus(self::STATUS_FUTURE), self::STATUS_FUTURE, $filterStatus);
    $strOptions .= $this->getLocalOption($this->getLibelleForStatus(self::STATUS_WORKING), self::STATUS_WORKING, $filterStatus);
    $strOptions .= $this->getLocalOption($this->getLibelleForStatus(self::STATUS_PENDING), self::STATUS_PENDING, $filterStatus);
    $strOptions .= $this->getLocalOption($this->getLibelleForStatus(self::STATUS_PUBLISHED), self::STATUS_PUBLISHED, $filterStatus);
    $strOptions .= $this->getLocalOption($this->getLibelleForStatus(self::STATUS_MAILED), self::STATUS_MAILED, $filterStatus);
    $strFiltres .= $this->getBalise(self::TAG_SELECT, $strOptions, $attributes);
    $strFiltres .= '<label for="statut">Statuts</label>';
    // Fin construction des filtres utilisés
    /////////////////////////////////////////////////////////////////////////////
    $statutValue = $this->LocalObject->getField(self::FIELD_STATUS);
    $strSelectStatut = $this->getBalise(self::TAG_SELECT, $strOptions, $attributes);

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
   * Ajout de l'interface CRUD avec une spécificité
   * @param string $crudType
   * @param array $attributes
   * @return string
   * @version 1.21.07.16
   * @since 1.21.06.06
   */
  public function getCardCRUD($crudType, $attributes=array())
  {
    // Pour des raisons d'optimisation du code, on est passé sur un ifelse.
    // Si les cas spécifiques venaient à se multiplier, repasser sur un switch
    if ($crudType==self::CST_CREATE) {
      // Vérifier le nombre d'éléments (3) dans $attributes pour matcher le Template ?
      $urlTemplateCard = 'web/pages/admin/fragments/card-compterendu-create.php';
    } else {
      return parent::getCardCRUD($crudType, $attributes);
    }
    return $this->getRender($urlTemplateCard, $attributes);
  }
}
