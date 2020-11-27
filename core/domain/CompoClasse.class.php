<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CompoClasse
 * @author Hugues
 * @version 1.00.00
 * @since 1.00.00
 */
class CompoClasse extends LocalDomain
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
   * Id technique de la classe
   * @var int $classeId
   */
  protected $classeId;
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
    $this->ClasseScolaireServices = new ClasseScolaireServices();
    $this->EnseignantServices = new EnseignantServices();
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
  { return get_class_vars('CompoClasse'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return CompoClasse
   * @version 1.00.00
   * @since 1.00.00
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new CompoClasse(), self::getClassVars(), $row); }
  /**
   * @return CompoClasseBean
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getBean()
  { return new CompoClasseBean($this); }

  public function getAnneeScolaire()
  {
    if ($this->AnneeScolaire==null) {
      $this->AnneeScolaire = $this->AnneeScolaireServices->selectLocal($this->anneeScolaireId);
    }
    return $this->AnneeScolaire;
  }
  public function getClasseScolaire()
  {
    if ($this->ClasseScolaire==null) {
      $this->ClasseScolaire = $this->ClasseScolaireServices->selectLocal($this->classeId);
    }
    return $this->ClasseScolaire;
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
