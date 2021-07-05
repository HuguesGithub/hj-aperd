<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe WpPageHomeBean
 * @author Hugues
 * @version 1.21.06.29
 * @since 1.21.06.29
 */
class WpPageHomeBean extends WpPageBean
{
  protected $urlTemplate = 'web/pages/public/wppage-home.php';
  /**
   * Class Constructor
   * @param WpPage $WpPage
   * @version 1.21.06.29
   * @since 1.21.06.29
   */
  public function __construct($WpPage='')
  { parent::__construct($WpPage); }
  /**
   * @return string
   * @version 1.21.06.29
   * @since 1.21.06.29
   */
  public function getContentPage()
  {
    $args = array(
      // Notifications éventuelles - 1
      '',

    );
    return $this->getRender($this->urlTemplate, $args);
  }

}
