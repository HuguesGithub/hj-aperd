<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe Division
 * @author Hugues
 * @version 1.21.06.17
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
  /**
   * Code de la Division
   * @var string $crKey
   */
  protected $crKey;

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
  /**
   * @return string
   * @version 1.21.06.17
   * @since 1.21.06.17
   */
  public function getCrKey()
  { return $this->crKey; }
  /**
   * @param string $crKey
   * @version 1.21.06.17
   * @since 1.21.06.17
   */
  public function setCrKey($crKey)
  { $this->crKey = $crKey; }

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
   * @version 1.21.06.17
   * @since 1.21.06.01
   */
  public function getCsvEntete($sep=self::SEP)
  { return implode($sep, array(self::FIELD_ID, self::FIELD_LABELDIVISION, self::FIELD_CRKEY)); }
  /**
   * @param string $sep
   * @return string
   * @version 1.21.06.17
   * @since 1.21.06.08
   */
  public function toCsv($sep=self::SEP)
  { return implode($sep, array($this->id, $this->labelDivision, $this->crKey)); }
  /**
   * @param string $rowContent
   * @param string $sep
   * @param string &$notif
   * @param string &$msg
   * @return boolean
   * @version 1.21.06.08
   * @since 1.21.06.08
   */
  public function controleImportRow($rowContent, $sep, &$notif, &$msg)
  {
    list($id, $labelDivision, $crKey) = explode($sep, $rowContent);
    $this->setId($id);
    $this->setLabelDivision(trim($labelDivision));
    $importedCrKey = trim(str_replace(self::EOL, '', $crKey));
    if (empty($importedCrKey)) {
      $importedCrKey = $this->getUniqueGenKey();
    }
    $this->setCrKey($importedCrKey);

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
   * @version 1.21.06.17
   * @since 1.21.06.08
   */
  public function controleDonnees(&$notif, &$msg)
  {
    $returned = true;
    // Le libellé doit être renseigné
    if (empty($this->labelDivision)) {
      $notif = self::NOTIF_DANGER;
      $msg   = self::MSG_ERREUR_CONTROL_EXISTENCE;
      $returned = false;
    }
    // Le libellé doit être unique et donc, ne pas exister en base
    $Divisions = $this->DivisionServices->getDivisionsWithFilters(array(self::FIELD_LABELDIVISION=>$this->labelDivision));
    if (!empty($Divisions)) {
      $notif = self::NOTIF_DANGER;
      $msg   = self::MSG_ERREUR_CONTROL_UNICITE;
      $returned = false;
    }

    // Le code crKey doit être unique. S'il est déjà présent, on envoie une alerte et on en génère un nouveau.
    $Divisions = $this->DivisionServices->getDivisionsWithFilters(array(self::FIELD_CRKEY=>$this->crKey));
    if (!empty($Divisions)) {
      $notif = self::NOTIF_DANGER;
      $msg   = self::MSG_ERREUR_CONTROL_UNICITE;
      $this->setCrKey($this->getUniqueGenKey());
      $returned = false;
    }

    return $returned;
  }



  /**
   * @return string
   * @version 1.21.06.17
   * @since 1.21.06.17
   */
  public function getUniqueGenKey()
  {
    do {
      // On génère une clef.
      $genCrKey = $this->genKey();
      // On vérifie son unicité.
      $Divisions = $this->DivisionServices->getDivisionsWithFilters(array(self::FIELD_CRKEY=>$genCrKey));
      // Tant qu'elle n'est pas unique, on reprend le processus.
    } while (!empty($Divisions));
    return $genCrKey;
  }
  /**
   * @return string
   * @version 1.21.06.17
   * @since 1.21.06.17
   */
  public function genKey()
  {
    $eligibleChars = 'abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $genCrKey = '';
    for ($i=0; $i<16; $i++) {
      $eligibleChars = str_shuffle($eligibleChars);
      $genCrKey .= $eligibleChars[0];
    }
    return $genCrKey;
  }

}
