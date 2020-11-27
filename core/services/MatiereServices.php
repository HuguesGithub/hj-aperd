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
  /**
   * @param int $id
   * @return Matiere
   * @version 1.00.01
   * @since 1.00.00
   */
  public function selectLocal($id)
  { return $this->select(__FILE__, __LINE__, $id); }
  /**
   * @param Matiere $Matiere
   * @return Matiere
   * @version 1.00.01
   * @since 1.00.01
   */
  public function updateLocal($Matiere)
  { return $this->update(__FILE__, __LINE__, $Matiere); }
  /**
   * @param Matiere $Matiere
   * @return Matiere
   * @version 1.00.01
   * @since 1.00.01
   */
  public function insertLocal($Matiere)
  { return $this->insert(__FILE__, __LINE__, $Matiere); }
}
