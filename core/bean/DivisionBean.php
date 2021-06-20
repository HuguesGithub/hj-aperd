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
   * @version 1.21.06.20
   * @since 1.21.06.20
   */
  public function getSelect($params = array())
  {
    /////////////////////////////////////////////////////////////////
    // Initialisation des données
    $tagId = (isset($params['tag']) ? $params['tag'] : self::CST_ID);
    $label = (isset($params['label']) ? $params['label'] : self::CST_DEFAULT_SELECT);
    $selectedId = (isset($params['selectedId']) ? $params['selectedId'] : -1);

    /////////////////////////////////////////////////////////////////
    // Construction de la liste des Options
    $strOptions = $this->getDefaultOption($selectedId, $label);
    $Objs = $this->DivisionServices->getDivisionsWithFilters();
    while (!empty($Objs)) {
      $Obj = array_shift($Objs);
      $Bean = $Obj->getBean();
      $strOptions .= $Bean->getOption($selectedId);
    }
    /////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////
    // Construction des attributs de la balise Select
    $selClass= self::CST_MD_SELECT;
    if (isset($params[self::AJAX_UPLOAD])) {
      $selClass .= self::CST_BLANK.self::AJAX_UPLOAD;
    }
    if (isset($params[self::ATTR_REQUIRED]) && ($selectedId==-1 || $selectedId==self::CST_DEFAULT_SELECT)) {
      //$selClass .= self::CST_BLANK.self::NOTIF_IS_INVALID;
    }
    $attributes = array(
      self::ATTR_CLASS => $selClass,
      self::ATTR_NAME  => $tagId,
    );
    if (isset($params[self::ATTR_REQUIRED])) {
      $attributes[self::ATTR_REQUIRED] = '';
    }
    if (isset($params[self::ATTR_READONLY])) {
      $attributes[self::ATTR_READONLY] = '';
    }
    /////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////
    // On retourne la balise construite
    return $this->getBalise(self::TAG_SELECT, $strOptions, $attributes);
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
