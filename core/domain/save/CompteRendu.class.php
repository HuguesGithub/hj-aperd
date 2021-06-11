<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CompteRendu
 * @author Hugues
 * @version 1.00.01
 * @since 1.00.00
 */
class CompteRendu extends LocalDomain
{
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;
  protected $crKey;
  protected $anneeScolaireId;
  protected $trimestre;
  protected $divisionId;
  protected $nbEleves;
  protected $dateConseil;
  protected $administrationId;
  protected $enseignantId;
  protected $parent1;
  protected $parent2;
  protected $enfant1;
  protected $enfant2;
  protected $bilanProfPrincipal;
  protected $bilanEleves;
  protected $bilanParents;
  protected $nbEncouragements;
  protected $nbCompliments;
  protected $nbFelicitations;
  protected $nbMgComportement;
  protected $nbMgTravail;
  protected $nbMgComportementTravail;
  protected $dateRedaction;
  protected $auteurRedaction;
  protected $mailContact;
  protected $status;
  /**
   */
  public function __construct()
  {
    parent::__construct();
    $this->AdministrationServices = new AdministrationServices();
    $this->AnneeScolaireServices = new AnneeScolaireServices();
    $this->DivisionServices = new DivisionServices();
    $this->EnseignantServices = new EnseignantServices();

    $this->mandatoryFields = array(
      self::FIELD_ANNEESCOLAIRE_ID,
      self::FIELD_TRIMESTRE,
      self::FIELD_DIVISION_ID,
      self::FIELD_ADMINISTRATION_ID,
      self::FIELD_ENSEIGNANT_ID,
      self::FIELD_PARENT1,
      self::FIELD_ENFANT1,
      self::FIELD_BILANELEVES,
      self::FIELD_BILANPARENTS,
      self::FIELD_BILANPROFPRINCIPAL,
      self::FIELD_NBCOMPLIMENTS,
      self::FIELD_NBENCOURAGEMENTS,
      self::FIELD_NBFELICITATIONS,
      self::FIELD_NBMGCPT,
      self::FIELD_NBMGTVL,
      self::FIELD_NBMGCPTTVL,
      self::FIELD_DATEREDACTION,
      self::FIELD_AUTEURREDACTION,
    );
  }
  /**
   * @return int
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getId()
  { return $this->id; }

  public function getCrKey()
  { return $this->crKey; }
  public function getAnneeScolaireId()
  { return $this->anneeScolaireId; }
  public function getDivisionId()
  { return $this->divisionId; }
  public function getTrimestre()
  { return $this->trimestre; }
  public function getStatus()
  { return $this->status; }
  public function getDateConseil()
  { return $this->dateConseil; }
  public function getAdministrationId()
  { return $this->administrationId; }
  /**
   * @param int $id
   * @version 1.00.00
   * @since 1.00.00
   */
  public function setId($id)
  { $this->id=$id; }
  public function setCrKey($crKey)
  { $this->crKey=$crKey; }
  public function setStatus($status)
  { $this->status = $status; }
  public function setDateConseil($dateConseil)
  { $this->dateConseil = $dateConseil; }
  public function setAdministrationId($administrationId)
  { $this->administrationId = $administrationId; }
  /**
   * @return array
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getClassVars()
  { return get_class_vars('CompteRendu'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return CompteRendu
   * @version 1.00.00
   * @since 1.00.00
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new CompteRendu(), self::getClassVars(), $row); }
  /**
   * @return AnneeScolaire
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getAnneeScolaire()
  {
    if ($this->AnneeScolaire==null) {
      $this->AnneeScolaire = $this->AnneeScolaireServices->selectLocal($this->anneeScolaireId);
    }
    return $this->AnneeScolaire;
  }
  /**
   * @return ClasseScolaire
   * @version 1.00.01
   * @since 1.00.00
   */
  public function getDivision()
  {
    if ($this->Division==null) {
      $this->Division = $this->DivisionServices->selectLocal($this->divisionId);
    }
    return $this->Division;
  }
  /**
   * @return Administration
   * @version 1.00.01
   * @since 1.00.00
   */
  public function getAdministration()
  {
    if ($this->Administration==null) {
      $this->Administration = $this->AdministrationServices->selectLocal($this->administrationId);
    }
    return $this->Administration;
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
   * @return CompteRenduBean
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getBean()
  { return new CompteRenduBean($this); }
  /**
   * @return boolean
   * @version 1.00.00
   * @since 1.00.00
   */
  public function checkMandatory(&$strErrors='')
  {
    while (!empty($this->mandatoryFields)) {
      $field = array_shift($this->mandatoryFields);
      if ($this->{$field}=='' || $this->{$field}==self::CST_DEFAULT_SELECT) {
        $strErrors = $field;
        return false;
      }
    }
    return true;
  }
  /**
   * @param string $field
   * @return mixed
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getValue($field)
  { return $this->{$field}; }

  public function setValue($field, $value)
  { $this->{$field} = $value; }
  /**
   * @param array $post
   * @version 1.00.00
   * @since 1.00.00
   */
  public function setByPost($post)
  {
    foreach ($post as $key => $value) {
      if (!in_array($key, array(self::AJAX_SAVE))) {
        if (is_array($value)) {
          $this->{$key} = $value;
        } else {
          $this->{$key} = stripslashes($value);
        }
      }
    }
  }

  /**
   * @return string
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getStrParentsDelegues()
  {
    if ($this->parent1=='') {
      $str = '';
    } elseif ($this->parent2=='') {
      $str = "du parent délégué ".$this->parent1;
    } else {
      $str = "des parents délégués ".$this->parent1." et ".$this->parent2;
    }
    return $str;
  }

  /**
   * @return string
   * @version 1.00.01
   * @since 1.00.00
   */
  public function getStrElevesDelegues()
  {
    if ($this->enfant2=='') {
      if ($this->enfant1=='') {
        $str = " et d'aucun élève délégué.";
      } else {
        $str = " et de l'élève délégué ".$this->enfant1.".";
      }
    } else {
      $str = " et des élèves délégués ".$this->enfant1." et ".$this->enfant2.".";
    }
    return $str;
  }

  /**
   * @return string
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getNotifications()
  { return $this->notifications; }
  /**
   * @param string $notifications
   * @version 1.00.00
   * @since 1.00.00
   */
  public function setNotifications($notifications)
  { $this->notifications = $notifications; }

}
