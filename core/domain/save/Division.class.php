<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe Division
 * @author Hugues
 * @version 1.00.00
 * @since 1.00.00
 */
class Division extends LocalDomain
{
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
  public function getLabelDivision()
  { return $this->labelDivision; }
  /**
   * @param int $id
   * @version 1.00.00
   * @since 1.00.00
   */
  public function setId($id)
  { $this->id = $id; }
  /**
   * @param string $labelDivision
   * @version 1.00.00
   * @since 1.00.00
   */
  public function setLabelDivision($labelDivision)
  { $this->labelDivision = $labelDivision; }
  /**
   * @return array
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getClassVars()
  { return get_class_vars('Division'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return Division
   * @version 1.00.00
   * @since 1.00.00
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new Division(), self::getClassVars(), $row); }
  /**
   * @return DivisionBean
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getBean()
  { return new DivisionBean($this); }
}
