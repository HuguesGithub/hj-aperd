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
    if ($_POST[self::AJAX_ACTION]==self::AJAX_GETNEWMATIERE) {
      $returned = CompteRenduActions::dealWithStatic($_POST);
    } elseif ($_POST[self::AJAX_ACTION]==self::AJAX_UPLOAD) {
      $returned = CompteRenduActions::dealWithStatic($_POST);
    } else {
      $saisie = stripslashes($_POST[self::AJAX_ACTION]);
      $returned  = 'Erreur dans le $_POST['.self::AJAX_ACTION.'] : '.$saisie.'<br>';
    }
    return $returned;
  }


}
