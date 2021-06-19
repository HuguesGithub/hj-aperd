<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdminPageElevesBean
 * @author Hugues
 * @version 1.21.06.17
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
    if ($urlParams!=null && isset($urlParams[self::FIELD_ID])) {
      $this->Eleve = $this->EleveServices->selectLocal($urlParams[self::FIELD_ID]);
    } else {
      $this->Eleve = new Eleve();
    }
    // On stocke les paramètres
    $this->urlParams = $urlParams;
    // On prépare le stockage pour les ids multiples si existants.
    $this->arrIds = array();
    $this->AnneeScolaireServices = new AnneeScolaireServices();
    $this->DivisionServices = new DivisionServices();
  }
  /**
   * Retourne l'Elève
   * @return Eleve
   * @version 1.21.06.11
   * @since 1.21.06.01
   */
  public function getObject()
  { return $this->Eleve; }
  /**
   * Retourne le Service
   * @return AdulteService
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  public function getServices()
  { return $this->EleveServices; }

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
   * @version 1.21.06.11
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
      switch ($this->urlParams[self::CST_POSTACTION]) {
        case self::CST_CREATION :
          // Exécution de la création
          $this->Eleve->setNomEleve($this->urlParams[self::FIELD_NOMELEVE]);
          $this->Eleve->setPrenomEleve($this->urlParams[self::FIELD_PRENOMELEVE]);
          $this->Eleve->setDivisionId($this->urlParams[self::FIELD_DIVISION_ID]);
          $this->Eleve->setDelegue(isset($this->urlParams[self::FIELD_DELEGUE]));
          $this->Eleve->insert($notif, $msg);
          $this->Eleve = new Eleve();
        break;
        case self::CST_EDITION :
          // Exécution de la création
          $this->Eleve->setNomEleve($this->urlParams[self::FIELD_NOMELEVE]);
          $this->Eleve->setPrenomEleve($this->urlParams[self::FIELD_PRENOMELEVE]);
          $this->Eleve->setDivisionId($this->urlParams[self::FIELD_DIVISION_ID]);
          $this->Eleve->setDelegue(isset($this->urlParams[self::FIELD_DELEGUE]));
          $this->Eleve->update($notif, $msg);
          $initPanel = self::CST_EDIT;
        break;
        case self::CST_SUPPRESSION :
          // Exécution de la suppression unitaire ou groupée
          $this->delete($notif, $msg);
          $this->Eleve = new Eleve();
        break;
        case self::CST_IMPORT :
          // Exécution de l'import
          $this->import($notif, $msg);
          $this->Eleve = new Eleve();
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
                $msg = ExportActions::dealWithStaticExport(self::PAGE_ELEVE, $this->urlParams[self::CST_POST]);
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
    $this->initPanels($initPanel);
    ///////////////////////////////////////////:
    // On retourne le listing et les panneaux latéraux droit
    return $this->getListingPage();
  }

  /**
   * Intialise les panneaux latéraux à afficher
   * @param string $action
   * @version 1.21.06.11
   * @since 1.21.06.01
   */
  public function initPanels($action)
  {
    switch ($action) {
      case self::CST_DELETE :
        $this->crudType = self::CST_DELETE;
        // Définition des attributs de la Card CRUD
        $this->attributesCardCRUD = array(
          // Message de confirmation à afficher - 1
          sprintf(self::MSG_CONFIRM_SUPPR_ELEVE, $this->Eleve->getNomComplet()),
          // Id de l'objet ou des objets à supprimer - 2
          $this->Eleve->getId(),
          // Url d'annulation de l'opération - 3
          $this->getQueryArg(array(self::CST_ONGLET=>self::PAGE_ELEVE)),
        );
      break;
      case self::CST_CREATION :
      case self::CST_EDITION  :
      case self::CST_EDIT     :
        $this->crudType = self::CST_EDIT;
        $attributesForm  = array(
          // Nom de l'Elève - 1
          $this->Eleve->getNomEleve(),
          // Prénom de l'Eleve - 2
          $this->Eleve->getPrenomEleve(),
          // Division de l'Eleve - 3
          $this->Eleve->getDivision()->getBean()->getSelect(self::FIELD_DIVISION_ID, self::CST_DEFAULT_SELECT, $this->Eleve->getDivisionId()),
          // Est délégué ? - 4
          ($this->Eleve->isDelegue() ? self::CST_BLANK.self::CST_CHECKED : ''),
        ) ;
        // Définition des attributs de la Card CRUD
        $this->attributesCardCRUD = array(
          // Contenu du Formulaire - 1
          $this->getRender($this->urlTemplateForm, $attributesForm),
          // Id de l'objet ou des objets à supprimer - 2
          $this->Eleve->getId(),
          // Url d'annulation de l'opération - 3
          $this->getQueryArg(array(self::CST_ONGLET=>self::PAGE_ELEVE)),
        );
      break;
      case self::CST_BULK_TRASH :
        $this->crudType = self::CST_DELETE;
        // Construction des listings suite à la sélection multiple.
        $arrIds = array();
        $arrLabels = array();
        foreach($this->urlParams[self::CST_POST] as $key=> $value) {
          $Eleve = $this->EleveServices->selectLocal($value);
          $arrLabels[] = $Eleve->getNomComplet();
          $arrIds[] = $value;
        }
        $this->arrIds                   = $arrIds;
        // Définition des attributs de la Card CRUD
        $this->attributesCardCRUD = array(
          // Message de confirmation à afficher - 1
          sprintf(self::MSG_CONFIRM_SUPPR_ELEVES, implode(', ', $arrLabels)),
          // Id de l'objet ou des objets à supprimer - 2
          implode(',', $arrIds),
          // Url d'annulation de l'opération - 3
          $this->getQueryArg(array(self::CST_ONGLET=>self::PAGE_ELEVE)),
        );
      break;
      case self::CST_BULK_EXPORT :
        foreach($this->urlParams[self::CST_POST] as $key=> $value) {
          $arrIds[] = $value;
        }
        $this->arrIds                   = $arrIds;
      case self::CST_CREATE :
      default :
        $this->crudType = self::CST_CREATE;
        $attributesForm  = array('', '', $this->Eleve->getDivision()->getBean()->getSelect(self::FIELD_DIVISION_ID), '');
        // Définition des attributs de la Card CRUD
        $this->attributesCardCRUD = array(
          // Contenu du Formulaire - 1
          $this->getRender($this->urlTemplateForm, $attributesForm),
          // Url d'annulation de l'opération - 2
          $this->getQueryArg(array(self::CST_ONGLET=>self::PAGE_ELEVE)),
        );
      break;
    }
  }

  /**
   * Gestion de l'affichage de la page.
   * @return string
   * @version 1.21.06.12
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
    $strAdminRowsEleves = '';
    $nbPerPage = 10;
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
      $strRows = '<tr><td colspan="5"><em>Aucun résultat</em></td></tr>';
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
    $strFiltres .= $DivisionBean->getSelect(self::FIELD_DIVISION_ID, self::CST_DEFAULT_SELECT, $filterDivisionId);
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
