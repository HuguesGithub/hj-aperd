<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CompoDivisionBean
 * @author Hugues
 * @version 1.00.00
 * @since 1.00.00
 */
class CompoDivisionBean extends LocalBean
{
  protected $urlTemplateRowAdmin = 'web/pages/admin/fragments/row-compo-division.php';
  /**
   * Class Constructor
   * @param CompoDivision $CompoDivision
   * @version 1.00.00
   * @since 1.00.00
   */
  public function __construct($CompoDivision='')
  {
    $this->CompoDivisionServices = new CompoDivisionServices();
    $this->CompoDivision = ($CompoDivision=='' ? new CompoDivision() : $CompoDivision);
  }
  /**
   */
  public function getRowForAdminPage($checked=false, $argFilters=array())
  {
    $queryArgs = array_merge(
      array(
        self::CST_ONGLET=>self::PAGE_COMPO_DIVISION,
        self::CST_POSTACTION=>self::CST_EDIT,
        self::FIELD_ID=>$this->CompoDivision->getId(),
      ),
      $argFilters,
    );
    $urlEdition = $this->getQueryArg($queryArgs);

    $queryArgs = array(
      self::CST_ONGLET=>self::PAGE_COMPO_DIVISION,
      self::CST_POSTACTION=>self::CST_DELETE,
      self::FIELD_ID=>$this->CompoDivision->getId()
    );
    $urlSuppression = $this->getQueryArg($queryArgs);

    $attributes = array(
      // Identifiant de la Classe - 1
      $this->CompoDivision->getId(),
      // Url d'édition de la Classe - 2
      $urlEdition,
      // Année scolaire - 3
      $this->CompoDivision->getAnneeScolaire()->getAnneeScolaire(),
      // Libellé de la Classe - 4
      $this->CompoDivision->getDivision()->getLabelDivision(),
      // Matière - 5
      $this->CompoDivision->getMatiere()->getLabelMatiere(),
      // Enseignant - 6
      $this->CompoDivision->getEnseignant()->getNomEnseignant(),
      // Lien pour suppression - 7
      $urlSuppression,
      // Checked ou pas - 8
      $checked ? ' checked' : '',
    );
    return $this->getRender($this->urlTemplateRowAdmin, $attributes);
  }
}
