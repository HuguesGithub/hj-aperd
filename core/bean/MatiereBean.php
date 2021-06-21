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
    $this->MatiereServices = new MatiereServices();
    $this->Matiere = ($Matiere=='' ? new Matiere() : $Matiere);
  }
  /**
   */
  public function getRowForAdminPage($checked=false)
  {
    $queryArgs = array(
      self::CST_ONGLET     => self::PAGE_MATIERE,
      self::CST_POSTACTION => self::CST_EDIT,
      self::FIELD_ID       => $this->Matiere->getId()
    );
    $urlEdition = $this->getQueryArg($queryArgs);

    $queryArgs = array(
      self::CST_ONGLET     => self::PAGE_MATIERE,
      self::CST_POSTACTION => self::CST_DELETE,
      self::FIELD_ID       => $this->Matiere->getId()
    );
    $urlSuppression = $this->getQueryArg($queryArgs);

    $attributes = array(
      // Identifiant de la Matière
      $this->Matiere->getId(),
      // Url d'édition de la Matière
      $urlEdition,
      // Libellé de la Matière
      $this->Matiere->getLabelMatiere(),
      // Url de suppression de la Matière
      $urlSuppression,
      // Checkée ou non - 5
      $checked ? ' checked' : '',
    );
    return $this->getRender($this->urlTemplateRowAdmin, $attributes);
  }
  /**
   * @param array $params
   * @return string
   * @version 1.21.06.21
   * @since 1.21.06.21
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
    $Objs = $this->MatiereServices->getMatieresWithFilters();
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
  { return $this->getLocalOption($this->Matiere->getLabelMatiere(), $this->Matiere->getId(), $selectedId); }
}
