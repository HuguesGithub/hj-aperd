<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe AnneeScolaire
 * @author Hugues
 * @version 1.21.06.17
 * @since 1.21.06.04
 */
class AnneeScolaire extends LocalDomain
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
   * Libellé de l'année scolaire
   * @var string $anneeScolaire
   */
  protected $anneeScolaire;

  //////////////////////////////////////////////////
  // GETTERS & SETTERS
  //////////////////////////////////////////////////
  /**
   * @return string
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getAnneeScolaire()
  { return $this->anneeScolaire; }
  /**
   * @param string $anneeScolaire
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function setAnneeScolaire($anneeScolaire)
  { $this->anneeScolaire=$anneeScolaire; }

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
    $this->Services              = new AnneeScolaireServices();
  }
  /**
   * @return array
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getClassVars()
  { return get_class_vars('AnneeScolaire'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return AnneeScolaire
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new AnneeScolaire(), self::getClassVars(), $row); }
  /**
   * @return AnneeScolaireBean
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getBean()
  { return new AnneeScolaireBean($this); }

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
  { return implode($sep, array(self::FIELD_ID, self::FIELD_ANNEESCOLAIRE)); }
  /**
   * @param string $sep
   * @return string
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function toCsv($sep=self::SEP)
  { return implode($sep, array($this->id, $this->anneeScolaire)); }
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
    list($id, $anneeScolaire) = explode($sep, $rowContent);
    $this->setId($id);
    $this->setAnneeScolaire(trim(str_replace(self::EOL, '', $anneeScolaire)));

    return $this->controleDonneesAndAct($this, $notif, $msg);
  }
  /**
   * @param string &$notif
   * @param string &$msg
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function controleDonnees(&$notif, &$msg)
  {
    // L'Année Scolaire doit être renseigné
    if (empty($this->anneeScolaire)) {
      $notif = self::NOTIF_DANGER;
      $msg   = self::MSG_ERREUR_CONTROL_EXISTENCE;
      return false;
    }
    return true;
  }

  /**
   * @return string
   * @version 1.21.06.17
   * @since 1.21.06.17
   */
  public function getFullName()
  { return $this->getAnneeScolaire(); }

}
