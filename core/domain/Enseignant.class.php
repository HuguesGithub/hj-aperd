<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe Enseignant
 * @author Hugues
 * @version 1.21.06.17
 * @since 1.21.06.04
 */
class Enseignant extends LocalDomain
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
   * Genre de l'enseignant
   * @var string $genre
   */
  protected $genre;
  /**
   * Nom de l'enseignant
   * @var string $nomEnseignant
   */
  protected $nomEnseignant;
  /**
   * Prénom de l'enseignant
   * @var string $prenomEnseignant
   */
  protected $prenomEnseignant;
  /**
   * Id technique de la Matière
   * @var int $matiereId
   */
  protected $matiereId;
  /**
   * Actif au collège, ou l'ayant quitté.
   * @var bool $status
   */
  protected $status;

  //////////////////////////////////////////////////
  // GETTERS & SETTERS
  //////////////////////////////////////////////////
  /**
   * @return int
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getId()
  { return $this->id; }
  /**
   * @return string
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getNomEnseignant()
  { return $this->nomEnseignant; }
  /**
   * @return string
   * @version 1.21.06.06
   * @since 1.21.06.06
   */
  public function getPrenomEnseignant()
  { return $this->prenomEnseignant; }
  /**
   * @return int
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getMatiereId()
  { return $this->matiereId; }
  public function getStatus()
  { return $this->status; }
  public function getGenre()
  { return $this->genre; }
  /**
   * @param int $id
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function setId($id)
  { $this->id=$id; }
  /**
   * @param string $nomEnseignant
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function setNomEnseignant($nomEnseignant)
  { $this->nomEnseignant=$nomEnseignant; }
  /**
   * @param int $matiereId
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function setMatiereId($matiereId)
  { $this->matiereId=$matiereId; }
  public function setStatus($status)
  { $this->status = $status; }
  public function setGenre($genre)
  { $this->genre = $genre; }

  //////////////////////////////////////////////////
  // CONSTRUCT - CLASSVARS - CONVERT - BEAN
  //////////////////////////////////////////////////
  /**
   * @param array $attributes
   * @version 1.21.06.09
   * @since 1.21.06.01
   */
  public function __construct()
  {
    parent::__construct();
    $this->EnseignantServices = new EnseignantServices();
    $this->Services           = new EnseignantServices();
    $this->MatiereServices = new MatiereServices();
    $this->ProfPrincipalServices = new ProfPrincipalServices();
  }
  /**
   * @return array
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getClassVars()
  { return get_class_vars('Enseignant'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return Enseignant
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new Enseignant(), self::getClassVars(), $row); }
  /**
   * @return EnseignantBean
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getBean()
  { return new EnseignantBean($this); }

  //////////////////////////////////////////////////
  // GETTERS OBJETS LIES
  //////////////////////////////////////////////////
  /**
   * @return string
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getProfPrincipal()
  { return $this->genre.' '.$this->nomEnseignant.', '.($this->genre=='Mme' ? 'professeure principale' : 'professeur principal'); }

  public function getMatiere()
  {
    if ($this->Matiere==null) {
      $this->Matiere = $this->MatiereServices->selectLocal($this->matiereId);
    }
    return $this->Matiere;
  }

  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////
  /**
   * @param string $sep
   * @param boolean $withPP
   * @return string
   * @version 1.21.06.09
   * @since 1.21.06.01
   */
  public function getCsvEntete($sep=self::SEP, $withPP=false)
  {
    $arrBase = array(self::FIELD_ID, self::FIELD_GENRE, self::FIELD_NOMENSEIGNANT, self::FIELD_PRENOMENSEIGNANT, self::FIELD_LABELMATIERE);
    if ($withPP) {
      $arrBase[] = self::FIELD_LABELDIVISION;
      $arrBase[] = self::FIELD_ANNEESCOLAIRE;
    }
    return implode($sep, $arrBase);
  }
  /**
   * @return string
   * @version 1.21.06.08
   * @since 1.21.06.01
   */
  public function getFullName()
  { return $this->genre.' '.$this->nomEnseignant.' '.$this->prenomEnseignant; }
  /**
   * @param string $sep
   * @param boolean $withPP
   * @return string
   * @version 1.21.06.08
   * @since 1.21.06.08
   */
  public function toCsv($sep=self::SEP, $withPP=false)
  {
    $arrValues = array();
    $arrValues[] = $this->id;
    $arrValues[] = $this->genre;
    $arrValues[] = $this->nomEnseignant;
    $arrValues[] = $this->prenomEnseignant;
    $arrValues[] = $this->getMatiere()->getLabelMatiere();
    if ($withPP) {
      $ProfPrincipals = $this->ProfPrincipalServices->getProfPrincipalsWithFilters(array(self::FIELD_ENSEIGNANT_ID=>$this->getId()));
      $ProfPrincipal  = ($this->getId()==''||empty($ProfPrincipals) ? new ProfPrincipal() : array_shift($ProfPrincipals));

      $arrValues[] = $ProfPrincipal->getDivision()->getLabelDivision();
      $arrValues[] = $ProfPrincipal->getAnneeScolaire()->getAnneeScolaire();
    }
    return implode($sep, $arrValues);
  }

  /**
   * @param string &$notif
   * @param string &$msg
   * @version 1.21.06.09
   * @since 1.21.06.09
   */
  public function controleDonnees(&$notif, &$msg)
  {
    // TODO : Contrôle sur la Division et l'Année Scolaire des Profs Principaux.
    // Le nom de l'Enseignant et la matière doivent être renseignés
    if (empty($this->nomEnseignant) || empty($this->matiereId)) {
      $notif = self::NOTIF_DANGER;
      $msg   = self::MSG_ERREUR_CONTROL_EXISTENCE;
      return false;
    }
    // Le nom de l'Enseignant doit être unique et donc, ne pas exister en base
    $Enseignants = $this->EnseignantServices->getEnseignantsWithFilters(array(self::FIELD_NOMENSEIGNANT=>$this->nomEnseignant));
    if (!empty($Enseignants)) {
      $notif = self::NOTIF_DANGER;
      $msg   = self::MSG_ERREUR_CONTROL_UNICITE;
      return false;
    }
    // La Matière doit exister
    $Matiere = $this->getMatiere();
    if ($Matiere->getId()=='') {
      $notif = self::NOTIF_DANGER;
      $msg   = sprintf(self::MSG_ERREUR_CONTROL_INEXISTENCE, 'Matière');
      return false;
    }
    return true;
  }
  /**
   * @param string $rowContent
   * @param string $sep
   * @param string &$notif
   * @param string &$msg
   * @return boolean
   * @version 1.21.06.17
   * @since 1.21.06.08
   */
  public function controleImportRow($rowContent, $sep, &$notif, &$msg)
  {
    list($id, $genre, $nomEnseignant, $prenomEnseignant, $labelMatiere, $labelDivision, $anneeScolaire) = explode($sep, $rowContent);
    $this->setId($id);
    $this->setGenre(trim($genre));
    $this->setNomEnseignant(trim($nomEnseignant));
    $this->setPrenomEnseignant(trim($prenomEnseignant));
    $Matieres = $this->MatiereServices()->getMatieresWithFilters(array(self::FIELD_LABELMATIERE, trim($labelMatiere)));
    if (!empty($Matieres)) {
      $Matiere = array_shift($Matieres);
      $this->setNomEnseignant($Matiere->getId());
    }
    // TODO : Gérer les Profs Principaux...
    /*
    $Divisions = $this->DivisionServices()->getDivisionsWithFilters(array(self::FIELD_LABELDIVISION, trim($labelDivision)));
    if (!empty($Divisions)) {
      $Matiere = array_shift($Divisions);
      $this->setNomEnseignant(Matiere->getId());
    }
    */

    if (!$this->controleDonnees($notif, $msg)) {
      $notif = self::NOTIF_WARNING;
      $msg  .= self::MSG_SUCCESS_PARTIEL_IMPORT;
      return true;
    }
    // Si les contrôles sont okay, on peut insérer ou mettre à jour
    if ($id=='') {
      // Si id n'est pas renseigné. C'est une création. Il faut vérifier que le label n'existe pas déjà.
      $this->Services->insertLocal($this);
    } else {
      $EnseignantInBase = $this->Services->selectLocal($id);
      if ($EnseignantInBase->getId()=='') {
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


}
