<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * LocalActions
 * @author Hugues
 * @since 1.00.00
 * @version 1.00.00
 */
class LocalActions extends GlobalActions implements ConstantsInterface
{
  /**
   * Class Constructor
   */
  public function __construct()
  {
  }

  /**
   * Retourne une chaine json
   * @param string $msg
   * @param string $id
   * @param boolean $directReturn
   * @return string
   */
  protected function jsonString($msg, $id, $directReturn)
  {
    $content = '"'.$id.'":'.json_encode($msg);
    return ($directReturn ? '{'.$content.'}' : $content);
  }

  public function importFile($prefix)
  {
    $dir_name = dirname(__FILE__).'/../../web/rsc/csv-files/';
    $file_name = self::CST_IMPORT.'_'.strtolower($prefix).'.csv';
    return file_get_contents($dir_name.$file_name);
  }

  public function exportFile($data, $prefix)
  {
    $dir_name = dirname(__FILE__).'/../../web/rsc/csv-files/';
    $file_name = self::CST_EXPORT.'_'.strtolower($prefix).'_'.date('Ymd_His').'.csv';
    $dst = fopen($dir_name.$file_name, 'w');
    fputs($dst, implode(self::EOL, $data));
    fclose($dst);
    $file_name = '/wp-content/plugins/hj-aperd/web/rsc/csv-files/'.$file_name;
    return sprintf(self::MSG_SUCCESS_EXPORT, $file_name);
  }
}
