<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe WpPageError404Bean
 * @author Hugues
 * @version 1.21.06.30
 * @since 1.21.06.30
 */
class WpPageError404Bean extends WpPageBean
{
  protected $urlTemplate = 'web/pages/public/wppage-error-404.php';
  /**
   * Class Constructor
   * @param WpPage $WpPage
   * @version 1.21.06.30
   * @since 1.21.06.30
   */
  public function __construct($WpPage='')
  { parent::__construct($WpPage); }
  /**
   * @return string
   * @version 1.21.06.30
   * @since 1.21.06.30
   */
  public function getContentPage()
  {
    $args = array();
    return $this->getRender($this->urlTemplate, $args);
  }

}
