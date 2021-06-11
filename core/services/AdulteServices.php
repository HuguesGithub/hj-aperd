<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe AdulteServices
 * @author Hugues
 * @version 1.21.06.10
 * @since 1.21.06.04
 */
class AdulteServices extends LocalServices
{
  //////////////////////////////////////////////////
  // ATTRIBUTES
  //////////////////////////////////////////////////
  /**
   * L'objet Dao pour faire les requêtes
   * @var AdulteDaoImpl $Dao
   */
  protected $Dao;

  //////////////////////////////////////////////////
  // CONSTRUCT
  //////////////////////////////////////////////////
  /**
   * Class constructor
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function __construct()
  {
    $this->Dao = new AdulteDaoImpl();
  }

  //////////////////////////////////////////////////
  // METHODS
  //////////////////////////////////////////////////
  /**
   * @param array $arrFilters
   * @return array
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_NOMPARENT));
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_ADHERENT));
    return $arrParams;
  }
  /**
   * @param array $arrFilters
   * @param string $orderby
   * @param string $order
   * @return array
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getAdultesWithFilters($arrFilters=array(), $orderby=self::FIELD_NOMPARENT, $order=self::ORDER_ASC)
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters(__FILE__, __LINE__, $arrParams);
  }
  /**
   * @param string $ins
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function deleteIn($ins)
  { $this->Dao->deleteIn($ins); }

}
