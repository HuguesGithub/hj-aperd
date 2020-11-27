<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe AnneeScolaireBean
 * @author Hugues
 * @version 1.00.00
 * @since 1.00.00
 */
class AnneeScolaireBean extends LocalBean
{
  protected $urlTemplateRowAdmin = 'web/pages/admin/fragments/row-anneescolaire.php';
  /**
   * Class Constructor
   * @param AnneeScolaire $AnneeScolaire
   * @version 1.00.00
   * @since 1.00.00
   */
  public function __construct($AnneeScolaire='')
  {
    parent::__construct();
    $this->AnneeScolaireServices = new AnneeScolaireServices();
    $this->AnneeScolaire = ($AnneeScolaire=='' ? new AnneeScolaire() : $AnneeScolaire);
  }
  public function getRowForAdminPage()
  {
    $queryArgs = array(
      self::CST_ONGLET=>self::PAGE_CONFIGURATION,
      self::CST_POSTACTION=>self::CST_EDIT,
      self::FIELD_ID=>$this->AnneeScolaire->getId(),
      self::ATTR_TYPE=>'AnneeScolaire',
    );

    $attributes = array(
      // Identifiant de l'Année Scolaire
      $this->AnneeScolaire->getId(),
      // Url d'édition de l'Année Scolaire
      $this->getQueryArg($queryArgs),
      // Libellé de l'Année Scolaire - 3
      $this->AnneeScolaire->getAnneeScolaire(),
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
    $AnneeScolaires = $this->AnneeScolaireServices->getAnneeScolairesWithFilters();
    return $this->getLocalSelect($AnneeScolaires, $tagId, $label, $selectedId, $isMandatory);
  }
  /**
   * @param mixed $selectedId
   * @return string;
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getOption($selectedId=-1)
  { return $this->getLocalOption($this->AnneeScolaire->getAnneeScolaire(), $this->AnneeScolaire->getId(), $selectedId); }
}
