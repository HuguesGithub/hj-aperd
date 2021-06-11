<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdminPageConfigurationsBean
 * @author Hugues
 * @version 1.00.01
 * @since 1.00.01
 */
class AdminPageConfigurationsBean extends AdminPageBean
{
  protected $urlTemplatePageConfigurationAdmin = 'web/pages/admin/board-configurations.php';
  /**
   * Class Constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->title = 'Configurations';
    $this->AdministrationServices = new AdministrationServices();
    $this->AnneeScolaireServices = new AnneeScolaireServices();
  }
  /**
   * @param array $urlParams
   * @return $Bean
   */
  public static function getStaticContentPage($urlParams)
  {
    $Bean = new AdminPageConfigurationsBean();
    if (isset($urlParams[self::CST_POSTACTION])) {
      $Bean->dealWithPostAction($urlParams);
    }
    return $Bean->getListingPage($urlParams);
  }
  public function dealWithPostAction($urlParams)
  {
    switch ($urlParams['type']) {
      case 'Administration' :
        $this->dealWithAdministrationAction($urlParams);
      break;
      case 'AnneeScolaire' :
        $this->dealWithAnneeScolaireAction($urlParams);
      break;
      default :
      break;
    }
  }
  private function dealWithAdministrationAction($urlParams)
  {
    $id = $urlParams[self::FIELD_ID];
    if ($urlParams[self::CST_POSTACTION] == 'edit') {
      $this->Administration = $this->AdministrationServices->selectLocal($id);
    } elseif ($urlParams[self::CST_POSTACTION] == 'Edition') {
      $this->Administration = $this->AdministrationServices->selectLocal($id);
      $this->Administration->setNomTitulaire($urlParams[self::FIELD_NOMTITULAIRE]);
      $this->Administration->setLabelPoste($urlParams[self::FIELD_LABELPOSTE]);
      $this->AdministrationServices->updateLocal($this->Administration);
    } elseif ($urlParams[self::CST_POSTACTION]==self::CST_CREATION) {
      $this->Administration = new Administration();
      $this->Administration->setNomTitulaire($urlParams[self::FIELD_NOMTITULAIRE]);
      $this->Administration->setLabelPoste($urlParams[self::FIELD_LABELPOSTE]);
      $this->AdministrationServices->insertLocal($this->Administration);
    }
  }
  private function dealWithAnneeScolaireAction($urlParams)
  {
    $id = $urlParams[self::FIELD_ID];
    if ($urlParams[self::CST_POSTACTION] == 'edit') {
      $this->AnneeScolaire = $this->AnneeScolaireServices->selectLocal($id);
    } elseif ($urlParams[self::CST_POSTACTION] == 'Edition') {
      $this->AnneeScolaire = $this->AnneeScolaireServices->selectLocal($id);
      $this->AnneeScolaire->setAnneeScolaire($urlParams[self::FIELD_ANNEESCOLAIRE]);
      $this->AnneeScolaireServices->updateLocal($this->AnneeScolaire);
    } elseif ($urlParams[self::CST_POSTACTION]==self::CST_CREATION) {
      $this->AnneeScolaire = new AnneeScolaire();
      $this->AnneeScolaire->setAnneeScolaire($urlParams[self::FIELD_ANNEESCOLAIRE]);
      $this->AnneeScolaireServices->insertLocal($this->AnneeScolaire);
    }
  }
  /**
   * @return string
   */
  public function getListingPage($urlParams)
  {
    /////////////////////////////////////////////////////////////////////////////
    // Onglet Administratif.
    $strAdminRows = '';
    $Administratifs = $this->AdministrationServices->getAdministrationsWithFilters();
    foreach ($Administratifs as $Administratif) {
      $Bean = $Administratif->getBean();
      $strAdminRows .= $Bean->getRowForAdminPage();
    }
    $urlCancelAdministration = $Bean->getQueryArg(array(self::CST_ONGLET=>self::PAGE_CONFIGURATION, 'type'=>'Administration'));
    // Onglet Année Scolaire
    $strAdminRowsAnneeScolaire = '';
    $AnneeScolaires = $this->AnneeScolaireServices->getAnneeScolairesWithFilters();
    while (!empty($AnneeScolaires)) {
      $AnneeScolaire = array_shift($AnneeScolaires);
      $Bean = $AnneeScolaire->getBean();
      $strAdminRowsAnneeScolaire .= $Bean->getRowForAdminPage();
    }
    $urlCancelAnneeScolaire = $Bean->getQueryArg(array(self::CST_ONGLET=>self::PAGE_CONFIGURATION, 'type'=>'AnneeScolaire'));

    /////////////////////////////////////////////////////////////////////////////
    // On restitue le template enrichi.
    $attibutes = array(
      // Liste des Administratifs - 1
      $strAdminRows,
      // Titre du bloc de Création / Edition - 2
      $this->Administration==null ? self::CST_CREATION : 'Edition',
      // Valeur du Nom du Titulaire sélectionné - 3
      $this->Administration==null ? '' : $this->Administration->getNomTitulaire(),
      // Libellé du Poste - 4
      $this->Administration==null ? '' : $this->Administration->getLabelPoste(),
      // Identifiant de l'élément sélectionné - 5
      $this->Administration==null ? '' : $this->Administration->getId(),
      // Url pour Annuler l'action - 6
      $urlCancelAdministration,
      // Onglet actuel - 7
      'tab'.(isset($urlParams['type']) ? $urlParams['type'] : 'Administration'),
      // Liste des Années Scolaires - 8
      $strAdminRowsAnneeScolaire,
      // Titre du bloc de Création / Edition pour Année Scolaire - 9
      $this->AnneeScolaire==null ? self::CST_CREATION : 'Edition',
      // Libellé de l'année Scolaire - 10
      $this->AnneeScolaire==null ? '' : $this->AnneeScolaire->getAnneeScolaire(),
      // Identifiant de l'élément sélectionné - 11
      $this->AnneeScolaire==null ? '' : $this->AnneeScolaire->getId(),
      // Url pour Annuler l'action - 12
      $urlCancelAnneeScolaire,
      '','','','','','','',
      '','','','','','','',
      '','','','','','','',
      '','','','','','','',
    );
    return $this->getRender($this->urlTemplatePageConfigurationAdmin, $attibutes);
  }
}
