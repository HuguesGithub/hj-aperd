<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe EnseignantMatiere
 * @author Hugues
 * @version 1.00.00
 * @since 1.00.00
 */
class EnseignantMatiere extends LocalDomain
{
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

  public function __construct()
  {
    parent::__construct();
    $this->EnseignantServices = new EnseignantServices();
    $this->MatiereServices = new MatiereServices();
  }

  public function getMatiereId()
  { return $this->matiereId; }
  public function getEnseignantId()
  { return $this->enseignantId; }

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
  { return get_class_vars('EnseignantMatiere'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return EnseignantMatiere
   * @version 1.00.00
   * @since 1.00.00
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new EnseignantMatiere(), self::getClassVars(), $row); }
  /**
   * @return EnseignantMatiereBean
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getBean()
  { return new EnseignantMatiereBean($this); }

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
