<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe Administration
 * @author Hugues
 * @version 1.21.06.19
 * @since 1.21.06.04
 */
class Administration extends LocalDomain
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
   * Genre du Titulaire
   * @var string $genre
   */
  protected $genre;
  /**
   * Nom du Titulaire du Poste
   * @var string $nomTitulaire
   */
  protected $nomTitulaire;
  /**
   * Libellé du Poste
   * @var string $labelPoste
   */
  protected $labelPoste;

  //////////////////////////////////////////////////
  // GETTERS & SETTERS
  //////////////////////////////////////////////////
  /**
   * @return string
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getLabelPoste()
  { return $this->labelPoste; }
  /**
   * @return string
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getNomTitulaire()
  { return $this->nomTitulaire; }
  /**
   * @return string
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function getGenre()
  { return $this->genre; }
  /**
   * @param string $labelPoste
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function setLabelPoste($labelPoste)
  { $this->labelPoste=stripslashes($labelPoste); }
  /**
   * @param string $nomTitulaire
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function setNomTitulaire($nomTitulaire)
  { $this->nomTitulaire=$nomTitulaire; }
  /**
   * @param string $genre
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function setGenre($genre)
  { $this->genre=$genre; }

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
    $this->AdministrationServices = new AdministrationServices();
    $this->Services               = new AdministrationServices();
  }
  /**
   * @return array
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getClassVars()
  { return get_class_vars('Administration'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return Administration
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new Administration(), self::getClassVars(), $row); }
  /**
   * @return AdministrationBean
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getBean()
  { return new AdministrationBean($this); }

  //////////////////////////////////////////////////
  // METHODS
  //////////////////////////////////////////////////
  /**
   * @return string
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getFullInfo()
  { return $this->nomTitulaire.', '.$this->labelPoste; }
  /**
   * @return string
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function getFullName()
  { return $this->genre.' '.$this->nomTitulaire.', '.$this->labelPoste; }
  /**
   * @param string $sep
   * @return string
   * @version 1.21.06.08
   * @since 1.21.06.08
   */
  public function getCsvEntete($sep=';')
  { return implode($sep, array(self::FIELD_ID, self::FIELD_GENRE, self::FIELD_NOMTITULAIRE, self::FIELD_LABELPOSTE)); }
  /**
   * @param string $sep
   * @return string
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function toCsv($sep=self::SEP)
  { return implode($sep, array($this->id, $this->genre, $this->nomTitulaire, $this->labelPoste)); }
  /**
   * @return string
   * @version 1.21.06.17
   * @since 1.21.06.17
   */
  public function toArrayForm($isNew=true)
  { return ($isNew ? array('','','') : array($this->genre, $this->nomTitulaire, $this->labelPoste)); }
  /**
   * @param string $rowContent
   * @param string $sep
   * @param string &$notif
   * @param string &$msg
   * @return boolean
   * @version 1.21.06.17
   * @since 1.21.06.10
   */
  public function controleImportRow($rowContent, $sep, &$notif, &$msg)
  {
    list($id, $genre, $nomTitulaire, $labelPoste) = explode($sep, $rowContent);
    $this->setId($id);
    $this->setGenre(trim($genre));
    $this->setNomTitulaire(trim($nomTitulaire));
    $this->setLabelPoste(trim(str_replace(self::EOL, '', $labelPoste)));

    return $this->controleDonneesAndAct($this, $notif, $msg);
  }
  /**
   * @param string &$notif
   * @param string &$msg
   * @version 1.21.06.19
   * @since 1.21.06.10
   */
  public function controleDonnees(&$notif, &$msg)
  {
    $returned = true;
    // Le nom du Titulaire doit être renseigné
    if (empty($this->nomTitulaire)) {
      $notif = self::NOTIF_DANGER;
      $msg   = self::MSG_ERREUR_CONTROL_EXISTENCE;
      $returned = false;
    }
    if ($returned) {
      // Le nom du Titulaire doit être unique
      $Administrations = $this->AdministrationServices->getAdministrationsWithFilters(array(self::FIELD_NOMTITULAIRE=>$this->nomTitulaire));
      if (!empty($Administrations)) {
        $Administration = array_shift($Administrations);
        if ($Administration->getId()!=$this->id) {
          $notif = self::NOTIF_DANGER;
          $msg   = self::MSG_ERREUR_CONTROL_UNICITE;
          $returned = false;
        }
      }
    }
    // Le libellé du Poste doit être renseigné
    if ($returned && empty($this->labelPoste)) {
      $notif = self::NOTIF_DANGER;
      $msg   = self::MSG_ERREUR_CONTROL_EXISTENCE;
      $returned = false;
    }
    return $returned;
  }

}
