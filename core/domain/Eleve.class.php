<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe Eleve
 * @author Hugues
 * @version 1.21.06.17
 * @since 1.21.06.04
 */
class Eleve extends LocalDomain
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
   * Nom de l'élève
   * @var string $nomEleve
   */
  protected $nomEleve;
  /**
   * Prénom de l'élève
   * @var string $prenomEleve
   */
  protected $prenomEleve;
  /**
   * Id de la division
   * @var int $divisionId
   */
  protected $divisionId;
  /**
   * Est délégué ?
   * @var boolean $delegue
   */
  protected $delegue;

  //////////////////////////////////////////////////
  // GETTERS & SETTERS
  //////////////////////////////////////////////////
  /**
   * @return string
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getNomEleve()
  { return $this->nomEleve; }
  /**
   * @return string
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getPrenomEleve()
  { return $this->prenomEleve; }
  /**
   * @return int
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getDivisionId()
  { return $this->divisionId; }
  /**
   * @return int
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  public function isDelegue()
  { return ($this->delegue==1); }
  /**
   * @param string $nomEleve
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function setNomEleve($nomEleve)
  { $this->nomEleve = stripslashes($nomEleve); }
  /**
   * @param string $prenomEleve
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function setPrenomEleve($prenomEleve)
  { $this->prenomEleve = stripslashes($prenomEleve); }
  /**
   * @param int $divisionId
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function setDivisionId($divisionId)
  { $this->divisionId = $divisionId; }
  /**
   * @param int $delegue
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  public function setDelegue($delegue)
  { $this->delegue = $delegue; }

  //////////////////////////////////////////////////
  // CONSTRUCT - CLASSVARS - CONVERT - BEAN
  //////////////////////////////////////////////////
  /**
   * @param array $attributes
   * @version 1.21.06.11
   * @since 1.21.06.04
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->DivisionServices = new DivisionServices();
    $this->EleveServices = new EleveServices();
    $this->Services = new EleveServices();
  }
  /**
   * @return array
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getClassVars()
  { return get_class_vars('Eleve'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return Eleve
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new Eleve(), self::getClassVars(), $row); }
  /**
   * @return EleveBean
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getBean()
  { return new EleveBean($this); }

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

  //////////////////////////////////////////////////
  // METHODS
  //////////////////////////////////////////////////
  /**
   * @param string $sep
   * @return string
   * @version 1.21.06.11
   * @since 1.21.06.01
   */
  public function getCsvEntete($sep=';')
  { return implode($sep, array(self::FIELD_ID, self::FIELD_NOMELEVE, self::FIELD_PRENOMELEVE, self::FIELD_LABELDIVISION, self::FIELD_DELEGUE)); }
  /**
   * @param string $sep
   * @return string
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function toCsv($sep=';')
  {
    $arrValues = array();
    $arrValues[] = $this->id;
    $arrValues[] = $this->nomEleve;
    $arrValues[] = $this->prenomEleve;
    $arrValues[] = $this->getDivision()->getLabelDivision();
    $arrValues[] = ($this->delegue==1 ? 'Oui' : 'Non');
    return implode($sep, $arrValues);
  }
  /**
   * @return string
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getNomComplet()
  { return $this->nomEleve.' '.$this->prenomEleve; }
  /**
   * @return string
   * @version 1.21.07.16
   * @since 1.21.07.16
   */
  public function getFullName()
  { return $this->nomEleve.' '.$this->prenomEleve; }
  /**
   * @param string $rowContent
   * @param string $sep
   * @param string &$notif
   * @param string &$msg
   * @return boolean
   * @version 1.21.06.17
   * @since 1.21.06.11
   */
  public function controleImportRow($rowContent, $sep, &$notif, &$msg)
  {
    list($id, $nomEleve, $prenomEleve, $labelDivision, $delegue) = explode($sep, $rowContent);
    $this->setId($id);
    $this->setNomEleve(trim($nomEleve));
    $this->setPrenomEleve(trim($prenomEleve));

    $Divisions = $this->DivisionServices->getDivisionsWithFilters(array(self::FIELD_LABELDIVISION=>$labelDivision));
    if (empty($Divisions)) {
      $notif = self::NOTIF_DANGER;
      $msg   = self::MSG_ERREUR_CONTROL_INEXISTENCE;
      return true;
    }
    $Division = array_shift($Divisions);
    $this->setDivisionId(trim($Division->getId()));
    $this->setDelegue(trim(str_replace(self::EOL, '', $delegue)));

    return $this->controleDonneesAndAct($this, $notif, $msg);
  }
  /**
   * @param string &$notif
   * @param string &$msg
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  public function controleDonnees(&$notif, &$msg)
  {
    // Le nom de l'Elève doit être renseigné
    if (empty($this->nomEleve)) {
      $notif = self::NOTIF_DANGER;
      $msg   = self::MSG_ERREUR_CONTROL_EXISTENCE;
      return false;
    }
    // TODO Vérifier la validité de la division.
    return true;
  }

}
