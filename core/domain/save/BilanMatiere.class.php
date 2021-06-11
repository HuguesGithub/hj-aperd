<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe BilanMatiere
 * @author Hugues
 * @version 1.00.00
 * @since 1.00.00
 */
class BilanMatiere extends LocalDomain
{
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;
  /**
   * Id technique du Compte Rendu
   * @var int $compteRenduId
   */
  protected $compteRenduId;
  /**
   * Id technique de la Matière
   * @var int $matiereId
   */
  protected $matiereId;
  /**
   * Id technique de l'Enseignant
   * @var int $enseignantId
   */
  protected $enseignantId;
  /**
   * Statut de l'Enseignant
   * @var string $status
   */
  protected $status;
  /**
   * Observations de l'Enseignant
   * @var string $observations
   */
  protected $observations;

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
  public function getMatiereId()
  { return $this->matiereId; }
  /**
   * @return int
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getEnseignantId()
  { return $this->enseignantId; }
  /**
   * @return string
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getStatus()
  { return $this->status; }
  /**
   * @return string
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getObservations()
  { return $this->observations; }

  public function setCompteRenduId($compteRenduId)
  { $this->compteRenduId = $compteRenduId; }
  public function setMatiereId($matiereId)
  { $this->matiereId = $matiereId; }
  public function setEnseignantId($enseignantId)
  { $this->enseignantId = $enseignantId; }
  public function setStatus($status)
  { $this->status = $status; }
  public function setObservations($observations)
  { $this->observations = $observations; }


  /**
   * @return string
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getStrStatut()
  {
    switch ($this->status) {
      case 'P' :
        $strStatus = 'Présent';
      break;
      case 'A' :
        $strStatus = 'Absent';
      break;
      case 'E' :
        $strStatus = 'Excusé';
      break;
      default :
        $strStatus = '';
      break;
    }
    if ($strStatus!='' && $this->getEnseignant()->getGenre()=='Mme') {
      $strStatus .= 'e';
    }
    return $strStatus;
  }
  /**
   * @return Enseignant
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getEnseignant()
  {
    if ($this->Enseignant==null) {
      $this->Enseignant = $this->EnseignantServices->selectLocal($this->enseignantId);
    }
    return $this->Enseignant;
  }
  /**
   * @return array
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getClassVars()
  { return get_class_vars('BilanMatiere'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return BilanMatiere
   * @version 1.00.00
   * @since 1.00.00
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new BilanMatiere(), self::getClassVars(), $row); }
  /**
   * @return BilanMatiereBean
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getBean()
  { return new BilanMatiereBean($this); }

  /**
   * @param array $post
   * @version 1.00.00
   * @since 1.00.00
   */
  public function setByPost($post)
  {
    foreach ($post as $key => $value) {
      $this->{$key} = stripslashes($value);
    }
  }

  public function getMatiere()
  {
    if ($this->Matiere==null) {
      $this->Matiere = $this->MatiereServices->selectLocal($this->matiereId);
    }
    return $this->Matiere;
  }
}
