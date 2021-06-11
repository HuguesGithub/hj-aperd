<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CompteRenduServices
 * @author Hugues
 * @version 1.21.06.04
 * @since 1.21.06.04
 */
class CompteRenduServices extends LocalServices
{
  //////////////////////////////////////////////////
  // ATTRIBUTES
  //////////////////////////////////////////////////
  /**
   * L'objet Dao pour faire les requêtes
   * @var CompteRenduDaoImpl $Dao
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
    $this->Dao = new CompteRenduDaoImpl();
  }

  //////////////////////////////////////////////////
  // METHODS
  //////////////////////////////////////////////////
  /**
   * @param array $arrFilters
   * @return array
   * @version 1.00.00
   * @since 1.00.00
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_CRKEY));
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_ANNEESCOLAIRE_ID));
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_TRIMESTRE));
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_DIVISION_ID));
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_STATUS));
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
  public function getCompteRendusWithFilters($arrFilters=array(), $orderby=self::FIELD_ID, $order=self::ORDER_ASC)
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters(__FILE__, __LINE__, $arrParams);
  }
  /**
   * @param string $crKey
   * @return CompteRendu
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getCompteRenduByCrKey($crKey)
  {
    $CompteRendus = $this->getCompteRendusWithFilters(array(self::FIELD_CRKEY=>$crKey), self::FIELD_ID, self::ORDER_DESC);
    return (count($CompteRendus)>=1 ? array_shift($CompteRendus) : new CompteRendu());
  }



  public function getUniqueGenKey()
  {
    do {
      $crKey = $this->genKey();
      $TestCr = $this->getCompteRenduByCrKey($crKey);
    } while ($TestCr->getId()!='');
    return $crKey;
  }
  /**
   * @return string
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function genKey()
  {
    $eligibleChars = 'abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $crKey = '';
    for ($i=0; $i<16; $i++) {
      $eligibleChars = str_shuffle($eligibleChars);
      $crKey .= $eligibleChars[0];
    }
    return $crKey;
  }
}
