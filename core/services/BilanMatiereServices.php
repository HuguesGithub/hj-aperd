<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe BilanMatiereServices
 * @author Hugues
 * @version 1.00.00
 * @since 1.00.00
 */
class BilanMatiereServices extends LocalServices
{
  /**
   * L'objet Dao pour faire les requêtes
   * @var BilanMatiereDaoImpl $Dao
   */
  protected $Dao;
  /**
   * Class constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->Dao = new BilanMatiereDaoImpl();
  }

  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_COMPTERENDU_ID));
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_MATIERE_ID));
    return $arrParams;
  }
  /**
   * @param array $arrFilters
   * @param string $orderby
   * @param string $order
   * @return array
   */
  public function getBilanMatieresWithFilters($arrFilters=array(), $orderby=self::FIELD_ID, $order=self::ORDER_ASC)
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters(__FILE__, __LINE__, $arrParams);
  }
  /**
   * @param int $id
   * @return BilanMatiere
   * @version 1.00.00
   * @since 1.00.00
   */
  public function selectLocal($id)
  { return $this->select(__FILE__, __LINE__, $id); }
  /**
   * @param BilanMatiere
   * @version 1.00.00
   * @since 1.00.00
   */
  public function insertLocal($BilanMatiere)
  {
    $id = $this->insert(__FILE__, __LINE__, $BilanMatiere);
    $BilanMatiere->setId($id);
  }
}
