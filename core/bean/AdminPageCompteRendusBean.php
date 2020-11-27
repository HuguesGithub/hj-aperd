<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdminPageCompteRendusBean
 * @author Hugues
 * @version 1.00.01
 * @since 1.00.01
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
    $this->ClasseScolaireServices = new ClasseScolaireServices();
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
    if ($urlParams['type']=='generateCdc') {
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
        $ClasseScolaires = $this->ClasseScolaireServices->getClasseScolairesWithFilters();
        while (!empty($ClasseScolaires)) {
          $ClasseScolaire = array_shift($ClasseScolaires);
          $crKey = $this->CompteRenduServices->getUniqueGenKey();
          $request  = "INSERT INTO wp_14_aperd_compte_rendu (crKey, anneeScolaireId, trimestre, classeId, status) VALUES ('";
          $request .= $crKey."', ".$urlParams[self::FIELD_ANNEESCOLAIRE_ID].", ".$urlParams[self::FIELD_TRIMESTRE].", ".$ClasseScolaire->getId().", 'future');";
          MySQL::wpdbQuery($request);
          $this->msgErreur .= $request.'<br>';
        }
      }
    }
  }
  /**
   * @return string
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
    if (isset($urlParams[self::FIELD_CLASSE_ID]) && $urlParams[self::FIELD_CLASSE_ID]!=-1) {
      $filterClasseId = $urlParams[self::FIELD_CLASSE_ID];
      $args[self::FIELD_CLASSE_ID] = $filterClasseId;
    }
    if (isset($urlParams[self::FIELD_STATUS]) && $urlParams[self::FIELD_STATUS]!=-1) {
      $status = $urlParams[self::FIELD_STATUS];
      $args[self::FIELD_STATUS] = $status;
    }
    // Fin gestion des filtres

    /////////////////////////////////////////////////////////////////////////////
    $strFiltres = '';
    $AnneeScolaireBean = new AnneeScolaireBean();
    $strFiltres .= $AnneeScolaireBean->getSelect(self::FIELD_ANNEESCOLAIRE_ID, 'Toutes les années scolaires', $anneeScolaireId);
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
    $ClasseScolaireBean = new ClasseScolaireBean();
    $strFiltres .= $ClasseScolaireBean->getSelect(self::FIELD_CLASSE_ID, 'Toutes les classes', $filterClasseId);
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
    $CompteRendus = $this->CompteRenduServices->getCompteRendusWithFilters($args);
    while (!empty($CompteRendus)) {
      $CompteRendu = array_shift($CompteRendus);
      $Bean = $CompteRendu->getBean();
      $strCompteRendus .= $Bean->getRowForAdminPage();
    }

    $AnneeScolaireBean = new AnneeScolaireBean();
    /////////////////////////////////////////////////////////////////////////////
    // On restitue le template enrichi.
    $attibutes = array(
      // Un Select des Années Scolaires - 1
      $AnneeScolaireBean->getSelect(self::FIELD_ANNEESCOLAIRE_ID, self::CST_DEFAULT_SELECT),
      // Un Select des Trimestres - 2
      $strSelectTrimestre,
      // Le message d'erreur si la génération s'est mal passée - 3
      (!empty($this->msgErreur) ? '<div class="alert alert-danger" role="alert">'.$this->msgErreur.'</div>' : ''),
      // La liste des Comptes Rendus - 4
      $strCompteRendus,
      // Les filtres - 5
      $strFiltres,
    '','','','','','','','','','','','','','','','',
    );
    return $this->getRender($this->urlTemplatePageCompteRenduAdmin, $attibutes);
  }
}
