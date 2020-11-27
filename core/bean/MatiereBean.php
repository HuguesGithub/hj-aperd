<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe MatiereBean
 * @author Hugues
 * @version 1.00.00
 * @since 1.00.00
 */
class MatiereBean extends LocalBean
{
  protected $urlTemplateRowAdmin = 'web/pages/admin/fragments/row-matiere.php';
  /**
   * Class Constructor
   * @param Matiere $Matiere
   * @version 1.00.00
   * @since 1.00.00
   */
  public function __construct($Matiere='')
  {
    parent::__construct();
    $this->MatiereServices = new MatiereServices();
    $this->Matiere = ($Matiere=='' ? new Matiere() : $Matiere);
  }
  /**
   */
  public function getRowForAdminPage()
  {
    $queryArgs = array(
      self::CST_ONGLET=>self::PAGE_MATIERE,
      self::CST_POSTACTION=>self::CST_EDIT,
      self::FIELD_ID=>$this->Matiere->getId()
    );

    $attributes = array(
      // Identifiant de la Matière
      $this->Matiere->getId(),
      // Url d'édition de la Matière
      $this->getQueryArg($queryArgs),
      // Libellé de la Matière
      $this->Matiere->getLabelMatiere(),
    );
    return $this->getRender($this->urlTemplateRowAdmin, $attributes);
  }
  /**
   * @param string $tagId
   * @param mixed $selectedId
   * @return string;
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getSelect($tagId=self::CST_ID, $label=self::CST_DEFAULT_SELECT, $selectedId=-1)
  {
    $Matieres = $this->MatiereServices->getMatieresWithFilters();
    return $this->getLocalSelect($Matieres, $tagId, $label, $selectedId);
  }
  /**
   * @param mixed $selectedId
   * @return string;
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getOption($selectedId=-1)
  { return $this->getLocalOption($this->Matiere->getLabelMatiere(), $this->Matiere->getId(), $selectedId); }
}
