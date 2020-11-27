<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AjaxActions
 * @author Hugues
 * @since 1.00.00
 * @version 1.00.00
 */
class AjaxActions extends LocalActions
{

  /**
   * Gère les actions Ajax
   * @since 1.0.00
   */
  public static function dealWithAjax()
  {
    switch ($_POST[self::AJAX_ACTION]) {
      case self::AJAX_GETNEWMATIERE   :
        $returned = CompteRenduActions::dealWithStatic($_POST);
      break;
      default              :
        $returned  = 'Erreur dans le $_POST['.self::AJAX_ACTION.'] : '.$_POST[self::AJAX_ACTION].'<br>';
      break;
    }
    return $returned;
  }


}
