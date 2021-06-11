<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe DivisionServices
 * @author Hugues
 * @version 1.00.01
 * @since 1.00.00
 */
class DivisionServices extends LocalServices
{
  /**
   * L'objet Dao pour faire les requêtes
   * @var DivisionDaoImpl $Dao
   */
  protected $Dao;
  /**
   * Class constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->Dao = new DivisionDaoImpl();
  }

  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_LABELDIVISION));
    return $arrParams;
  }
  /**
   * @param array $arrFilters
   * @param string $orderby
   * @param string $order
   * @return array
   */
  public function getDivisionsWithFilters($arrFilters=array(), $orderby=self::FIELD_LABELDIVISION, $order=self::ORDER_ASC)
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters(__FILE__, __LINE__, $arrParams);
  }
  /**
   * @param int $id
   * @return Division
   * @version 1.00.01
   * @since 1.00.01
   */
  public function selectLocal($id)
  { return $this->select(__FILE__, __LINE__, $id); }
  /**
   * @param Division $Division
   * @return Division
   * @version 1.00.01
   * @since 1.00.01
   */
  public function updateLocal($Division)
  { return $this->update(__FILE__, __LINE__, $Division); }
  /**
   * @param Division $Division
   * @return Division
   * @version 1.00.01
   * @since 1.00.01
   */
  public function insertLocal($Division)
  { return $this->insert(__FILE__, __LINE__, $Division); }
}

