<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe ParentDelegue
 * @author Hugues
 * @version 1.21.06.11
 * @since 1.21.06.11
 */
class ParentDelegue extends LocalDomain
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
   * Id technique de l'Adulte
   * @var int $parentId
   */
  protected $parentId;
  /**
   * Id technique de la division
   * @var int $divisionId
   */
  protected $divisionId;


  //////////////////////////////////////////////////:
  // GETTERS & SETTERS
  //////////////////////////////////////////////////:
  /**
   * @return int
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  public function getParentId()
  { return $this->parentId; }
  /**
   * @return int
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  public function getDivisionId()
  { return $this->divisionId; }
  /**
   * @param int $parentId
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  public function setParentId($parentId)
  { $this->parentId = $parentId; }
  /**
   * @param int $divisionId
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  public function setDivisionId($divisionId)
  { $this->divisionId = $divisionId; }


  //////////////////////////////////////////////////:
  // CONSTRUCT - CLASSVARS - CONVERT - BEAN
  //////////////////////////////////////////////////:
  /**
   * @param array $attributes
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->ParentDelegueServices = new ParentDelegueServices();
    $this->Services = new ParentDelegueServices();
    $this->DivisionServices = new DivisionServices();
    $this->AdulteServices = new AdulteServices();
  }
  /**
   * @return array
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  public function getClassVars()
  { return get_class_vars('ParentDelegue'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return ParentDelegue
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new ParentDelegue(), self::getClassVars(), $row); }
  /**
   * @return ParentDelegueBean
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  public function getBean()
  { return new ParentDelegueBean($this); }

  //////////////////////////////////////////////////
  // GETTERS OBJETS LIES
  //////////////////////////////////////////////////
  /**
   * @return Adulte
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  public function getAdulte()
  {
    if ($this->Adulte==null) {
      $this->Adulte = $this->AdulteServices->selectLocal($this->parentId);
    }
    return $this->Adulte;
  }
  /**
   * @return Division
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  public function getDivision()
  {
    if ($this->Division==null) {
      $this->Division = $this->DivisionServices->selectLocal($this->divisionId);
    }
    return $this->Division;
  }

  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////
  /**
   * @param string $sep
   * @return string
   * @version 1.21.06.12
   * @since 1.21.06.11
   */
  public function getCsvEntete($sep=self::SEP)
  { return implode($sep, array(self::FIELD_ID, self::FIELD_NOMPARENT, self::FIELD_PRENOMPARENT, self::FIELD_LABELDIVISION)); }
  /**
   * @return string
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  public function getLabelComplet()
  { return $this->getAdulte()->getFullName().self::CST_BLANK.$this->getDivision()->getLabelDivision(); }
  /**
   * @param string $sep
   * @return string
   * @version 1.21.06.12
   * @since 1.21.06.11
   */
  public function toCsv($sep=self::SEP)
  {
    $arrValues = array();
    $arrValues[] = $this->id;
    $arrValues[] = $this->getAdulte()->getNomParent();
    $arrValues[] = $this->getAdulte()->getPrenomParent();
    $arrValues[] = $this->getDivision()->getLabelDivision();
    return implode($sep, $arrValues);
  }
  /**
   * @param string $rowContent
   * @param string $sep
   * @param string &$notif
   * @param string &$msg
   * @return boolean
   * @version 1.21.06.12
   * @since 1.21.06.11
   */
  public function controleImportRow($rowContent, $sep=self::SEP, &$notif, &$msg)
  {
    list($id, $nomAdulte, $prenomAdulte, $labelDivision) = explode($sep, $rowContent);
    $this->setId($id);
    // On vérifie l'existence de l'Adulte.
    $argFilters = array(self::FIELD_NOMPARENT=>$nomAdulte, self::FIELD_PRENOMPARENT=>$prenomAdulte);
    $Adultes = $this->AdulteServices->getAdultesWithFilters($argFilters);
    if (count($Adultes)!=1) {
      $notif = self::NOTIF_DANGER;
      $msg   = self::MSG_ERREUR_CONTROL_INEXISTENCE;
      return true;
    }
    $Adulte = array_shift($Adultes);
    $this->setParentId(trim($Adulte->getId()));
    // On vérifie l'existence de la Division
    $Divisions = $this->DivisionServices->getDivisionsWithFilters(array(self::FIELD_LABELDIVISION=>str_replace(self::EOL, '', $labelDivision)));
    if (empty($Divisions)) {
      $notif = self::NOTIF_DANGER;
      $msg   = self::MSG_ERREUR_CONTROL_INEXISTENCE;
      return true;
    }
    $Division = array_shift($Divisions);
    $this->setDivisionId(trim($Division->getId()));

    if (!$this->controleDonnees($notif, $msg)) {
      return true;
    }
    // Si les contrôles sont okay, on peut insérer ou mettre à jour
    if ($id=='') {
      // Si id n'est pas renseigné. C'est une création. Il faut vérifier que le label n'existe pas déjà.
      $this->Services->insertLocal($this);
    } else {
      $ObjectInBase = $this->Services->selectLocal($id);
      if ($ObjectInBase->getId()=='') {
        // Sinon, si id n'existe pas, c'est une création. Cf au-dessus
        $this->Services->insertLocal($this);
      } else {
        // Si id existe, c'est une édition, même contrôle que ci-dessus.
        $this->setId($id);
        $this->Services->updateLocal($this);
      }
    }
    return false;
  }
  /**
   * @param string &$notif
   * @param string &$msg
   * @version 1.21.06.12
   * @since 1.21.06.11
   */
  public function controleDonnees(&$notif, &$msg)
  {
    // Vérification de l'id du Parent
    $Adulte = $this->AdulteServices->selectLocal($this->parentId);
    if ($Adulte->getId()=='') {
      $notif = self::NOTIF_DANGER;
      $msg   = self::MSG_ERREUR_CONTROL_EXISTENCE;
      return false;
    }
    // Vérification de l'id de la Division
    $Division = $this->DivisionServices->selectLocal($this->divisionId);
    if ($Division->getId()=='') {
      $notif = self::NOTIF_DANGER;
      $msg   = self::MSG_ERREUR_CONTROL_EXISTENCE;
      return false;
    }
    return true;
  }
}
