<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe Administration
 * @author Hugues
 * @version 1.00.00
 * @since 1.00.00
 */
class Administration extends LocalDomain
{
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;
  /**
   * Libellé du Poste
   * @var string $labelPoste
   */
  protected $labelPoste;
  /**
   * Nom du Titulaire du Poste
   * @var string $nomTitulaire
   */
  protected $nomTitulaire;
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
  public function getLabelPoste()
  { return $this->labelPoste; }
  /**
   * @return string
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getNomTitulaire()
  { return $this->nomTitulaire; }
  /**
   * @param int $id
   * @version 1.00.00
   * @since 1.00.00
   */
  public function setId($id)
  { $this->id=$id; }
  /**
   * @param string $labelPoste
   * @version 1.00.00
   * @since 1.00.00
   */
  public function setLabelPoste($labelPoste)
  { $this->labelPoste=stripslashes($labelPoste); }
  /**
   * @param string $nomTitulaire
   * @version 1.00.00
   * @since 1.00.00
   */
  public function setNomTitulaire($nomTitulaire)
  { $this->nomTitulaire=$nomTitulaire; }
  /**
   * @return array
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getClassVars()
  { return get_class_vars('Administration'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return Administration
   * @version 1.00.00
   * @since 1.00.00
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new Administration(), self::getClassVars(), $row); }
  /**
   * @return AdministrationBean
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getBean()
  { return new AdministrationBean($this); }
  /**
   * @return string
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getFullInfo()
  { return $this->nomTitulaire.', '.$this->labelPoste; }
}
