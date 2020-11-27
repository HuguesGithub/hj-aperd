<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe Matiere
 * @author Hugues
 * @version 1.00.00
 * @since 1.00.00
 */
class Matiere extends LocalDomain
{
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;
  /**
   * Libellé de la Matière
   * @var string $labelMatiere
   */
  protected $labelMatiere;
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
  public function getLabelMatiere()
  { return $this->labelMatiere; }
  /**
   * @param int $id
   * @version 1.00.00
   * @since 1.00.00
   */
  public function setId($id)
  { $this->id=$id; }
  /**
   * @param string $labelMatiere
   * @version 1.00.00
   * @since 1.00.00
   */
  public function setLabelMatiere($labelMatiere)
  { $this->labelMatiere=$labelMatiere; }
  /**
   * @return array
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getClassVars()
  { return get_class_vars('Matiere'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return Matiere
   * @version 1.00.00
   * @since 1.00.00
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new Matiere(), self::getClassVars(), $row); }
  /**
   * @return MatiereBean
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getBean()
  { return new MatiereBean($this); }
}
