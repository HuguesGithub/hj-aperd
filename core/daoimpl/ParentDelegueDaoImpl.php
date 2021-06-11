<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe ParentDelegueDaoImpl
 * @author Hugues
 * @version 1.21.06.11
 * @since 1.21.06.11
 */
class ParentDelegueDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  { parent::__construct('ParentDelegue'); }
  /**
   * @param array $rows
   * @return array
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  protected function convertToArray($rows)
  {
    $Items = array();
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $Items[] = ParentDelegue::convertElement($row);
      }
    }
    return $Items;
  }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|ParentDelegue
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  public function select($file, $line, $arrParams)
  { return parent::localSelect($arrParams, new ParentDelegue()); }
}
