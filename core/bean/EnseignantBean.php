<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe EnseignantBean
 * @author Hugues
 * @version 1.00.01
 * @since 1.00.00
 */
class EnseignantBean extends LocalBean
{
  protected $urlTemplateRowAdmin = 'web/pages/admin/fragments/row-enseignant.php';
  /**
   * Class Constructor
   * @param Enseignant $Enseignant
   * @version 1.00.00
   * @since 1.00.00
   */
  public function __construct($Enseignant='')
  {
    parent::__construct();
    $this->EnseignantServices = new EnseignantServices();
    $this->Enseignant = ($Enseignant=='' ? new Enseignant() : $Enseignant);
  }
  /**
   */
  public function getRowForAdminPage($args=array())
  {
    $queryArgs = array(
      self::CST_ONGLET=>self::PAGE_ENSEIGNANT,
      self::CST_POSTACTION=>self::CST_EDIT,
      self::FIELD_ID=>$this->Enseignant->getId(),
      self::ATTR_TYPE=>'Enseignants',
    );
    $queryArgs = array_merge($queryArgs, $args);

    $attributes = array(
      // Identifiant de l'Enseignant
      $this->Enseignant->getId(),
      // Url d'édition de l'Enseignant
      $this->getQueryArg($queryArgs),
      // Nom de l'Enseignant - 3
      $this->Enseignant->getNomEnseignant(),
      // Matière de l'Enseignant - 4
      $this->Enseignant->getMatiere()->getLabelMatiere(),
      // Statut de l'Enseignant - 5
      $this->Enseignant->getStatus()==1 ? 'Actif' : 'Inactif',
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
    $Enseignants = $this->EnseignantServices->getEnseignantsWithFilters();
    return $this->getLocalSelect($Enseignants, $tagId, $label, $selectedId, $isMandatory);
  }
  /**
   * @param mixed $selectedId
   * @return string
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getOption($selectedId=-1)
  { return $this->getLocalOption($this->Enseignant->getNomEnseignant(), $this->Enseignant->getId(), $selectedId); }
}
