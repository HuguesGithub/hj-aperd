<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe Eleve
 * @author Hugues
 * @version 1.00.00
 * @since 1.00.00
 */
class Eleve extends LocalDomain
{
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

  public function __construct()
  {
    $this->DivisionServices = new DivisionServices();
    $this->EleveServices = new EleveServices();
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
  public function getNomEleve()
  { return $this->nomEleve; }
  /**
   * @return string
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getPrenomEleve()
  { return $this->prenomEleve; }
  /**
   * @return int
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getDivisionId()
  { return $this->divisionId; }
  /**
   * @param int $id
   * @version 1.00.00
   * @since 1.00.00
   */
  public function setId($id)
  { $this->id=$id; }
  public function setNomEleve($nomEleve)
  { $this->nomEleve = stripslashes($nomEleve); }
  public function setPrenomEleve($prenomEleve)
  { $this->prenomEleve = stripslashes($prenomEleve); }
  public function setDivisionId($divisionId)
  { $this->divisionId = $divisionId; }
  /**
   * @return array
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getClassVars()
  { return get_class_vars('Eleve'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return Eleve
   * @version 1.00.00
   * @since 1.00.00
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new Eleve(), self::getClassVars(), $row); }
  /**
   * @return EleveBean
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getBean()
  { return new EleveBean($this); }

  public function getNomComplet()
  { return $this->nomEleve.' '.$this->prenomEleve; }

  public function getDivision()
  {
    if ($this->Division==null) {
      $this->Division = $this->DivisionServices->selectLocal($this->divisionId);
    }
    return $this->Division;
  }

  public function getCsvEntete($sep=';')
  { return 'id;nomEleve;prenomEleve;division'; }
  public function toCsv($sep=';')
  {
    $arrValues = array();
    $arrValues[] = $this->id;
    $arrValues[] = $this->nomEleve;
    $arrValues[] = $this->prenomEleve;
    $arrValues[] = $this->getDivision()->getLabelDivision();
    return implode($sep, $arrValues);
  }

  public function update(&$notif, &$msg)
  {
    $msg    = 'Mise à jour réussie.';
    $notif  = self::NOTIF_DANGER;
    $bln_OK = false;

    if ($this->id=='') {
      $msg = 'Mise à jour impossible. Identifiant non reconnu.';
    } elseif ($this->nomEleve=='') {
      $msg = 'Mise à jour impossible. Nom de l\'Elève non renseigné.';
    } elseif ($this->prenomEleve=='') {
      $msg = 'Mise à jour impossible. Prénom de l\'Elève non renseigné.';
    } else {
      /*
      $Divisions = $this->DivisionServices->getDivisionsWithFilters(array(self::FIELD_LABELDIVISION=>$this->di));
      if (!empty($Matieres)) {
        $msg = 'Mise à jour impossible. Libellé déjà existant.';
      } else {
      */
        $this->EleveServices->updateLocal($this);
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

    if ($this->nomEleve=='') {
      $msg = 'Création impossible. Nom de l\'Elève non renseigné.';
    } elseif ($this->prenomEleve=='') {
      $msg = 'Mise à jour impossible. Prénom de l\'Elève non renseigné.';
    } else {
      /*
      $Matieres = $this->MatiereServices->getMatieresWithFilters(array(self::FIELD_LABELMATIERE=>$this->labelMatiere));
      if (!empty($Matieres)) {
        $msg = 'Création impossible. Libellé déjà existant.';
      } else {
      */
        $this->EleveServices->insertLocal($this);
        $notif = self::NOTIF_SUCCESS;
        $bln_OK = true;
      /*
      }
      */
    }
    return $bln_OK;
  }

  public function delete (&$notif, &$msg)
  {
    $this->EleveServices->deleteLocal($this);
    $msg = 'Suppression réussie.';
    $notif = self::NOTIF_SUCCESS;
  }

}
