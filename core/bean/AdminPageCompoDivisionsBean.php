<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdminPageCompoDivisionsBean
 * @author Hugues
 * @version 1.00.01
 * @since 1.00.01
 */
class AdminPageCompoDivisionsBean extends AdminPageBean
{
  protected $urlFragmentPagination = 'web/pages/admin/fragments/fragment-pagination.php';
  protected $urlTemplatePageAdmin = 'web/pages/admin/board-compo-divisions.php';
  /**
   * Class Constructor
   */
  public function __construct($urlParams=null)
  {
    parent::__construct();
    $this->title = 'Classes';
    $this->CompoDivisionServices = new CompoDivisionServices();
    if ($urlParams!=null && isset($urlParams['id'])) {
      $this->CompoDivision = $this->CompoDivisionServices->selectLocal($urlParams['id']);
    } else {
      $this->CompoDivision = new CompoDivision();
    }
    $this->urlParams = $urlParams;
    $this->arrIds = array();
  }
  public function getCompoDivision()
  { return $this->CompoDivision; }
  /**
   * @param array $urlParams
   * @return $Bean
   */
  public static function getStaticContentPage($urlParams)
  {
    ///////////////////////////////////////////:
    // Initialisation des valeurs par défaut
    $Bean = new AdminPageCompoDivisionsBean($urlParams);
    $msg = '';
    $initPanel = self::CST_CREATION;
    // Analyse de l'action éventuelle.
    if (isset($urlParams['filter_action'])) {
      $Bean = new AdminPageCompoDivisionsBean($urlParams);
    } elseif (isset($urlParams[self::CST_POSTACTION])) {
      $Bean = new AdminPageCompoDivisionsBean($urlParams);
      switch($urlParams[self::CST_POSTACTION]) {
        case 'Import' :
          // Exécution de l'import
          $Bean->import($notif, $msg);
          $Bean->CompoDivision = new CompoDivision();
        break;
        case 'Édition' :
          // Exécution de la mise à jour
          $Bean->getCompoDivision()->setAnneeScolaireId($urlParams[self::FIELD_ANNEESCOLAIRE_ID]);
          $Bean->getCompoDivision()->setDivisionId($urlParams[self::FIELD_DIVISION_ID]);
          $Bean->getCompoDivision()->setMatiereId($urlParams[self::FIELD_MATIERE_ID]);
          $Bean->getCompoDivision()->setEnseignantId($urlParams[self::FIELD_ENSEIGNANT_ID]);
          $Bean->getCompoDivision()->update($notif, $msg);
          $initPanel = self::CST_EDIT;
        break;
        case 'Création' :
          // Exécution de la création
          $Bean->getCompoDivision()->setAnneeScolaireId($urlParams[self::FIELD_ANNEESCOLAIRE_ID]);
          $Bean->getCompoDivision()->setDivisionId($urlParams[self::FIELD_DIVISION_ID]);
          $Bean->getCompoDivision()->setMatiereId($urlParams[self::FIELD_MATIERE_ID]);
          $Bean->getCompoDivision()->setEnseignantId($urlParams[self::FIELD_ENSEIGNANT_ID]);
          $Bean->getCompoDivision()->insert($notif, $msg);
          $Bean->CompoDivision = new CompoDivision();
        break;
        case 'Suppression' :
          // Exécution de la suppression unitaire ou groupée
          $Bean->delete($notif, $msg);
          $Bean->CompoDivision = new CompoDivision();
        break;
        case 'Bulk' :
          // Gestion des Actions groupées
          switch ($urlParams['action']) {
            case 'trash' :
              // Confirmation de la Suppression de masse
              if (empty($urlParams['post'])) {
                $msg = 'Suppressions impossibles : aucune entrée sélectionnée.';
                $notif = self::NOTIF_WARNING;
              } else {
                $initPanel = 'bulk-trash';
              }
            break;
            case 'export' :
              // Exécution de l'exportation
              if (empty($urlParams['post'])) {
                $msg = 'Export impossible : aucune entrée sélectionnée.';
                $notif = self::NOTIF_WARNING;
              } else {
                $msg = ExportActions::dealWithStaticExport(self::PAGE_COMPO_DIVISION, $urlParams['post']);
                $notif = self::NOTIF_SUCCESS;
              }
              $initPanel = 'bulk-export';
            break;
            default :
              // Erreur sur l'action groupée, non reconnue
              $notif = self::NOTIF_WARNING;
              $msg   = 'Action Bulk indéterminée : <strong>'.$urlParams['action'].'</strong>';
            break;
          }
        break;
        default :
          // Affichage des écrans simples : création ou édition
          $initPanel = $urlParams[self::CST_POSTACTION];
        break;
      }
    }
    if ($msg!='') {
      $Bean->createNotification($notif, $msg);
    }
    $Bean->initPanels($initPanel);
    return $Bean->getListingPage();
  }

  public function delete(&$notif, &$msg)
  {
    if (strpos($this->urlParams['id'], ',')!==false) {
      $this->CompoDivisionServices->deleteIn($this->urlParams['id']);
      $msg   = 'Suppressions réussies.';
      $notif = self::NOTIF_SUCCESS;
    } else {
      $this->getCompoDivision()->delete($notif, $msg);
      $this->createNotification($notif, $msg);
    }
  }


  public function createNotification($typeAlert, $msg)
  { $this->notifications = '<div class="alert alert-'.$typeAlert.' alert-dismissible fade show" role="alert">'.$msg.'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'; }

  /**
   * @return string
   */
  public function getListingPage()
  {
    /////////////////////////////////////////////////////////////////////////////
    // On récupère les filtres éventuels.
    $argFilters = array();
    $filterDivisionId = (isset($this->urlParams[self::FIELD_DIVISION_ID]) ? $this->urlParams[self::FIELD_DIVISION_ID] : '');
    $argFilters[self::FIELD_DIVISION_ID] = $filterDivisionId;
    $filterAnneeScolaireId = (isset($this->urlParams[self::FIELD_ANNEESCOLAIRE_ID]) ? $this->urlParams[self::FIELD_ANNEESCOLAIRE_ID] : '');
    $argFilters[self::FIELD_ANNEESCOLAIRE_ID] = $filterAnneeScolaireId;
    $filterMatiereId = (isset($this->urlParams[self::FIELD_MATIERE_ID]) ? $this->urlParams[self::FIELD_MATIERE_ID] : '');
    $argFilters[self::FIELD_MATIERE_ID] = $filterMatiereId;
    $filterEnseignantId = (isset($this->urlParams[self::FIELD_ENSEIGNANT_ID]) ? $this->urlParams[self::FIELD_ENSEIGNANT_ID] : '');
    $argFilters[self::FIELD_ENSEIGNANT_ID] = $filterEnseignantId;
    // Fin gestion des filtres

    //////////////////////////////////////////////////////////////////
    // On récupère tous les Compos et on construit la base de la pagination et on restreint l'affichage
    $strAdminRowsCompos = '';
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
      $strAdminRowsCompos = '<tr><td colspan="6"><em>Aucun résultat</em></td></tr>';
    } else {
      while (!empty($DisplayedCompoDivisions)) {
        $CompoDivision = array_shift($DisplayedCompoDivisions);
        $strAdminRowsCompos .= $CompoDivision->getBean()->getRowForAdminPage(false, $queryArg);
      }
    }
    //////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////////////
    // Construction des filtres utilisés
    $strFiltres = '';
    $AnneeScolaireBean = new AnneeScolaireBean();
    $strFiltres .= $AnneeScolaireBean->getSelect(self::FIELD_ANNEESCOLAIRE_ID, 'Toutes les années scolaires', $filterAnneeScolaireId);
    $DivisionBean = new DivisionBean();
    $strFiltres .= $DivisionBean->getSelect(self::FIELD_DIVISION_ID, 'Toutes les divisions', $filterDivisionId);
    $MatiereBean = new MatiereBean();
    $strFiltres .= $MatiereBean->getSelect(self::FIELD_MATIERE_ID, 'Toutes les matières', $filterMatiereId);
    $EnseignantBean = new EnseignantBean();
    $strFiltres .= $EnseignantBean->getSelect(self::FIELD_ENSEIGNANT_ID, 'Tous les enseignants', $filterEnseignantId);
    //////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////////////
    // Pagination
    $strPagination = $this->getPagination($queryArg, $post_status, $curPage, $nbPages, $nbElements);
    //////////////////////////////////////////////////////////////////

    $urlCancel = $this->getQueryArg($queryArg);

    $attributes = array(
      // Liste des élèves - 1
      $strAdminRowsCompos,
      // Titre du bloc de Création / Edition pour Compo Divisions - 2
      $this->cardCreationEditionTitre,
      // - 3
      $AnneeScolaireBean->getSelect(self::FIELD_ANNEESCOLAIRE_ID, self::CST_DEFAULT_SELECT, $this->CompoDivision->getAnneeScolaireId()),
      // - 4
      $DivisionBean->getSelect(self::FIELD_DIVISION_ID, self::CST_DEFAULT_SELECT, $this->CompoDivision->getDivisionId()),
      // - 5
      $MatiereBean->getSelect(self::FIELD_MATIERE_ID, self::CST_DEFAULT_SELECT, $this->CompoDivision->getMatiereId()),
      // - 6
      $EnseignantBean->getSelect(self::FIELD_ENSEIGNANT_ID, self::CST_DEFAULT_SELECT, $this->CompoDivision->getEnseignantId()),
      // Identifiant de l'élément sélectionné - 7
      $this->CompoDivision->getId(),
      // Url d'annulation - 8
      $urlCancel,
      // Show Card Creation/Edition - 9
      $this->showCardEditionCreation ? '' : ' hidden',
      // Show Card Suppression - 10
      $this->showCardSuppression ? '' : ' hidden',
      // Show Suppression simple - 11
      $this->showSuppressionSimple ? '' : ' hidden',
      // Show Suppression multiple - 12
      !$this->showSuppressionSimple ? '' : ' hidden',
      // Libellé suppressions mumtiples - 13
      $this->libellesMultiples,
      // Id de la Suppression - 14
      $this->ids,
      // La Pagination - 15
      $strPagination,
      // Les filtres pour le listing - 16
      $strFiltres,
      // Notifications - 17
      $this->notifications,
      /*
      // Nom de l'élève - 3
      '',//$this->CompoDivision->getNomEleve(),
      // Prénom de l'élève - 4
      '',//$this->Eleve->getPrenomEleve(),
      // Menu Division pour l'édition - 5
      '',//$DivisionBean->getSelect(self::FIELD_DIVISION_ID, self::CST_DEFAULT_SELECT, $this->Eleve->getDivisionId()),
      */
      '','','','','','','','','','','','','','','','','','',
      '','','','','','','','','','','','','','','','','','',
    );
    return $this->getRender($this->urlTemplatePageAdmin, $attributes);
  }
  /**
   * @param unknown $queryArg
   * @param unknown $post_status
   * @param unknown $curPage
   * @param unknown $nbPages
   * @param unknown $nbElements
   * @return string
   */
  protected function getPagination($queryArg, $post_status, $curPage, $nbPages, $nbElements)
  {
    ////////////////////////////////////////////////////////////////////////////
    // Lien vers la première page. Seulement si on n'est ni sur la première, ni sur la deuxième page.

    if ($curPage>=3) {
      $queryArg[self::WP_CURPAGE] = 1;
      $href = $this->getQueryArg($queryArg);
      $addClass = '';
    } else {
      $href = '#';
      $addClass = ' disabled';
    }
    $strToFirst = '<li class="page-item '.$addClass.'"><a class="page-link" href="'.$href.'">&laquo;</a></li>';

    ////////////////////////////////////////////////////////////////////////////
    // Lien vers la page précédente. Seulement si on n'est pas sur la première.
    if ($curPage>=2) {
      $queryArg[self::WP_CURPAGE] = $curPage-1;
      $href = $this->getQueryArg($queryArg);
      $addClass = '';
    } else {
      $href = '#';
      $addClass = ' disabled';
    }
    $strToPrevious = '<li class="page-item '.$addClass.'"><a class="page-link" href="'.$href.'">&lsaquo;</a></li>';

    ////////////////////////////////////////////////////////////////////////////
    // Lien vers la page suivante. Seulement si on n'est pas sur la dernière.
    if ($curPage<$nbPages) {
      $queryArg[self::WP_CURPAGE] = $curPage+1;
      $href = $this->getQueryArg($queryArg);
      $addClass = '';
    } else {
      $href = '#';
      $addClass = ' disabled';
    }
    $strToNext = '<li class="page-item '.$addClass.'"><a class="page-link" href="'.$href.'">&rsaquo;</a></li>';

    ////////////////////////////////////////////////////////////////////////////
    // Lien vers la dernière page. Seulement si on n'est pas sur la dernière, ni l'avant-dernière.
    if ($curPage<$nbPages-1) {
      $queryArg[self::WP_CURPAGE] = $nbPages;
      $href = $this->getQueryArg($queryArg);
      $addClass = '';
    } else {
      $href = '#';
      $addClass = ' disabled';
    }
    $strToLast = '<li class="page-item '.$addClass.'"><a class="page-link" href="'.$href.'">&raquo;</a></li>';

    $args = array(
      // Nombre d'éléments - 1
      $nbElements,
      // Lien vers la première page - 2
      $strToFirst,
      // Lien vers la page précédente - 3
      $strToPrevious,
      // Page courante - 4
      $curPage,
      // Nombre total de pages - 5
      $nbPages,
      // Lien vers la page suivante - 6
      $strToNext,
      // Lien vers la dernière page - 7
      $strToLast,
    );
    return $this->getRender($this->urlFragmentPagination, $args);
  }

  public function initPanels($action)
  {
    switch ($action) {
      case 'bulk-export' :
      case self::CST_CREATION :
        $this->cardCreationEditionTitre = 'Création';
        $this->showCardEditionCreation = true;
        $this->showCardSuppression     = false;
      break;
      case 'Création' :
      case 'Édition' :
      case self::CST_EDIT :
        $this->cardCreationEditionTitre = 'Édition';
        $this->showCardEditionCreation = true;
        $this->showCardSuppression     = false;
      break;
      case 'bulk-trash' :
        $this->cardCreationEditionTitre = '';
        $this->showSuppressionSimple    = false;
        $this->showCardEditionCreation  = false;
        $this->showCardSuppression      = true;
        $arrIds = array();
        $arrLabels = array();
        foreach($this->urlParams['post'] as $key=> $value) {
          $CompoDivision = $this->CompoDivisionServices->selectLocal($value);
          $arrLabels[] = $CompoDivision->getId();
          $arrIds[] = $value;
        }
        $this->arrIds                   = $arrIds;
        $this->ids                      = implode(',', $arrIds);
        $this->libellesMultiples        = implode(', ', $arrLabels);
      break;
      case self::CST_DELETE :
        $this->ids = $this->CompoDivision->getId();
        $this->cardCreationEditionTitre = 'Suppression';
        $this->showSuppressionSimple    = true;
        $this->showCardEditionCreation  = false;
        $this->showCardSuppression      = true;
      break;
      default :
        $this->cardCreationEditionTitre = 'WIP - '.$action.' -';
        $this->showCardEditionCreation = true;
        $this->showCardSuppression     = false;

      break;
    }
  }
}
