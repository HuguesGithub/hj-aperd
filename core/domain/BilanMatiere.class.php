<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe BilanMatiere
 * @author Hugues
 * @version 1.21.07.21
 * @since 1.21.06.04
 */
class BilanMatiere extends LocalDomain
{
  //////////////////////////////////////////////////
  // ATTRIBUTES
  //////////////////////////////////////////////////
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
   * Statut de l'Enseignant
   * @var string $status
   */
  protected $status;
  /**
   * Moyenne de la divison
   * @var float $moyenneDivision
   */
  protected $moyenneDivision;
  /**
   * Observations de l'Enseignant
   * @var string $observations
   */
  protected $observations;

  //////////////////////////////////////////////////
  // GETTERS & SETTERS
  //////////////////////////////////////////////////
  /**
   * @return int
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getCompteRenduId()
  { return $this->compteRenduId; }
  /**
   * @return int
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getMatiereId()
  { return $this->matiereId; }
  /**
   * @return string
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getStatus()
  { return $this->status; }
  /**
   * @return float
   * @version 1.21.07.17
   * @since 1.21.07.17
   */
  public function getMoyenneDivision()
  { return $this->moyenneDivision; }
  /**
   * @return string
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getObservations()
  { return $this->observations; }
  /**
   * @param int $compteRenduId
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function setCompteRenduId($compteRenduId)
  { $this->compteRenduId = $compteRenduId; }
  /**
   * @param int $matiereId
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function setMatiereId($matiereId)
  { $this->matiereId = $matiereId; }
  /**
   * @param string $status
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function setStatus($status)
  { $this->status = $status; }
  /**
   * @param float $moyenneDivision
   * @version 1.21.07.17
   * @since 1.21.07.17
   */
  public function setMoyenneDivision($moyenneDivision)
  { $this->moyenneDivision = $moyenneDivision; }
  /**
   * @param string $observations
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function setObservations($observations)
  { $this->observations = $observations; }

  //////////////////////////////////////////////////
  // CONSTRUCT - CLASSVARS - CONVERT - BEAN
  //////////////////////////////////////////////////
  /**
   * @param array $attributes
   * @version 1.21.07.17
   * @since 1.21.07.17
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->MatiereServices     = new MatiereServices();
    $this->CompteRenduServices = new CompteRenduServices();
  }
  /**
   * @return array
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getClassVars()
  { return get_class_vars('BilanMatiere'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return BilanMatiere
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new BilanMatiere(), self::getClassVars(), $row); }
  /**
   * @return BilanMatiereBean
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getBean()
  { return new BilanMatiereBean($this); }

  //////////////////////////////////////////////////
  // GETTERS OBJETS LIES
  //////////////////////////////////////////////////
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
  /**
   * @return CompteRendu
   * @version 1.21.07.20
   * @since 1.21.07.20
   */
  public function getCompteRendu()
  {
    if ($this->CompteRendu==null) {
      $this->CompteRendu = $this->CompteRenduServices->selectLocal($this->compteRenduId);
    }
    return $this->CompteRendu;
  }

  //////////////////////////////////////////////////
  // METHODS
  //////////////////////////////////////////////////
  /**
   * @return string
   * @version 1.21.07.21
   * @since 1.21.06.04
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
    return $strStatus;
  }

  /**
   * @param array $post
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function setByPost($post)
  {
    foreach ($post as $key => $value) {
      $this->{$key} = stripslashes($value);
    }
  }

}
