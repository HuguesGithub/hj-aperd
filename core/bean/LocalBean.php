<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LocalBean
 * @author Hugues
 * @version 1.00.01
 * @since 1.00.00
 */
class LocalBean extends UtilitiesBean implements ConstantsInterface
{
  /**
   */
  public function __construct()
  {
  }
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
   * @param array $Objs
   * @param string $tagId
   * @param mixed $selectedId
   * @param boolean $isMandatory
   * @return string
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getLocalSelect($Objs, $tagId, $label='', $selectedId=-1, $isMandatory=false)
  {
    $strOptions = $this->getDefaultOption($selectedId, $label);
    while (!empty($Objs)) {
      $Obj = array_shift($Objs);
      $Bean = $Obj->getBean();
      $strOptions .= $Bean->getOption($selectedId);
    }
    $bFlag = $isMandatory && ($selectedId==-1||$selectedId==self::CST_DEFAULT_SELECT);
    $attributes = array(
      self::ATTR_CLASS => self::CST_MD_SELECT.($bFlag ? ' '.self::NOTIF_IS_INVALID : ''),
      self::ATTR_NAME  => $tagId,
    );
    if (strpos($tagId, '[]')===false) {
      $attributes[self::ATTR_ID] = $tagId;
    }
    if ($isMandatory) {
      $attributes[self::ATTR_REQUIRED] = '';
    }
    return $this->getBalise(self::TAG_SELECT, $strOptions, $attributes);
  }
}
