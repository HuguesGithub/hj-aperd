<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe AdministrationBean
 * @author Hugues
 * @version 1.00.01
 * @since 1.00.00
 */
class AdministrationBean extends LocalBean
{
  protected $urlTemplateRowAdmin = 'web/pages/admin/fragments/row-administration.php';
  /**
   * Class Constructor
   * @param Administration $Administration
   * @version 1.00.00
   * @since 1.00.00
   */
  public function __construct($Administration='')
  {
    parent::__construct();
    $this->AdministrationServices = new AdministrationServices();
    $this->Administration = ($Administration=='' ? new Administration() : $Administration);
  }
  /**
   * @since v1.00.01
   */
  public function getRowForAdminPage()
  {
    $queryArgs = array(
      self::CST_ONGLET=>self::PAGE_CONFIGURATION,
      self::CST_POSTACTION=>self::CST_EDIT,
      self::FIELD_ID=>$this->Administration->getId(),
      self::ATTR_TYPE=>'Administration',
    );

    $attributes = array(
      // Identifiant de l'Administration
      $this->Administration->getId(),
      // Url d'édition de l'Administration
      $this->getQueryArg($queryArgs),
      // Nom de l'Administration - 3
      $this->Administration->getNomTitulaire(),
      // Poste de l'Administration - 4
      $this->Administration->getLabelPoste(),
    );
    return $this->getRender($this->urlTemplateRowAdmin, $attributes);
  }
  /**
   * @param string $tagId
   * @param mixed $selectedId
   * @param boolean $isMandatory
   * @return string
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getSelect($tagId=self::CST_ID, $label=self::CST_DEFAULT_SELECT, $selectedId=-1, $isMandatory=false)
  {
    $Administrations = $this->AdministrationServices->getAdministrationsWithFilters();
    return $this->getLocalSelect($Administrations, $tagId, $label, $selectedId, $isMandatory);
  }
  /**
   * @param mixed $selectedId
   * @return string;
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getOption($selectedId=-1)
  { return $this->getLocalOption($this->Administration->getNomTitulaire(), $this->Administration->getId(), $selectedId); }
}
