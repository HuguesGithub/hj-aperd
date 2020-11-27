<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe Enseignant
 * @author Hugues
 * @version 1.00.00
 * @since 1.00.00
 */
class Enseignant extends LocalDomain
{
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;
  /**
   * Nom de l'enseignant
   * @var string $nomEnseignant
   */
  protected $nomEnseignant;
  /**
   * Id technique de la Matière
   * @var int $matiereId
   */
  protected $matiereId;
  /**
   * Actif au collège, ou l'ayant quitté.
   * @var bool $status
   */
  protected $status;
  public function __construct()
  {
    parent::__construct();
    $this->MatiereServices = new MatiereServices();
  }
  /**
   * @return int
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getId()
  { return $this->id; }
  /**
   * @return string
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getNomEnseignant()
  { return $this->nomEnseignant; }
  /**
   * @return int
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getMatiereId()
  { return $this->matiereId; }
  public function getStatus()
  { return $this->status; }
  /**
   * @param int $id
   * @version 1.00.00
   * @since 1.00.00
   */
  public function setId($id)
  { $this->id=$id; }
  /**
   * @param string $nomEnseignant
   * @version 1.00.00
   * @since 1.00.00
   */
  public function setNomEnseignant($nomEnseignant)
  { $this->nomEnseignant=$nomEnseignant; }
  /**
   * @param int $matiereId
   * @version 1.00.00
   * @since 1.00.00
   */
  public function setMatiereId($matiereId)
  { $this->matiereId=$matiereId; }
  public function setStatus($status)
  { $this->status = $status; }
  /**
   * @return array
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getClassVars()
  { return get_class_vars('Enseignant'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return Enseignant
   * @version 1.00.00
   * @since 1.00.00
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new Enseignant(), self::getClassVars(), $row); }
  /**
   * @return EnseignantBean
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getBean()
  { return new EnseignantBean($this); }
  /**
   * @return string
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getProfPrincipal()
  {
    if (substr($this->nomEnseignant, 0, 3)=='Mme') {
      $profPrincipal = 'professeure principale';
    } else {
      $profPrincipal = 'professeur principal';
    }
    return $this->nomEnseignant.", $profPrincipal";
  }
  public function getMatiere()
  {
    if ($this->Matiere==null) {
      $this->Matiere = $this->MatiereServices->selectLocal($this->matiereId);
    }
    return $this->Matiere;
  }
}
