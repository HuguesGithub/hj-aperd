<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe ProfPrincipal
 * @author Hugues
 * @version 1.21.07.07
 * @since 1.21.06.04
 */
class ProfPrincipal extends LocalDomain
{
  //////////////////////////////////////////////////:
  // ATTRIBUTES
  //////////////////////////////////////////////////:
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;
  /**
   * Id technique de la division
   * @var int $divisionId
   */
  protected $divisionId;
  /**
   * Id technique de l'enseignant
   * @var int $enseignantId
   */
  protected $enseignantId;

  //////////////////////////////////////////////////:
  // GETTERS & SETTERS
  //////////////////////////////////////////////////:
  /**
   * @return int
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getDivisionId()
  { return $this->divisionId; }
  /**
   * @return int
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getEnseignantId()
  { return $this->enseignantId; }
  /**
   * @param int $divisionId
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function setDivisionId($divisionId)
  { $this->divisionId = $divisionId; }
  /**
   * @param int $enseignantId
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function setEnseignantId($enseignantId)
  { $this->enseignantId = $enseignantId; }

  //////////////////////////////////////////////////:
  // CONSTRUCT - CLASSVARS - CONVERT - BEAN
  //////////////////////////////////////////////////:
  /**
   * @param array $attributes
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->DivisionServices = new DivisionServices();
    $this->EnseignantServices = new EnseignantServices();
  }
  /**
   * @return array
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getClassVars()
  { return get_class_vars('ProfPrincipal'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return ProfPrincipal
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new ProfPrincipal(), self::getClassVars(), $row); }
  /**
   * @return ProfPrincipalBean
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getBean()
  { return new ProfPrincipalBean($this); }

  //////////////////////////////////////////////////
  // GETTERS OBJETS LIES
  //////////////////////////////////////////////////
  /**
   * @return Division
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getDivision()
  {
    if ($this->Division==null) {
      $this->Division = $this->DivisionServices->selectLocal($this->divisionId);
    }
    return $this->Division;
  }
  /**
   * @return Enseignant
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getEnseignant()
  {
    if ($this->Enseignant==null) {
      $this->Enseignant = $this->EnseignantServices->selectLocal($this->enseignantId);
    }
    return $this->Enseignant;
  }

  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////
  /**
   * @param string $sep
   * @return string
   * @version 1.21.07.07
   * @since 1.21.06.01
   */
  public function getCsvEntete($sep=';')
  { return implode($sep, array(self::FIELD_ID, self::FIELD_LABELDIVISION, self::FIELD_NOMENSEIGNANT)); }
}
