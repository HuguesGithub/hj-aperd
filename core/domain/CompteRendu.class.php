<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CompteRendu
 * @author Hugues
 * @version 1.21.07.17
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
   * @version 1.21.07.16
   * @since 1.21.06.04
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->AdministrationServices = new AdministrationServices();
    $this->AdulteServices         = new AdulteServices();
    $this->AnneeScolaireServices = new AnneeScolaireServices();
    $this->DivisionServices = new DivisionServices();
    $this->EleveServices          = new EleveServices();
    $this->EnseignantServices = new EnseignantServices();
    $this->ParentDelegueServices = new ParentDelegueServices();
    $this->Services = new CompteRenduServices();
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
  /**
   * @return Enseignant
   * @version 1.21.07.16
   * @since 1.21.07.16
   */
  public function getProfPrincipal()
  {
    if ($this->ProfPrincipal==null) {
      $this->ProfPrincipal = $this->EnseignantServices->selectLocal($this->profPrincId);
    }
    return $this->ProfPrincipal;
  }
  /**
   * @return Adulte
   * @version 1.21.07.17
   * @since 1.21.07.17
   */
  public function getAuteurRedaction()
  {
    if ($this->AuteurRedaction==null) {
      $this->AuteurRedaction = $this->AdulteServices->selectLocal($this->auteurRedaction);
    }
    return $this->AuteurRedaction;
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
   * @return Adulte
   * @version 1.21.07.17
   * @since 1.21.07.17
   */
  public function getAdulteByLogin($login)
  {
    $ParentDelegues = $this->ParentDelegueServices->getParentDeleguesWithFilters(array(self::FIELD_DIVISION_ID=>$this->getDivision()->getId()));
    while (!empty($ParentDelegues)) {
      $ParentDelegue = array_shift($ParentDelegues);
      if (strtolower($ParentDelegue->getAdulte()->getLogin())==strtolower($login)) {
        return $ParentDelegue->getAdulte();
      }
    }
    return new Adulte();
  }
  /**
   * @return string
   * @version 1.21.07.16
   * @since 1.21.06.04
   */
  public function getStrParentsDelegues()
  {
    $ParentDelegue1 = $this->ParentDelegueServices->selectLocal($this->delegueParent1Id);
    $Adulte1 = $this->AdulteServices->selectLocal($ParentDelegue1->getParentId());
    $ParentDelegue2 = $this->ParentDelegueServices->selectLocal($this->delegueParent2Id);
    $Adulte2 = $this->AdulteServices->selectLocal($ParentDelegue2->getParentId());
    if ($this->delegueParent1Id=='') {
      $str = '';
    } elseif ($this->delegueParent2Id=='') {
      $str = "du parent délégué ".$Adulte1->getFullName();
    } else {
      $str = "des parents délégués ".$Adulte1->getFullName()." et ".$Adulte2->getFullName();
    }
    return $str;
  }

  /**
   * @return string
   * @version 1.21.07.16
   * @since 1.21.06.04
   */
  public function getStrElevesDelegues()
  {
    $Eleve1 = $this->EleveServices->selectLocal($this->delegueEleve1Id);
    $Eleve2 = $this->EleveServices->selectLocal($this->delegueEleve2Id);
    if ($Eleve2->getId()=='') {
      if ($Eleve1->getId()=='') {
        $str = " et d'aucun élève délégué.";
      } else {
        $str = " et de l'élève délégué ".$Eleve1->getFullName().".";
      }
    } else {
      $str = " et des élèves délégués ".$Eleve1->getFullName()." et ".$Eleve2->getFullName().".";
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
   * @version 1.21.07.16
   * @since 1.21.07.05
   */
  public function controleDonnees(&$notif, &$msg)
  {
    return true;
  }

  public function getUrlPdf()
  { return '2021-2022-T'.$this->getTrimestre().'-'.$this->getDivision->getLabelDivision().'.pdf'; }
}
