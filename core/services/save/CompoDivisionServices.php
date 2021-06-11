<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CompoDivisionServices
 * @author Hugues
 * @version 1.00.01
 * @since 1.00.00
 */
class CompoDivisionServices extends LocalServices
{
  /**
   * L'objet Dao pour faire les requêtes
   * @var CompoDivisionDaoImpl $Dao
   */
  protected $Dao;
  /**
   * Class constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->Dao = new CompoDivisionDaoImpl();
  }

  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_ANNEESCOLAIRE_ID));
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_DIVISION_ID));
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_MATIERE_ID));
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_ENSEIGNANT_ID));
    return $arrParams;
  }
  /**
   * @param array $arrFilters
   * @param string $orderby
   * @param string $order
   * @return array
   */
  public function getCompoDivisionsWithFilters($arrFilters=array(), $orderby=self::FIELD_ANNEESCOLAIRE_ID, $order=self::ORDER_ASC)
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters(__FILE__, __LINE__, $arrParams);
  }
  /**
   * @param int $id
   * @return CompoDivision
   * @version 1.00.01
   * @since 1.00.01
   */
  public function selectLocal($id)
  { return $this->select(__FILE__, __LINE__, $id); }
  /**
   * @param CompoDivision $CompoDivision
   * @return CompoDivision
   * @version 1.00.01
   * @since 1.00.01
   */
  public function updateLocal($CompoDivision)
  { return $this->update(__FILE__, __LINE__, $CompoDivision); }
  /**
   * @param CompoDivision $CompoDivision
   * @return CompoDivision
   * @version 1.00.01
   * @since 1.00.01
   */
  public function insertLocal($CompoDivision)
  { return $this->insert(__FILE__, __LINE__, $CompoDivision); }
}

