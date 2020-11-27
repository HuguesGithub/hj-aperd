<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe ClasseScolaireServices
 * @author Hugues
 * @version 1.00.01
 * @since 1.00.00
 */
class ClasseScolaireServices extends LocalServices
{
  /**
   * L'objet Dao pour faire les requêtes
   * @var ClasseScolaireDaoImpl $Dao
   */
  protected $Dao;
  /**
   * Class constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->Dao = new ClasseScolaireDaoImpl();
  }

  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_LABELCLASSE));
    return $arrParams;
  }
  /**
   * @param array $arrFilters
   * @param string $orderby
   * @param string $order
   * @return array
   */
  public function getClasseScolairesWithFilters($arrFilters=array(), $orderby=self::FIELD_LABELCLASSE, $order=self::ORDER_ASC)
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters(__FILE__, __LINE__, $arrParams);
  }
  /**
   * @param int $id
   * @return ClasseScolaire
   * @version 1.00.01
   * @since 1.00.01
   */
  public function selectLocal($id)
  { return $this->select(__FILE__, __LINE__, $id); }
  /**
   * @param ClasseScolaire $ClasseScolaire
   * @return ClasseScolaire
   * @version 1.00.01
   * @since 1.00.01
   */
  public function updateLocal($ClasseScolaire)
  { return $this->update(__FILE__, __LINE__, $ClasseScolaire); }
  /**
   * @param ClasseScolaire $ClasseScolaire
   * @return ClasseScolaire
   * @version 1.00.01
   * @since 1.00.01
   */
  public function insertLocal($ClasseScolaire)
  { return $this->insert(__FILE__, __LINE__, $ClasseScolaire); }
}

