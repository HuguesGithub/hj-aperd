<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * WpPageBean
 * @author Hugues
 * @version 1.21.06.29
 * @since 1.21.06.01
 */
class WpPageBean extends MainPageBean
{
  /**
   * WpPost affiché
   * @var WpPost $WpPage
   */
  protected $WpPage;
  /**
   * @param string $post
   * @version 1.00.00
   * @since 1.00.00
   */
  public function __construct($post='')
  {
    if ($post=='') {
      $post = get_post();
    }
    if ($post!='') {
      if (get_class($post) == 'WpPost') {
        $this->WpPage = $post;
      } else {
        $this->WpPage = WpPost::convertElement($post);
      }
    }
    parent::__construct();
  }
  /**
   * @return string
   * @version 1.21.06.29
   * @since 1.21.06.01
   */
  public function getContentPage()
  {
    if($this->WpPage->getPostName()==self::PAGE_COMPTE_RENDU) {
      $Bean = new WpPageCompteRendusBean($this->WpPage);
    } elseif($this->WpPage->getPostName()==self::PAGE_PARENT_DELEGUE) {
      $Bean = new WpPageParentDelegueBean($this->WpPage);
    } else {
      $Bean = new WpPageError404Bean();
    }
    return $Bean->getContentPage();
  }
  /**
   * {@inheritDoc}
   * @see MainPageBean::getShellClass()
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getShellClass()
  { return ''; }

}
