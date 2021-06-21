<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe DivisionBean
 * @author Hugues
 * @version 1.21.06.17
 * @since 1.21.06.04
 */
class DivisionBean extends LocalBean
{
  protected $urlTemplateRowAdmin = 'web/pages/admin/fragments/row-division.php';
  /**
   * Class Constructor
   * @param Division $Division
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function __construct($Division='')
  {
    $this->DivisionServices = new DivisionServices();
    $this->Division = ($Division=='' ? new Division() : $Division);
  }
  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////
  /**
   * @parame boolean $checked
   * @return string
   * @version 1.21.06.17
   * @since 1.21.06.04
   */
  public function getRowForAdminPage($checked=false)
  {
    // Création du lien d'édition
    $queryArgs = array(
      self::CST_ONGLET     => self::PAGE_DIVISION,
      self::CST_POSTACTION => self::CST_EDIT,
      self::FIELD_ID       => $this->Division->getId(),
    );
    $urlEdition = $this->getQueryArg($queryArgs);
    // Création du lien de suppression
    $queryArgs[self::CST_POSTACTION] = self::CST_DELETE;
    $urlSuppression = $this->getQueryArg($queryArgs);
    // Assignation des données relatives au template
    $attributes = array(
      // Identifiant de la Division
      $this->Division->getId(),
      // Url d'édition de la Division
      $urlEdition,
      // Libellé de la Division
      $this->Division->getLabelDivision(),
      // crKey de la Division
      $this->Division->getCrKey(),
      // Url de suppression de la Division
      $urlSuppression,
      // Checkée ou non - 5
      $checked ? self::CST_BLANK.self::CST_CHECKED : '',
    );
    // Restituttion du template.
    return $this->getRender($this->urlTemplateRowAdmin, $attributes);
  }
  /**
   * @param array $params
   * @return string
   * @version 1.21.06.21
   * @since 1.21.06.20
   */
  public function getSelect($params = array())
  {
    $params['Objs'] = $this->DivisionServices->getDivisionsWithFilters();
    return parent::getSelect($params);
  }












  /**
   * @param mixed $selectedId
   * @return string;
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getOption($selectedId=-1)
  { return $this->getLocalOption($this->Division->getLabelDivision(), $this->Division->getId(), $selectedId); }
}
