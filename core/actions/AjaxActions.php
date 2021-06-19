<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AjaxActions
 * @author Hugues
 * @since 1.21.06.18
 * @version 1.21.06.01
 */
class AjaxActions extends LocalActions
{

  /**
   * Gère les actions Ajax
   * @version 1.21.06.18
   * @since 1.21.06.01
   */
  public static function dealWithAjax()
  {
    if ($_POST[self::AJAX_ACTION]==self::AJAX_GETNEWMATIERE) {
      $returned = CompteRenduActions::dealWithStatic($_POST);
    } elseif ($_POST[self::AJAX_ACTION]==self::AJAX_UPLOAD) {
      $returned = CompteRenduActions::dealWithStatic($_POST);
    } elseif ($_POST[self::AJAX_ACTION]=='importFile') {
      $msg   = 'Importation du fichier échouée.';
      $notif = self::NOTIF_DANGER;
      $importType = $_POST['importType'];

      if (is_uploaded_file($_FILES['fileToImport']['tmp_name'])) {
        $dir_name  = dirname(__FILE__).'/../../web/rsc/csv-files/';
        $file_name = 'import_'.$importType.'.csv';
        if (rename($_FILES['fileToImport']['tmp_name'], $dir_name.$file_name)) {
          $returned = ImportActions::dealWithStaticImport($importType, $notif, $msg);
        }
      }
    } else {
      $saisie = stripslashes($_POST[self::AJAX_ACTION]);
      $returned  = 'Erreur dans le $_POST['.self::AJAX_ACTION.'] : '.$saisie.'<br>';
    }
    return $returned;
  }


}
