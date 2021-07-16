<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CompteRendu
 * @author Hugues
 * @version 1.21.07.16
 * @since 1.21.06.04
 */
class CompteRendu extends LocalDomain
{
  //////////////////////////////////////////////////:
  // ATTRIBUTES
  //////////////////////////////////////////////////:
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;
  protected $trimestre;
  protected $divisionId;
  protected $nbEleves;
  protected $dateConseil;
  protected $administrationId;
  protected $profPrincId;
  protected $delegueEleve1Id;
  protected $delegueEleve2Id;
  protected $delegueParent1Id;
  protected $delegueParent2Id;
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
  protected $status;

  //////////////////////////////////////////////////
  // GETTERS & SETTERS
  //////////////////////////////////////////////////
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

  public function setStatus($status)
  { $this->status = $status; }
  public function setDateConseil($dateConseil)
  { $this->dateConseil = $dateConseil; }
  public function setAdministrationId($administrationId)
  { $this->administrationId = $administrationId; }

  //////////////////////////////////////////////////
  // CONSTRUCT - CLASSVARS - CONVERT - BEAN
  //////////////////////////////////////////////////
  /**
   * @param array $attributes
   * @version 1.21.07.15
   * @since 1.21.06.04
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->AdministrationServices = new AdministrationServices();
    $this->AnneeScolaireServices = new AnneeScolaireServices();
    $this->DivisionServices = new DivisionServices();
    $this->EnseignantServices = new EnseignantServices();
  }
  /**
   * @return array
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getClassVars()
  { return get_class_vars('CompteRendu'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return CompteRendu
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new CompteRendu(), self::getClassVars(), $row); }
  /**
   * @return CompteRenduBean
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getBean()
  { return new CompteRenduBean($this); }

  //////////////////////////////////////////////////
  // GETTERS OBJETS LIES
  //////////////////////////////////////////////////
  /**
   * @return ClasseScolaire
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
   * @return Administration
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getAdministration()
  {
    if ($this->Administration==null) {
      $this->Administration = $this->AdministrationServices->selectLocal($this->administrationId);
    }
    return $this->Administration;
  }

  //////////////////////////////////////////////////
  // METHODS
  //////////////////////////////////////////////////
  /**
   * @param string $field
   * @return mixed
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getValue($field)
  { return $this->{$field}; }
  /**
   * @param string $field
   * @param mixed $value
   * @version 1.21.07.15
   * @since 1.21.06.04
   */
  public function setValue($field, $value)
  { $this->{$field} = $value; }

  /**
   * @return string
   * @version 1.21.07.16
   * @since 1.21.07.16
   */
  public function getFullName()
  { return $this->getDivision()->getLabelDivision().' T'.$this->getTrimestre(); }





  /**
   * @param array $post
   * @version 1.21.06.04
   * @since 1.21.06.04
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
   * @version 1.21.06.04
   * @since 1.21.06.04
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
   * @version 1.21.06.04
   * @since 1.21.06.04
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
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getNotifications()
  { return $this->notifications; }
  /**
   * @param string $notifications
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function setNotifications($notifications)
  { $this->notifications = $notifications; }

  /**
   * @param string &$notif
   * @param string &$msg
   * @version 1.21.07.05
   * @since 1.21.07.05
   */
  public function controleDonnees(&$notif, &$msg)
  {
    // L'Année Scolaire doit être renseigné
    if (empty($this->anneeScolaireId) || $this->anneeScolaireId==-1) {
      $notif = self::NOTIF_DANGER;
      $msg   = self::MSG_ERREUR_CONTROL_EXISTENCE;
      return false;
    }
    return true;
  }
}
