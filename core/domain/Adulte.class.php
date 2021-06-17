<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe Adulte
 * @author Hugues
 * @version 1.21.06.17
 * @since 1.21.06.10
 */
class Adulte extends LocalDomain
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
   * Nom du Parent
   * @var string $nomParent
   */
  protected $nomParent;
  /**
   * Prénom du Parent
   * @var string $prenomParent
   */
  protected $prenomParent;
  /**
   * Mail du Parent
   * @var string $mailParent
   */
  protected $mailParent;
  /**
   * Est adhérent ?
   * @var boolean $adherent
   */
  protected $adherent;

  //////////////////////////////////////////////////
  // GETTERS & SETTERS
  //////////////////////////////////////////////////
  /**
   * @return string
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function getNomParent()
  { return $this->nomParent; }
  /**
   * @return string
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function getPrenomParent()
  { return $this->prenomParent; }
  /**
   * @return string
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function getMailParent()
  { return $this->mailParent; }
  /**
   * @return boolean
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function isAdherent()
  { return ($this->adherent==1); }
  /**
   * @param string $nomParent
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function setNomParent($nomParent)
  { $this->nomParent=stripslashes($nomParent); }
  /**
   * @param string $prenomParent
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function setPrenomParent($prenomParent)
  { $this->prenomParent=$prenomParent; }
  /**
   * @param string $mailParent
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function setMailParent($mailParent)
  { $this->mailParent=$mailParent; }
  /**
   * @param boolean $adherent
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function setAdherent($adherent)
  { $this->adherent=$adherent; }

  //////////////////////////////////////////////////
  // CONSTRUCT - CLASSVARS - CONVERT - BEAN
  //////////////////////////////////////////////////
  /**
   * @param array $attributes
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->AdulteServices = new AdulteServices();
    $this->Services       = new AdulteServices();
  }
  /**
   * @return array
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function getClassVars()
  { return get_class_vars('Adulte'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return Adulte
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new Adulte(), self::getClassVars(), $row); }
  /**
   * @return AdulteBean
   * @version 1.21.06.11
   * @since 1.21.06.10
   */
  public function getBean()
  { return new AdulteBean($this); }

  //////////////////////////////////////////////////
  // METHODS
  //////////////////////////////////////////////////
  /**
   * @return string
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function getFullName()
  { return $this->nomParent.self::CST_BLANK.$this->prenomParent; }
  /**
   * @param string $sep
   * @return string
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function getCsvEntete($sep=';')
  { return implode($sep, array(self::FIELD_ID, self::FIELD_NOMPARENT, self::FIELD_PRENOMPARENT, self::FIELD_MAILPARENT, self::FIELD_ADHERENT)); }
  /**
   * @param string $sep
   * @return string
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function toCsv($sep=self::SEP)
  { return implode($sep, array($this->id, $this->nomParent, $this->prenomParent, $this->mailParent,  $this->adherent)); }
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
    list($id, $nomParent, $prenomParent, $mailParent, $adherent) = explode($sep, $rowContent);
    $this->setId($id);
    $this->setNomParent(trim($nomParent));
    $this->setPrenomParent(trim($prenomParent));
    $this->setMailParent(trim($mailParent));
    $this->setAdherent(trim(str_replace(self::EOL, '', $adherent)));

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
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function controleDonnees(&$notif, &$msg)
  {
    // Le nom du Titulaire doit être renseigné
    if (empty($this->nomParent)) {
      $notif = self::NOTIF_DANGER;
      $msg   = self::MSG_ERREUR_CONTROL_EXISTENCE;
      return false;
    }
    // Le libellé du Poste doit être renseigné
    if (empty($this->mailParent)) {
      $notif = self::NOTIF_DANGER;
      $msg   = self::MSG_ERREUR_CONTROL_EXISTENCE;
      return false;
    }
    return true;
  }

}
