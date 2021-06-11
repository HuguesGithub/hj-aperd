<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe EleveServices
 * @author Hugues
 * @version 1.00.01
 * @since 1.00.00
 */
class EleveServices extends LocalServices
{
  /**
   * L'objet Dao pour faire les requêtes
   * @var EleveDaoImpl $Dao
   */
  protected $Dao;
  /**
   * Class constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->Dao = new EleveDaoImpl();
  }

  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_NOMELEVE));
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_PRENOMELEVE));
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_DIVISION_ID));
    return $arrParams;
  }
  /**
   * @param array $arrFilters
   * @param string $orderby
   * @param string $order
   * @return array
   */
  public function getElevesWithFilters($arrFilters=array(), $orderby=self::FIELD_NOMELEVE, $order=self::ORDER_ASC)
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters(__FILE__, __LINE__, $arrParams);
  }
  /**
   * @param int $id
   * @return Eleve
   * @version 1.00.01
   * @since 1.00.00
   */
  public function selectLocal($id)
  { return $this->select(__FILE__, __LINE__, $id); }
  /**
   * @param Eleve $Eleve
   * @return Eleve
   * @version 1.00.01
   * @since 1.00.01
   */
  public function updateLocal($Eleve)
  { return $this->update(__FILE__, __LINE__, $Eleve); }
  /**
   * @param Eleve $Eleve
   * @return Eleve
   * @version 1.00.01
   * @since 1.00.01
   */
  public function insertLocal($Eleve)
  { return $this->insert(__FILE__, __LINE__, $Eleve); }
  /**
   * @param Eleve $Eleve
   * @return null
   * @version 1.00.01
   * @since 1.00.01
   */
  public function deleteLocal($Eleve)
  { return $this->delete(__FILE__, __LINE__, $Eleve); }
}
