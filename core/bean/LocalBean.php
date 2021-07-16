<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LocalBean
 * @author Hugues
 * @version 1.26.01.21
 * @since 1.26.01.01
 */
class LocalBean extends UtilitiesBean implements ConstantsInterface
{

  /**
   * @param array $addArg
   * @param array $remArg
   * @return string
   */
  public function getQueryArg($addArg, $remArg=array())
  {
    $addArg['page'] = 'hj-aperd/admin_manage.php';
    $remArg[] = 'form';
    $remArg[] = self::FIELD_ID;
    return add_query_arg($addArg, remove_query_arg($remArg, 'http://aperd.jhugues.fr/wp-admin/admin.php'));
  }
  /**
   * @param array $addArg
   * @param array $remArg
   * @param string $url
   * @return string
   */
  public function getFrontQueryArg($addArg, $remArg=array(), $url='http://aperd.jhugues.fr/')
  { return add_query_arg($addArg, remove_query_arg($remArg, $url)); }
  /**
   * @return bool
   */
  public static function isAdmin()
  { return current_user_can('manage_options'); }
  /**
   * @return bool
   */
  public static function isLogged()
  { return is_user_logged_in(); }
  /**
   * @return int
   */
  public static function getWpUserId()
  { return get_current_user_id(); }
  /**
   * @param string $id
   * @param string $default
   * @return mixed
   */
  public function initVar($id, $default='')
  {
    if (isset($_POST[$id])) {
      return $_POST[$id];
    }
    if (isset($_GET[$id])) {
      return $_GET[$id];
    }
    return $default;
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
    $Objs = $params['Objs'];

    /////////////////////////////////////////////////////////////////
    // Construction de la liste des Options
    $strOptions = $this->getDefaultOption($selectedId, $label);
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
    if (isset($params[self::ATTR_MULTIPLE])) {
      $attributes[self::ATTR_MULTIPLE] = '';
    }
    /////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////
    // On retourne la balise construite
    return $this->getBalise(self::TAG_SELECT, $strOptions, $attributes);
  }



  /**
   * @param array $Objs
   * @param string $tagId
   * @param mixed $selectedId
   * @param boolean $isMandatory
   * @return string
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getLocalSelect($Objs, $tagId, $label='', $selectedId=-1, $isMandatory=false, $isAjaxUpload=false, $isReadOnly=false)
  {
    $strOptions = $this->getDefaultOption($selectedId, $label);
    while (!empty($Objs)) {
      $Obj = array_shift($Objs);
      $Bean = $Obj->getBean();
      $strOptions .= $Bean->getOption($selectedId);
    }
    $bFlag = $isMandatory && ($selectedId==-1||$selectedId==self::CST_DEFAULT_SELECT);
    $attributes = array(
      self::ATTR_CLASS => self::CST_MD_SELECT.' form-control-sm'.($bFlag ? ' '.self::NOTIF_IS_INVALID : '').($isAjaxUpload ? ' '.self::AJAX_UPLOAD : ''),
      self::ATTR_NAME  => $tagId,
    );
    if (strpos($tagId, '[]')===false) {
      $attributes[self::ATTR_ID] = $tagId;
    }
    if ($isMandatory) {
      $attributes[self::ATTR_REQUIRED] = '';
    }
    if ($isReadOnly) {
      $attributes[self::ATTR_READONLY] = '';
    }
    return $this->getBalise(self::TAG_SELECT, $strOptions, $attributes);
  }

  protected function getTdCheckbox($Obj)
  {
    $attributes = array(
      self::FIELD_ID   => 'cb-select-'.$Obj->getId(),
      self::ATTR_NAME  => 'post[]',
      self::ATTR_VALUE => $Obj->getId(),
      self::ATTR_TYPE  => 'checkbox',
    );
    return $this->getBalise(self::TAG_TD, $this->getBalise(self::TAG_INPUT, '', $attributes));
  }

  protected function getTdStandard($label)
  { return $this->getBalise(self::TAG_TD, $label); }

  protected function getTdParentActions($label, $href)
  {
    $link   = $this->getBalise(self::TAG_A, $label, array(self::ATTR_CLASS=>'row-title', self::ATTR_HREF=>$href));
    $strong = $this->getBalise(self::TAG_STRONG, $link);
    $link   = $this->getBalise(self::TAG_A, 'Modifier', array(self::ATTR_HREF=>$href));
    $span   = $this->getBalise(self::TAG_SPAN, $link, array(self::ATTR_CLASS=>'edit'));
    $divActions = $this->getBalise(self::TAG_DIV, $span,  array(self::ATTR_CLASS=>'row-actions'));
    return $this->getBalise(self::TAG_TD, $strong.$divActions);
  }
}
