<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe ClasseScolaireBean
 * @author Hugues
 * @version 1.00.00
 * @since 1.00.00
 */
class ClasseScolaireBean extends LocalBean
{
  protected $urlTemplateRowAdmin = 'web/pages/admin/fragments/row-classe.php';
  /**
   * Class Constructor
   * @param ClasseScolaire $ClasseScolaire
   * @version 1.00.00
   * @since 1.00.00
   */
  public function __construct($ClasseScolaire='')
  {
    parent::__construct();
    $this->ClasseScolaireServices = new ClasseScolaireServices();
    $this->ClasseScolaire = ($ClasseScolaire=='' ? new ClasseScolaire() : $ClasseScolaire);
  }
  /**
   */
  public function getRowForAdminPage()
  {
    $queryArgs = array(
      self::CST_ONGLET=>self::PAGE_CLASSE,
      self::CST_POSTACTION=>self::CST_EDIT,
      self::FIELD_ID=>$this->ClasseScolaire->getId(),
      self::ATTR_TYPE=>'Classe',
    );

    $attributes = array(
      // Identifiant de la Classe
      $this->ClasseScolaire->getId(),
      // Url d'édition de la Classe
      $this->getQueryArg($queryArgs),
      // Libellé de la Classe
      $this->ClasseScolaire->getLabelClasse(),
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
    $ClasseScolaires = $this->ClasseScolaireServices->getClasseScolairesWithFilters();
    return $this->getLocalSelect($ClasseScolaires, $tagId, $label, $selectedId, $isMandatory);
  }
  /**
   * @param mixed $selectedId
   * @return string;
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getOption($selectedId=-1)
  { return $this->getLocalOption($this->ClasseScolaire->getLabelClasse(), $this->ClasseScolaire->getId(), $selectedId); }
}
