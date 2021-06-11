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
   * Id technique de l'année scolaire
   * @var int $anneeScolaireId
   */
  protected $anneeScolaireId;
  /**
   * Id technique de la division
   * @var int $divisionId
   */
  protected $divisionId;
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

  //////////////////////////////////////////////////
  // GETTERS & SETTERS
  //////////////////////////////////////////////////
  /**
   * @return int
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getAnneeScolaireId()
  { return $this->anneeScolaireId; }
  /**
   * @return int
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getDivisionId()
  { return $this->divisionId; }
  /**
   * @return int
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getMatiereId()
  { return $this->matiereId; }
  /**
   * @return int
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getEnseignantId()
  { return $this->enseignantId; }
  /**
   * @param int $anneeScolaireId
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function setAnneeScolaireId($anneeScolaireId)
  { $this->anneeScolaireId = $anneeScolaireId; }
  /**
   * @param int $divisionId
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function setDivisionId($divisionId)
  { $this->divisionId = $divisionId; }
  /**
   * @param int $matiereId
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function setMatiereId($matiereId)
  { $this->matiereId = $matiereId; }
  /**
   * @param int $enseignantId
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function setEnseignantId($enseignantId)
  { $this->enseignantId = $enseignantId; }

  //////////////////////////////////////////////////
  // CONSTRUCT - CLASSVARS - CONVERT - BEAN
  //////////////////////////////////////////////////
  /**
   * @param array $attributes
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->AnneeScolaireServices = new AnneeScolaireServices();
    $this->DivisionServices = new DivisionServices();
    $this->EnseignantServices = new EnseignantServices();
    $this->MatiereServices = new MatiereServices();

    $this->mandatoryFields = array(
      self::FIELD_ANNEESCOLAIRE_ID,
      self::FIELD_DIVISION_ID,
      self::FIELD_ENSEIGNANT_ID,
      self::FIELD_MATIERE_ID,
    );
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
   * @return AnneeScolaire
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getAnneeScolaire()
  {
    if ($this->AnneeScolaire==null) {
      $this->AnneeScolaire = $this->AnneeScolaireServices->selectLocal($this->anneeScolaireId);
    }
    return $this->AnneeScolaire;
  }
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
   * @return Enseignant
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getEnseignant()
  {
    if ($this->Enseignant==null) {
      $this->Enseignant = $this->EnseignantServices->selectLocal($this->enseignantId);
    }
    return $this->Enseignant;
  }

  //////////////////////////////////////////////////
  // METHODS
  //////////////////////////////////////////////////

  //////////////////////////////////////////////////
  // METHODS
  //////////////////////////////////////////////////
  /**
   * @param string $sep
   * @return string
   * @version 1.21.06.08
   * @since 1.21.06.08
   */
  public function getCsvEntete($sep=';')
  { return implode($sep, array(self::FIELD_ID, self::FIELD_ANNEESCOLAIRE, self::FIELD_LABELDIVISION, self::FIELD_LABELMATIERE, self::FIELD_NOMENSEIGNANT)); }
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
    $arrValues[] = $this->getAnneeScolaire()->getAnneeScolaire();
    $arrValues[] = $this->getDivision()->getLabelDivision();
    $arrValues[] = $this->getMatiere()->getLabelMatiere();
    $arrValues[] = $this->getEnseignant()->getNomEnseignant();
    return implode($sep, $arrValues);
  }



  public function update(&$notif, &$msg)
  {
    $msg    = 'Mise à jour réussie.';
    $notif  = self::NOTIF_DANGER;
    $bln_OK = false;

    if ($this->id=='') {
      $msg = 'Mise à jour impossible. Identifiant non reconnu.';
      /*
    } elseif ($this->nomEleve=='') {
      $msg = 'Mise à jour impossible. Nom de l\'Elève non renseigné.';
    } elseif ($this->prenomEleve=='') {
      $msg = 'Mise à jour impossible. Prénom de l\'Elève non renseigné.';
      */
    } else {
      /*
      $Divisions = $this->DivisionServices->getDivisionsWithFilters(array(self::FIELD_LABELDIVISION=>$this->di));
      if (!empty($Matieres)) {
        $msg = 'Mise à jour impossible. Libellé déjà existant.';
      } else {
      */
        $this->CompoDivisionServices->updateLocal($this);
        $notif = self::NOTIF_SUCCESS;
        $bln_OK = true;
      /*
      }
      */
    }
    return $bln_OK;
  }

  public function insert(&$notif, &$msg)
  {
    $msg    = 'Création réussie.';
    $notif  = self::NOTIF_DANGER;
    $bln_OK = false;
/*
    if ($this->nomEleve=='') {
      $msg = 'Création impossible. Nom de l\'Elève non renseigné.';
    } elseif ($this->prenomEleve=='') {
      $msg = 'Mise à jour impossible. Prénom de l\'Elève non renseigné.';
    } else {
      $Matieres = $this->MatiereServices->getMatieresWithFilters(array(self::FIELD_LABELMATIERE=>$this->labelMatiere));
      if (!empty($Matieres)) {
        $msg = 'Création impossible. Libellé déjà existant.';
      } else {
      */
        $this->CompoDivisionServices->insertLocal($this);
        $notif = self::NOTIF_SUCCESS;
        $bln_OK = true;
      /*
      }
    }
      */
    return $bln_OK;
  }

  public function delete (&$notif, &$msg)
  {
    $this->CompoDivisionServices->deleteLocal($this);
    $msg = 'Suppression réussie.';
    $notif = self::NOTIF_SUCCESS;
  }

}
