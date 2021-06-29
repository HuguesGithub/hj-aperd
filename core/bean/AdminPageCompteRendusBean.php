<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdminPageCompteRendusBean
 * @author Hugues
 * @version 1.21.06.29
 * @since 1.21.06.01
 */
class AdminPageCompteRendusBean extends AdminPageBean
{
  protected $urlTemplatePageCompteRenduAdmin = 'web/pages/admin/board-comptes-rendus.php';
  /**
   * Class Constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->title = 'Comptes-Rendus';
    $this->DivisionServices = new DivisionServices();
    $this->CompteRenduServices = new CompteRenduServices();
  }
  /**
   * @param array $urlParams
   * @return $Bean
   */
  public static function getStaticContentPage($urlParams)
  {
    $Bean = new AdminPageCompteRendusBean();
    if (isset($urlParams[self::CST_POSTACTION])) {
      $Bean->dealWithPostAction($urlParams);
    }
    return $Bean->getListingPage($urlParams);
  }
  public function dealWithPostAction($urlParams)
  {
    $this->msgErreur = '';
    $id = $urlParams[self::FIELD_ID];
    if ($urlParams[self::CST_POSTACTION] == 'edit') {
      $this->CompteRendu = $this->CompteRenduServices->selectLocal($id);
    } elseif ($urlParams[self::CST_POSTACTION] == 'Edition') {
      $this->CompteRendu = $this->CompteRenduServices->selectLocal($id);
      $this->CompteRendu->setDateConseil($urlParams[self::FIELD_DATECONSEIL]);
      $this->CompteRendu->setAdministrationId($urlParams[self::FIELD_ADMINISTRATION_ID]);
      $this->CompteRenduServices->updateLocal($this->CompteRendu);
    } elseif ($urlParams[self::CST_POSTACTION] == 'Appliquer') {
      // Soit on est sur le point de Trasher des données, soit on est sur le point de passer des données au statut definitive
      $ids = $urlParams['post'];
      while (!empty($ids)) {
        $id = array_shift($ids);
        $CompteRendu = $this->CompteRenduServices->selectLocal($id);
        if ($urlParams['action']=='trash' && $CompteRendu->getStatus()=='archived') {
          $this->CompteRenduServices->deleteLocal($CompteRendu);
        } elseif ($urlParams['action']=='definitive' && $CompteRendu->getStatus()=='published') {
          $CompteRendu->setField(self::FIELD_STATUS, 'definitive');
          $this->CompteRenduServices->updateLocal($CompteRendu);
        } elseif ($urlParams['action']=='published' && $CompteRendu->getStatus()=='definitive') {
          $CompteRendu->setField(self::FIELD_STATUS, 'published');
          $this->CompteRenduServices->updateLocal($CompteRendu);
        }
      }
    } elseif ($urlParams['type']=='generateCdc') {
      $this->dealWithGenerateCdcAction($urlParams);
    }
  }
  private function dealWithGenerateCdcAction($urlParams)
  {
    if (empty($urlParams[self::FIELD_ANNEESCOLAIRE_ID]) || empty($urlParams[self::FIELD_TRIMESTRE])) {
      $this->msgErreur .= 'Il est nécessaire de renseigner l\'année scolaire et le trimestre.<br>';
    } else {
      $attributes = array(
        self::FIELD_ANNEESCOLAIRE_ID => $urlParams[self::FIELD_ANNEESCOLAIRE_ID],
        self::FIELD_TRIMESTRE        => $urlParams[self::FIELD_TRIMESTRE],
      );
      $CompteRendus = $this->CompteRenduServices->getCompteRendusWithFilters($attributes);
      if (!empty($CompteRendus)) {
        $this->msgErreur .= 'Il existe déjà des comptes-rendus pour cette année scolaire et ce trimestre..<br>';
      } else {
        $ClasseScolaires = $this->DivisionServices->getDivisionsWithFilters();
        while (!empty($ClasseScolaires)) {
          $ClasseScolaire = array_shift($ClasseScolaires);
          $crKey = $this->CompteRenduServices->getUniqueGenKey();
          $request  = "INSERT INTO wp_14_aperd_compte_rendu (crKey, anneeScolaireId, trimestre, divisionId, status) VALUES ('";
          $request .= $crKey."', ".$urlParams[self::FIELD_ANNEESCOLAIRE_ID].", ".$urlParams[self::FIELD_TRIMESTRE].", ".$ClasseScolaire->getId().", 'future');";
          MySQL::wpdbQuery($request);
          $this->msgErreur .= $request.'<br>';
        }
      }
    }
  }
  /**
   * @return string
   * @version 1.21.06.29
   * @since 1.21.06.01
   */
  public function getListingPage($urlParams)
  {
    /////////////////////////////////////////////////////////////////////////////
    // Filtres disponibles
    $args = array();
    if (isset($urlParams[self::FIELD_ANNEESCOLAIRE_ID]) && $urlParams[self::FIELD_ANNEESCOLAIRE_ID]!=-1) {
      $anneeScolaireId = $urlParams[self::FIELD_ANNEESCOLAIRE_ID];
      $args[self::FIELD_ANNEESCOLAIRE_ID] = $anneeScolaireId;
    }
    if (isset($urlParams[self::FIELD_TRIMESTRE]) && $urlParams[self::FIELD_TRIMESTRE]!=-1) {
      $trimestre = $urlParams[self::FIELD_TRIMESTRE];
      $args[self::FIELD_TRIMESTRE] = $trimestre;
    }
    if (isset($urlParams[self::FIELD_DIVISION_ID]) && $urlParams[self::FIELD_DIVISION_ID]!=-1) {
      $filterClasseId = $urlParams[self::FIELD_DIVISION_ID];
      $args[self::FIELD_DIVISION_ID] = $filterClasseId;
    }
    if (isset($urlParams[self::FIELD_STATUS]) && $urlParams[self::FIELD_STATUS]!=-1) {
      $status = $urlParams[self::FIELD_STATUS];
      $args[self::FIELD_STATUS] = $status;
    }
    // Fin gestion des filtres

    /////////////////////////////////////////////////////////////////////////////
    $strFiltres = '';
    $AnneeScolaireBean = new AnneeScolaireBean();
    $argAsSelect = array(
      'tag'        => self::FIELD_ANNEESCOLAIRE_ID,
      'label'      => 'Toutes les années scolaires',
      'selectedId' => $anneeScolaireId,
    );
    $strFiltres .= $AnneeScolaireBean->getSelect($argAsSelect);
    /////////////////////////////////////////////////////////////////////////////
    $attributes = array(
      self::ATTR_CLASS => self::CST_MD_SELECT,
      self::ATTR_NAME  => self::FIELD_TRIMESTRE,
    );
    $strOptions  = $this->getDefaultOption(-1, 'Tous les trimestres');
    $strOptions .= $this->getLocalOption('T1', '1', $trimestre);
    $strOptions .= $this->getLocalOption('T2', '2', $trimestre);
    $strOptions .= $this->getLocalOption('T3', '3', $trimestre);
    $strFiltres .= $this->getBalise(self::TAG_SELECT, $strOptions, $attributes);
    /////////////////////////////////////////////////////////////////////////////
    $DivisionBean = new DivisionBean();
    $argDivisionSelect = array(
      'tag'        => self::FIELD_DIVISION_ID,
      'label'      => 'Toutes les divisions',
      'selectedId' => $filterClasseId,
    );
    $strFiltres .= $DivisionBean->getSelect($argDivisionSelect);
    /////////////////////////////////////////////////////////////////////////////
    $attributes = array(
      self::ATTR_CLASS => self::CST_MD_SELECT,
      self::ATTR_NAME  => self::FIELD_STATUS,
    );
    $strOptions  = $this->getDefaultOption(-1, 'Tous les statuts');
    $strOptions .= $this->getLocalOption('Futur', 'future', $status);
    $strOptions .= $this->getLocalOption('Publié', 'published', $status);
    $strOptions .= $this->getLocalOption('Archivé', 'archived', $status);
    $strFiltres .= $this->getBalise(self::TAG_SELECT, $strOptions, $attributes);
    /////////////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////////////
    // On construit le Select des Trimestres
    $attributes = array(
      self::ATTR_CLASS => self::CST_MD_SELECT,
      self::ATTR_NAME  => 'trimestre',
    );
    $strOptions  = $this->getDefaultOption(-1, self::CST_DEFAULT_SELECT);
    $strOptions .= $this->getLocalOption('T1', '1', -1);
    $strOptions .= $this->getLocalOption('T2', '2', -1);
    $strOptions .= $this->getLocalOption('T3', '3', -1);
    $strSelectTrimestre = $this->getBalise(self::TAG_SELECT, $strOptions, $attributes);

    // On récupère l'ensemble des Compte-rendus
    $strCompteRendus = '';
    $Bean = new CompteRenduBean();
    $CompteRendus = $this->CompteRenduServices->getCompteRendusWithFilters($args);
    if (!empty($CompteRendus)) {
      while (!empty($CompteRendus)) {
        $CompteRendu = array_shift($CompteRendus);
        $Bean = $CompteRendu->getBean();
        $strCompteRendus .= $Bean->getRowForAdminPage($args);
      }
    } else {
      $strCompteRendus = '<tr><td colspan="7"><em>Aucun résultat</em></td></tr>';
    }

    $urlCancel = $Bean->getQueryArg(array(self::CST_ONGLET=>self::PAGE_COMPTE_RENDU));

    $AnneeScolaireBean = new AnneeScolaireBean();
    $AdministrationBean = new AdministrationBean();
    /////////////////////////////////////////////////////////////////////////////
    // On restitue le template enrichi.
    $attibutes = array(
      // Un Select des Années Scolaires - 1
      $AnneeScolaireBean->getSelect(array('tag'=>self::FIELD_ANNEESCOLAIRE_ID)),
      // Un Select des Trimestres - 2
      $strSelectTrimestre,
      // Le message d'erreur si la génération s'est mal passée - 3
      (!empty($this->msgErreur) ? '<div class="alert alert-danger" role="alert">'.$this->msgErreur.'</div>' : ''),
      // La liste des Comptes Rendus - 4
      $strCompteRendus,
      // Les filtres - 5
      $strFiltres,
      // Titre du bloc de Création / Edition - 6
      $this->CompteRendu==null ? self::CST_CREATION : 'Edition',
      // Année Scolaire - 7
      $this->CompteRendu==null ? '' : $this->CompteRendu->getAnneeScolaire()->getAnneeScolaire(),
      // Trimestre - 8
      $this->CompteRendu==null ? '' : 'T'.$this->CompteRendu->getTrimestre(),
      // Division - 9
      $this->CompteRendu==null ? '' : $this->CompteRendu->getDivision()->getLabelDivision(),
      // crKey - 10
      $this->CompteRendu==null ? '' : $this->CompteRendu->getCrKey(),
      // Id - 11
      $this->CompteRendu==null ? '' : $this->CompteRendu->getId(),
      // Url pour annuler - 12
      $urlCancel,
      // Date du conseil de classe - 13
      $this->CompteRendu==null ? '' : $this->CompteRendu->getDateConseil(),
      // Président de séance (c'est un select) - 14
      $AdministrationBean->getSelect(array('tag'=>self::FIELD_ADMINISTRATION_ID, 'selectedId'=>($this->CompteRendu==null ? -1 : $this->CompteRendu->getAdministrationId()))),
      '','','','','','','','','','','','','','','','',
    );
    return $this->getRender($this->urlTemplatePageCompteRenduAdmin, $attibutes);
  }
}
