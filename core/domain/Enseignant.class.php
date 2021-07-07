<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe Enseignant
 * @author Hugues
 * @version 1.21.07.07
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
   * @return string
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
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
   * @param string $prenomEnseignant
   * @version 1.21.07.06
   * @since 1.21.07.06
   */
  public function setPrenomEnseignant($prenomEnseignant)
  { $this->prenomEnseignant=$prenomEnseignant; }
  /**
   * @param string $genre
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
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
    $this->DivisionServices   = new DivisionServices();
    $this->ProfPrincipalServices = new ProfPrincipalServices();
    $this->EnseignantMatiereServices = new EnseignantMatiereServices();
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
  /**
   * @return array[Matiere]
   * @version 1.21.07.06
   * @since 1.21.07.06
   */
  public function getMatieres()
  {
    $Matieres = array();
    $EnseignantMatieres = $this->EnseignantMatiereServices->getEnseignantMatieresWithFilters(array(self::FIELD_ENSEIGNANT_ID=>$this->id));
    while (!empty($EnseignantMatieres)) {
      $EnseignantMatiere = array_shift($EnseignantMatieres);
      array_push($Matieres, $EnseignantMatiere->getMatiere());
    }
    return $Matieres;
  }

  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////
  /**
   * @param string $sep
   * @return string
   * @version 1.21.07.06
   * @since 1.21.06.01
   */
  public function getCsvEntete($sep=self::SEP)
  {
    $arrBase = array(self::FIELD_ID, self::FIELD_GENRE, self::FIELD_NOMENSEIGNANT, self::FIELD_PRENOMENSEIGNANT, self::FIELD_LABELMATIERE);
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
   * @return string
   * @version 1.21.07.06
   * @since 1.21.06.08
   */
  public function toCsv($sep=self::SEP)
  {
    $arrValues = array();
    $arrValues[] = $this->id;
    $arrValues[] = $this->genre;
    $arrValues[] = $this->nomEnseignant;
    $arrValues[] = $this->prenomEnseignant;
    $Matieres = $this->getMatieres();
    $arrLabelMatieres = array();
    while (!empty($Matieres)) {
      $Matiere = array_shift($Matieres);
      array_push($arrLabelMatieres, $Matiere->getLabelMatiere());
    }
    $arrValues[] = implode(',', $arrLabelMatieres);
    return implode($sep, $arrValues);
  }

  /**
   * @param string &$notif
   * @param string &$msg
   * @version 1.21.07.06
   * @since 1.21.06.09
   */
  public function controleDonnees(&$notif, &$msg)
  {
    $returned = true;
    // Le nom de l'Enseignant et la matière doivent être renseignés
    if (empty($this->nomEnseignant)) {
      $notif = self::NOTIF_DANGER;
      $msg   = sprintf(self::MSG_ERREUR_CONTROL_EXISTENCE_NORMEE, 'Nom Enseignant');
      $returned = false;
    }
    if ($returned) {
      // Le nom de l'Enseignant doit être unique et donc, ne pas exister en base
      $Enseignants = $this->EnseignantServices->getEnseignantsWithFilters(array(self::FIELD_NOMENSEIGNANT=>$this->nomEnseignant));
      while (!empty($Enseignants)) {
        $Enseignant = array_shift($Enseignants);
        if ($Enseignant->getId()!=$this->id) {
          $notif = self::NOTIF_DANGER;
          $msg   = self::MSG_ERREUR_CONTROL_UNICITE;
          $returned = false;
        }
      }
    }
    /*
    if ($returned && empty($this->Matieres)) {
      $notif = self::NOTIF_WARNING;
      $msg   = sprintf(self::MSG_ERREUR_CONTROL_EXISTENCE_NORMEE, 'Matières');
      $returned = false;
    }
    */
    return $returned;
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
    list($id, $genre, $nomEnseignant, $prenomEnseignant, $labelMatiere) = explode($sep, $rowContent);
    //////////////////////////////////////////////////////////
    // Initialisation de l'Objet à insérer
    $this->setId($id);
    $this->setGenre(trim($genre));
    $this->setNomEnseignant(trim($nomEnseignant));
    $this->setPrenomEnseignant(trim($prenomEnseignant));

    //////////////////////////////////////////////////////////
    if (!$this->controleDonnees($notif, $msg)) {
      $notif = self::NOTIF_WARNING;
      $msg  .= self::MSG_SUCCESS_PARTIEL_IMPORT;
      return true;
    }

    //////////////////////////////////////////////////////////
    // Initialisation des données relatives aux Matières liées
    $urlParams['matiereIds'] = array();
    $labelMatieres = explode(',', $labelMatiere);
    while (!empty($labelMatieres)) {
      $labelMatiere = array_shift($labelMatieres);
      $Matieres = $this->MatiereServices->getMatieresWithFilters(array(self::FIELD_LABELMATIERE=>trim($labelMatiere)));
      if (!empty($Matieres)) {
        $Matiere = array_shift($Matieres);
        array_push($urlParams['matiereIds'], $Matiere->getId());
      }
    }

    //////////////////////////////////////////////////////////
    // Initialisation des données relatives à la Division liée
    $urlParams[self::FIELD_DIVISION_ID] = '';

    // Si les contrôles sont okay, on peut insérer ou mettre à jour
    if ($id=='') {
      // Si id n'est pas renseigné. C'est une création. Il faut vérifier que le label n'existe pas déjà.
      $this->Services->insertLocal($this);
      $this->insertEnseignantMatieres($urlParams);
      $this->insertProfPrincipal($urlParams);
    } else {
      $EnseignantInBase = $this->Services->selectLocal($id);
      if ($EnseignantInBase->getId()=='') {
        // Sinon, si id n'existe pas, c'est une création. Cf au-dessus
        $this->Services->insertLocal($this);
        $this->insertEnseignantMatieres($urlParams);
        $this->insertProfPrincipal($urlParams);
      } else {
        // Si id existe, c'est une édition, même contrôle que ci-dessus.
        $this->setId($id);
        $this->Services->updateLocal($this);
        $this->insertEnseignantMatieres($urlParams);
        $this->insertProfPrincipal($urlParams);
      }
    }
    return false;
  }

  private function insertEnseignantMatieres($urlParams)
  {
    $EnseignantMatiere = new EnseignantMatiere();
    $EnseignantMatiere->setEnseignantId($this->id);
    $matiereIds = $urlParams['matiereIds'];
    while (!empty($matiereIds)) {
      $id = array_shift($matiereIds);
      $EnseignantMatiere->setMatiereId($id);
      $this->EnseignantMatiereServices->insertLocal($EnseignantMatiere);
    }
  }

  /**
   * @param array $urlParams
   * @version 1.21.07.07
   * @since 1.21.07.07
   */
  private function insertProfPrincipal($urlParams)
  {
    if (isset($urlParams[self::FIELD_DIVISION_ID]) && $urlParams[self::FIELD_DIVISION_ID]!='') {
      $Division = $this->DivisionServices->selectLocal($urlParams[self::FIELD_DIVISION_ID]);
      if ($Division->getId()!='') {
        $ProfPrincipal = new ProfPrincipal();
        $ProfPrincipal->setEnseignantId($this->id);
        $ProfPrincipal->setDivisionId($Division->getId());
        $this->ProfPrincipalServices->insertLocal($ProfPrincipal);
      }
    }
  }

  /**
   * @param string &$notif
   * @param string &$msg
   * @return boolean
   * @version 1.21.07.07
   * @since 1.21.06.23
   */
  public function insert(&$notif, &$msg, $urlParams=array())
  {
    $returned = parent::insert($notif, $msg);
    if ($returned) {
      $this->insertEnseignantMatieres($urlParams);
      $this->insertProfPrincipal($urlParams);
    }
    return $returned;
  }
  /**
   * @param string &$notif
   * @param string &$msg
   * @return boolean
   * @version 1.21.07.07
   * @since 1.21.07.06
   */
  public function update(&$notif, &$msg, $urlParams=array())
  {
    $returned = parent::update($notif, $msg);
    if ($returned) {
      ///////////////////////////////////////////////////
      // Si la mise à jour s'est bien passée, on gère les Matières associées à l'Enseignant
      $EnseignantMatieres = $this->EnseignantMatiereServices->getEnseignantMatieresWithFilters(array(self::FIELD_ENSEIGNANT_ID=>$this->id));
      while (!empty($EnseignantMatieres)) {
        $EnseignantMatiere = array_shift($EnseignantMatieres);
        $this->EnseignantMatiereServices->deleteLocal($EnseignantMatiere);
      }
      $this->insertEnseignantMatieres($urlParams);
      ///////////////////////////////////////////////////

      ///////////////////////////////////////////////////
      // On peut aussi gérer l'éventuel Division où l'Enseignant est Professeur Principal.
      $this->ProfPrincipalServices->deleteWithFilters(array(self::FIELD_ENSEIGNANT_ID=>$this->id));
      $this->insertProfPrincipal($urlParams);
      ///////////////////////////////////////////////////
    }
    return $returned;
  }

}
