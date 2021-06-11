<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe Division
 * @author Hugues
 * @version 1.21.06.04
 * @since 1.21.06.04
 */
class Division extends LocalDomain
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
   * Libellé de la Division
   * @var string $labelDivision
   */
  protected $labelDivision;

  //////////////////////////////////////////////////
  // GETTERS & SETTERS
  //////////////////////////////////////////////////
  /**
   * @return string
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getLabelDivision()
  { return $this->labelDivision; }
  /**
   * @param string $labelDivision
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function setLabelDivision($labelDivision)
  { $this->labelDivision = $labelDivision; }

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
    $this->DivisionServices = new DivisionServices();
    $this->Services         = new DivisionServices();
  }
  /**
   * @return array
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getClassVars()
  { return get_class_vars('Division'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return Division
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new Division(), self::getClassVars(), $row); }
  /**
   * @return DivisionBean
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getBean()
  { return new DivisionBean($this); }

  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////
  /**
   * @param string $sep
   * @return string
   * @version 1.21.06.08
   * @since 1.21.06.01
   */
  public function getCsvEntete($sep=self::SEP)
  { return implode($sep, array(self::FIELD_ID, self::FIELD_LABELDIVISION)); }
  /**
   * @param string $sep
   * @return string
   * @version 1.21.06.08
   * @since 1.21.06.08
   */
  public function toCsv($sep=self::SEP)
  { return implode($sep, array($this->id, $this->labelDivision)); }
  /**
   * @param string $rowContent
   * @param string $sep
   * @param string &$notif
   * @param string &$msg
   * @return boolean
   * @version 1.21.06.08
   * @since 1.21.06.08
   */
  public function controleImportRow($rowContent, $sep=self::SEP, &$notif, &$msg)
  {
    list($id, $labelDivision) = explode($sep, $rowContent);
    $this->setId($id);
    $labelDivision = trim(str_replace(self::EOL, '', $labelDivision));
    $this->setLabelDivision($labelDivision);

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
      $DivisionInBase = $this->Services->selectLocal($id);
      if ($DivisionInBase->getId()=='') {
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
   * @version 1.21.06.08
   * @since 1.21.06.08
   */
  public function controleDonnees(&$notif, &$msg)
  {
    // Le libellé doit être renseigné
    if (empty($this->labelDivision)) {
      $notif = self::NOTIF_DANGER;
      $msg   = self::MSG_ERREUR_CONTROL_EXISTENCE;
      return false;
    }
    // Le libellé doit être unique et donc, ne pas exister en base
    $Divisions = $this->DivisionServices->getDivisionsWithFilters(array(self::FIELD_LABELDIVISION=>$this->labelDivision));
    if (!empty($Divisions)) {
      $notif = self::NOTIF_DANGER;
      $msg   = self::MSG_ERREUR_CONTROL_UNICITE;
      return false;
    }
    return true;
  }

}
