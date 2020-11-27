<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CompoClasseServices
 * @author Hugues
 * @version 1.00.01
 * @since 1.00.00
 */
class CompoClasseServices extends LocalServices
{
  /**
   * L'objet Dao pour faire les requêtes
   * @var CompoClasseDaoImpl $Dao
   */
  protected $Dao;
  /**
   * Class constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->Dao = new CompoClasseDaoImpl();
  }

  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_ANNEESCOLAIRE_ID));
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_CLASSE_ID));
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
  public function getCompoClassesWithFilters($arrFilters=array(), $orderby=self::FIELD_ANNEESCOLAIRE_ID, $order=self::ORDER_ASC)
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters(__FILE__, __LINE__, $arrParams);
  }
  /**
   * @param int $id
   * @return CompoClasse
   * @version 1.00.01
   * @since 1.00.01
   */
  public function selectLocal($id)
  { return $this->select(__FILE__, __LINE__, $id); }
  /**
   * @param CompoClasse $CompoClasse
   * @return CompoClasse
   * @version 1.00.01
   * @since 1.00.01
   */
  public function updateLocal($CompoClasse)
  { return $this->update(__FILE__, __LINE__, $CompoClasse); }
  /**
   * @param CompoClasse $CompoClasse
   * @return CompoClasse
   * @version 1.00.01
   * @since 1.00.01
   */
  public function insertLocal($CompoClasse)
  { return $this->insert(__FILE__, __LINE__, $CompoClasse); }
}

