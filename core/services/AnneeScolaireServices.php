<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe AnneeScolaireServices
 * @author Hugues
 * @version 1.00.00
 * @since 1.00.00
 */
class AnneeScolaireServices extends LocalServices
{
  /**
   * L'objet Dao pour faire les requêtes
   * @var AnneeScolaireDaoImpl $Dao
   */
  protected $Dao;
  /**
   * Class constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->Dao = new AnneeScolaireDaoImpl();
  }

  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_ANNEESCOLAIRE));
    return $arrParams;
  }
  /**
   * @param array $arrFilters
   * @param string $orderby
   * @param string $order
   * @return array
   */
  public function getAnneeScolairesWithFilters($arrFilters=array(), $orderby=self::FIELD_ANNEESCOLAIRE, $order=self::ORDER_ASC)
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters(__FILE__, __LINE__, $arrParams);
  }
  /**
   * @param int $id
   * @return AnneeScolaire
   * @version 1.00.00
   * @since 1.00.00
   */
  public function selectLocal($id)
  { return $this->select(__FILE__, __LINE__, $id); }
  /**
   * @param AnneeScolaire $AnneeScolaire
   * @return AnneeScolaire
   * @version 1.00.01
   * @since 1.00.01
   */
  public function updateLocal($AnneeScolaire)
  { return $this->update(__FILE__, __LINE__, $AnneeScolaire); }
  /**
   * @param AnneeScolaire $AnneeScolaire
   * @return AnneeScolaire
   * @version 1.00.01
   * @since 1.00.01
   */
  public function insertLocal($AnneeScolaire)
  { return $this->insert(__FILE__, __LINE__, $AnneeScolaire); }
}
