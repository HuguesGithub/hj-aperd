<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CompoDivision
 * @author Hugues
 * @version 1.21.06.04
 * @since 1.21.06.04
 */
class CompoDivision extends LocalDomain
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
   * Id technique de la division
   * @var int $divisionId
   */
  protected $divisionId;
  /**
   * Id technique de l'enseignantMatiere
   * @var int $enseignantMatiereId
   */
  protected $enseignantMatiereId;

  //////////////////////////////////////////////////
  // GETTERS & SETTERS
  //////////////////////////////////////////////////
  /**
   * @return int
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getDivisionId()
  { return $this->divisionId; }
  /**
   * @return int
   * @version 1.21.07.07
   * @since 1.21.07.07
   */
  public function getEnseignantMatiereId()
  { return $this->enseignantMatiereId; }
  /**
   * @param int $divisionId
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function setDivisionId($divisionId)
  { $this->divisionId = $divisionId; }
  /**
   * @param int $enseignantId
   * @version 1.21.07.07
   * @since 1.21.07.07
   */
  public function setEnseignantMatiereId($enseignantMatiereId)
  { $this->enseignantMatiereId = $enseignantMatiereId; }

  //////////////////////////////////////////////////
  // CONSTRUCT - CLASSVARS - CONVERT - BEAN
  //////////////////////////////////////////////////
  /**
   * @param array $attributes
   * @version 1.21.07.07
   * @since 1.21.07.07
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->Services = new CompoDivisionServices();
    $this->DivisionServices = new DivisionServices();
    $this->EnseignantMatiereServices = new EnseignantMatiereServices();
  }
  /**
   * @return array
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getClassVars()
  { return get_class_vars('CompoDivision'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return CompoDivision
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new CompoDivision(), self::getClassVars(), $row); }
  /**
   * @return CompoDivisionBean
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getBean()
  { return new CompoDivisionBean($this); }


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
   * @return EnseignantMatiere
   * @version 1.21.07.07
   * @since 1.21.07.07
   */
  public function getEnseignantMatiere()
  {
    if ($this->EnseignantMatiere==null) {
      $this->EnseignantMatiere = $this->EnseignantMatiereServices->selectLocal($this->enseignantMatiereId);
    }
    return $this->EnseignantMatiere;
  }
  /**
   * @return Matiere
   * @version 1.21.07.07
   * @since 1.21.06.04
   */
  public function getMatiere()
  { return $this->getEnseignantMatiere()->getMatiere(); }
  /**
   * @return Enseignant
   * @version 1.21.07.07
   * @since 1.21.07.07
   */
  public function getEnseignant()
  { return $this->getEnseignantMatiere()->getEnseignant(); }

  //////////////////////////////////////////////////
  // METHODS
  //////////////////////////////////////////////////
  /**
   * @param string $sep
   * @return string
   * @version 1.21.07.07
   * @since 1.21.06.08
   */
  public function getCsvEntete($sep=';')
  { return implode($sep, array(self::FIELD_ID, self::FIELD_LABELDIVISION, self::FIELD_NOMENSEIGNANT, self::FIELD_LABELMATIERE)); }
  /**
   * @param string $sep
   * @return string
   * @version 1.21.07.07
   * @since 1.21.06.04
   */
  public function toCsv($sep=';')
  {
    $arrValues = array();
    $arrValues[] = $this->id;
    $arrValues[] = $this->getDivision()->getLabelDivision();
    $arrValues[] = $this->getEnseignant()->getNomEnseignant();
    $arrValues[] = $this->getMatiere()->getLabelMatiere();
    return implode($sep, $arrValues);
  }

  /**
   * @param string &$notif
   * @param string &$msg
   * @version 1.21.07.07
   * @since 1.21.07.07
   */
  public function controleDonnees(&$notif, &$msg)
  {
    $returned = true;
    // Tous les champs doivent être renseignés
    if (empty($this->divisionId)) {
      $notif = self::NOTIF_DANGER;
      $msg   = sprintf(self::MSG_ERREUR_CONTROL_EXISTENCE_NORMEE, 'Division');
      $returned = false;
    }
    if ($returned && empty($this->enseignantMatiereId)) {
      $notif = self::NOTIF_DANGER;
      $msg   = sprintf(self::MSG_ERREUR_CONTROL_EXISTENCE_NORMEE, 'Enseignant / Matière');
      $returned = false;
    }
    return $returned;
  }

}
