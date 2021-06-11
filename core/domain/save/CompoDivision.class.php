<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CompoDivision
 * @author Hugues
 * @version 1.00.00
 * @since 1.00.00
 */
class CompoDivision extends LocalDomain
{
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;
  /**
   * Id technique de l'année scolaire
   * @var int $anneeScolaireId
   */
  protected $anneeScolaireId;
  /**
   * Id technique de la division
   * @var int $divisionId
   */
  protected $divisionId;
  /**
   * Id technique de la matière
   * @var int $matiereId
   */
  protected $matiereId;
  /**
   * Id technique de l'enseignant
   * @var int $enseignantId
   */
  protected $enseignantId;

  public function __construct()
  {
    parent::__construct();
    $this->AnneeScolaireServices = new AnneeScolaireServices();
    $this->DivisionServices = new DivisionServices();
    $this->EnseignantServices = new EnseignantServices();
    $this->MatiereServices = new MatiereServices();
  }

  public function getAnneeScolaireId()
  { return $this->anneeScolaireId; }
  public function getDivisionId()
  { return $this->divisionId; }
  public function getMatiereId()
  { return $this->matiereId; }
  public function getEnseignantId()
  { return $this->enseignantId; }

  public function setAnneeScolaireId($anneeScolaireId)
  { $this->anneeScolaireId = $anneeScolaireId; }
  public function setDivisionId($divisionId)
  { $this->divisionId = $divisionId; }
  public function setMatiereId($matiereId)
  { $this->matiereId = $matiereId; }
  public function setEnseignantId($enseignantId)
  { $this->enseignantId = $enseignantId; }
  /**
   * @return int
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getId()
  { return $this->id; }
  /**
   * @param int $id
   * @version 1.00.00
   * @since 1.00.00
   */
  public function setId($id)
  { $this->id=$id; }
  /**
   * @return array
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getClassVars()
  { return get_class_vars('CompoDivision'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return CompoDivision
   * @version 1.00.00
   * @since 1.00.00
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new CompoDivision(), self::getClassVars(), $row); }
  /**
   * @return CompoDivisionBean
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getBean()
  { return new CompoDivisionBean($this); }

  public function getAnneeScolaire()
  {
    if ($this->AnneeScolaire==null) {
      $this->AnneeScolaire = $this->AnneeScolaireServices->selectLocal($this->anneeScolaireId);
    }
    return $this->AnneeScolaire;
  }
  public function getDivision()
  {
    if ($this->Division==null) {
      $this->Division = $this->DivisionServices->selectLocal($this->divisionId);
    }
    return $this->Division;
  }
  public function getMatiere()
  {
    if ($this->Matiere==null) {
      $this->Matiere = $this->MatiereServices->selectLocal($this->matiereId);
    }
    return $this->Matiere;
  }
  public function getEnseignant()
  {
    if ($this->Enseignant==null) {
      $this->Enseignant = $this->EnseignantServices->selectLocal($this->enseignantId);
    }
    return $this->Enseignant;
  }
}
