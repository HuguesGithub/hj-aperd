<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe MatiereServices
 * @author Hugues
 * @version 1.00.01
 * @since 1.00.00
 */
class MatiereServices extends LocalServices
{
  /**
   * L'objet Dao pour faire les requêtes
   * @var MatiereDaoImpl $Dao
   */
  protected $Dao;
  /**
   * Class constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->Dao = new MatiereDaoImpl();
  }

  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_LABELMATIERE));
    return $arrParams;
  }
  /**
   * @param array $arrFilters
   * @param string $orderby
   * @param string $order
   * @return array
   */
  public function getMatieresWithFilters($arrFilters=array(), $orderby=self::FIELD_LABELMATIERE, $order=self::ORDER_ASC)
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters(__FILE__, __LINE__, $arrParams);
  }
  public function deleteIn($ins)
  { $this->Dao->deleteIn($ins); }

}
