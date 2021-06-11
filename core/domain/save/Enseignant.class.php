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

  protected $genre;


  public function __construct()
  {
    parent::__construct();
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
  public function getGenre()
  { return $this->genre; }
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
  public function setGenre($genre)
  { $this->genre = $genre; }
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
  { return $this->genre.' '.$this->nomEnseignant.', '.($this->genre=='Mme' ? 'professeure principale' : 'professeur principal'); }

  public function getMatiere()
  {
    if ($this->Matiere==null) {
      $this->Matiere = $this->MatiereServices->selectLocal($this->matiereId);
    }
    return $this->Matiere;
  }

  public function getFullName()
  { return $this->genre.' '.$this->nomEnseignant.' '.$this->prenomEnseignant; }

  public function getCsvEntete($sep=';')
  { return 'id;genre;nomEnseignant;prenomEnseignant;labelMatiere'; }
  public function toCsv($sep=';')
  {
    $classVars = $this->getClassVars();
    $arrValues = array();
    $arrValues[] = $this->id;
    $arrValues[] = $this->genre;
    $arrValues[] = $this->nomEnseignant;
    $arrValues[] = $this->prenomEnseignant;
    $arrValues[] = $this->getMatiere()->getLabelMatiere();
    return implode($sep, $arrValues);
  }

  public function update(&$notif, &$msg)
  {
    $msg    = 'Mise à jour réussie.';
    $notif  = self::NOTIF_DANGER;
    $bln_OK = false;

    if ($this->id=='') {
      $msg = 'Mise à jour impossible. Identifiant non reconnu.';
    } elseif ($this->nomEnseignant=='') {
      $msg = 'Mise à jour impossible. Nom non renseigné.';
    } elseif ($this->matiereId=='') {
      $msg = 'Mise à jour impossible. Matière non renseignée.';
    } else  {
      $this->EnseignantServices->updateLocal($this);
      $notif = self::NOTIF_SUCCESS;
      $bln_OK = true;
    }
    return $bln_OK;
  }

  public function insert(&$notif, &$msg)
  {
    $msg    = 'Création réussie.';
    $notif  = self::NOTIF_DANGER;
    $bln_OK = false;

    if ($this->nomEnseignant=='') {
      $msg = 'Création impossible. Nom non renseigné.';
    } elseif ($this->matiereId=='') {
      $msg = 'Création impossible. Matière non renseignée.';
    } else  {
      $this->EnseignantServices->insertLocal($this);
      $notif = self::NOTIF_SUCCESS;
      $bln_OK = true;
    }
    return $bln_OK;
  }

  public function delete (&$notif, &$msg)
  {
    $this->EnseignantServices->deleteLocal($this);
    $msg = 'Suppression réussie.';
    $notif = self::NOTIF_SUCCESS;
  }


}
