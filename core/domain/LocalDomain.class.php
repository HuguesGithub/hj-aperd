<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LocalDomain
 * @author Hugues
 * @version 1.00.00
 * @since 1.00.00
 */
class LocalDomain extends GlobalDomain implements ConstantsInterface
{
  /**
   * @return string
   * @version 1.00.00
   * @since 1.00.00
   */
  public function toJson()
  {
    $classVars = $this->getClassVars();
    $str = '';
    foreach ($classVars as $key => $value) {
      if ($str!='') {
        $str .= ', ';
      }
      $str .= '"'.$key.'":'.json_encode($this->getField($key));
    }
    return '{'.$str.'}';
  }
  /**
   * @param array $post
   * @return bool
   * @version 1.00.00
   * @since 1.00.00
   */
  public function updateWithPost($post)
  {
    $classVars = $this->getClassVars();
    unset($classVars['id']);
    $doUpdate = false;
    foreach ($classVars as $key => $value) {
      if (is_array($post[$key])) {
        $value = stripslashes(implode(';', $post[$key]));
      } else {
        $value = stripslashes($post[$key]);
      }
      if ($this->{$key} != $value) {
        $doUpdate = true;
        $this->{$key} = $value;
      }
    }
    return $doUpdate;
  }
  /**
   * @return int
   * @version 1.00.00
   * @since 1.00.00
   */
  public static function getWpUserId()
  { return get_current_user_id(); }
}
