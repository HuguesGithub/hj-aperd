<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe EnseignantMatiere
 * @author Hugues
 * @version 1.21.06.04
 * @since 1.21.06.04
 */
class EnseignantMatiere extends LocalDomain
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
   * Id technique de l'enseignant
   * @var int $enseignantId
   */
  protected $enseignantId;
  /**
   * Id technique de la matière
   * @var int $matiereId
   */
  protected $matiereId;

  //////////////////////////////////////////////////
  // GETTERS & SETTERS
  //////////////////////////////////////////////////
  /**
   * @return int
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getEnseignantId()
  { return $this->enseignantId; }
  /**
   * @return int
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getMatiereId()
  { return $this->matiereId; }
  /**
   * @param int $enseignantId
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function setEnseignantId($enseignantId)
  { $this->enseignantId = $enseignantId; }
  /**
   * @param int $matiereId
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function setMatiereId($matiereId)
  { $this->matiereId = $matiereId; }

  //////////////////////////////////////////////////
  // CONSTRUCT - CLASSVARS - CONVERT - BEAN
  //////////////////////////////////////////////////
  /**
   * @param array $attributes
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->EnseignantServices = new EnseignantServices();
    $this->MatiereServices = new MatiereServices();

    $this->mandatoryFields = array(
      self::FIELD_ENSEIGNANT_ID,
      self::FIELD_MATIERE_ID,
    );
  }
  /**
   * @return array
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getClassVars()
  { return get_class_vars('EnseignantMatiere'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return EnseignantMatiere
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new EnseignantMatiere(), self::getClassVars(), $row); }
  /**
   * @return EnseignantMatiereBean
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getBean()
  { return new EnseignantMatiereBean($this); }

  //////////////////////////////////////////////////
  // GETTERS OBJETS LIES
  //////////////////////////////////////////////////
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
  /**
   * @return Matiere
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getMatiere()
  {
    if ($this->Matiere==null) {
      $this->Matiere = $this->MatiereServices->selectLocal($this->matiereId);
    }
    return $this->Matiere;
  }



}
