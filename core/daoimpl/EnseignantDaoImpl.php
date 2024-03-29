<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe EnseignantDaoImpl
 * @author Hugues
 * @version 1.21.07.20
 * @since 1.21.06.01
 */
class EnseignantDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  { parent::__construct('Enseignant'); }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  {
    $Items = array();
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $Items[] = Enseignant::convertElement($row);
      }
    }
    return $Items;
  }
  public function deleteIn($ins)
  {
    $request = $this->delete.$this->fromRequest.'WHERE id IN ('.$ins.');';
    MySQL::wpdbQuery($request);
  }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|Enseignant
   */
  public function select($file, $line, $arrParams)
  { return parent::localSelect($arrParams, new Enseignant()); }


  public function getEnseignantsJointsWithFilters($filters)
  {
    $requete  = "SELECT ae.id AS id, genre, nomEnseignant, prenomEnseignant ";
    $requete .= "FROM wp_14_aperd_enseignant ae ";
    $requete .= 'LEFT JOIN wp_14_aperd_enseignant_matiere aem ON ae.id=aem.enseignantId ';
    $requete .= $this->whereFilters."AND (matiereId LIKE '%s' OR matiereId IS NULL) ".$this->orderBy.$this->limit;
    return $this->convertToArray($this->selectEntriesAndLogQuery(__FILE__, __LINE__, $requete, $filters));
  }

  /**
   * @param array $filters
   * @version 1.21.07.20
   * @since 1.21.07.20
   */
  public function getEnseignantByMatiereAndDivision($filters)
  {
    $requete  = 'SELECT ae.id AS id, genre, nomEnseignant, prenomEnseignant ';
    $requete .= 'FROM wp_14_aperd_enseignant ae ';
    $requete .= 'INNER JOIN wp_14_aperd_enseignant_matiere aem ON ae.id=aem.enseignantId ';
    $requete .= 'INNER JOIN wp_14_aperd_compo_division acd ON aem.id=acd.enseignantMatiereId ';
    $requete .= "WHERE matiereId LIKE '%s' AND divisionId LIKE '%s';";
    return $this->convertToArray($this->selectEntriesAndLogQuery(__FILE__, __LINE__, $requete, $filters));
  }
}
