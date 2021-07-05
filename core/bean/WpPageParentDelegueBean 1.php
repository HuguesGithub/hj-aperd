<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe WpPageParentDelegueBean
 * @author Hugues
 * @version 1.21.06.29
 * @since 1.21.06.29
 */
class WpPageParentDelegueBean extends WpPageBean
{
  protected $urlTemplate = 'web/pages/public/wppage-parent-delegue.php';
  protected $urlTemplateLogIn = 'web/pages/public/wppage-parent-delegue-identification.php';
  /**
   * Class Constructor
   * @param WpPage $WpPage
   * @version 1.21.06.29
   * @since 1.21.06.29
   */
  public function __construct($WpPage='')
  {
    parent::__construct($WpPage);
    $this->DivisionServices = new DivisionServices();
    $this->EleveServices = new EleveServices();
    $this->EnseignantServices = new EnseignantServices();
    $this->ParentDelegueServices = new ParentDelegueServices();
  }

  /**
   * @return string
   * @version 1.21.06.29
   * @since 1.21.06.29
   */
  public function getContentPage()
  {
    if (!isset($_SESSION['userLogin'])) {
      return $this->getLogInPage();
    } else {
      return $this->getDashboardPage();
    }
  }

  /**
   * @return string
   * @version 1.21.06.29
   * @since 1.21.06.29
   */
  private function getDashboardPage()
  {
    $args = array(
      // Panneau d'accueil - 1
      '',
      // Composition de la Division : liste des enseignants, matières et prof principal.
      // Puis, liste des élèves.
      $this->getPanelCompositionDivision(),
    );
    return $this->getRender($this->urlTemplate, $args);
  }

  private function getPanelCompositionDivision()
  {
    $urlTemplatePanel = 'web/pages/public/fragments/panel-parent-delegue-composition-division.php';
    $Division = $this->getDivision();

    /////////////////////////////////////////////////////////////
    // Récupération de la liste des Enseignants de la classe et construction du tableau correspondant.
    $strEnseignants = '';
    if ($Division->getId()!='') {
      $Enseignants = $this->EnseignantServices->getEnseignantsWithFilters(array(self::FIELD_DIVISION_ID=>$Division->getId()));
      while (!empty($Enseignants)) {
        $Enseignant = array_shift($Enseignants);
        $strEnseignants .= $Enseignant->getBean()->getRowForPublicPage($Division->getId());
      }
	}
    /////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////
    // Récupération de la liste des Elèves de la classe et construction du tableau correspondant.
    $strEleves = '';
    if ($Division->getId()!='') {
      $Eleves = $this->EleveServices->getElevesWithFilters(array(self::FIELD_DIVISION_ID=>$Division->getId()));
      while (!empty($Eleves)) {
        $Eleve = array_shift($Eleves);
        $strEleves .= $Eleve->getBean()->getRowForPublicPage();
      }
    }
    /////////////////////////////////////////////////////////////

    $args = array(
      // La liste des Enseignants - 1
      $strEnseignants,
      // La liste des Elèves - 2
      $strEleves,
    );
    return $this->getRender($urlTemplatePanel, $args);
  }

  private function getDivision()
  {
    $Divisions = $this->DivisionServices->getDivisionsWithFilters(array(self::FIELD_CRKEY=>$_SESSION['crKey']));
    return (empty($Divisions) ? new Division() : array_shift($Divisions));
  }

  /**
   * @return string
   * @version 1.21.06.29
   * @since 1.21.06.29
   */
  private function getLoginPage()
  {
    if (isset($_POST['login'])) {
      // Soit je suis en train d'essayer de m'identifier.
      $divisionCrKey = stripslashes($_POST['crKey']);
      $loginParent   = stripslashes($_POST['loginParent']);
      $Divisions     = $this->DivisionServices->getDivisionsWithFilters(array(self::FIELD_CRKEY=>$divisionCrKey));
      if (!empty($divisionCrKey) && !empty($Divisions)) {
        // S'il y a une division avec cette clé
        $Division = array_shift($Divisions);
        $ParentDelegues = $this->ParentDelegueServices->getParentDeleguesWithFilters(array(self::FIELD_DIVISION_ID=>$Division->getId()));
        $bln_isLoginCorrect = false;
        while (!empty($ParentDelegues)) {
          $ParentDelegue = array_shift($ParentDelegues);
          if (strtolower($ParentDelegue->getAdulte()->getLogin())==strtolower($loginParent)) {
            $bln_isLoginCorrect = true;
            break;
          }
        }
        if ($bln_isLoginCorrect) {
          $_SESSION['userLogin'] = $loginParent;
          $_SESSION['crKey'] = $divisionCrKey;
          return $this->getDashboardPage();
        } else {
          $notifications = 'Erreur de login Parent.';
        }
      } else {
        $notifications = 'Erreur de clé division.';
      }
    } else {
      // Soit je débarque.
      $notifications = '';
    }
    $args = array(
      // Notifications - 1
      $notifications,
    );
    return $this->getRender($this->urlTemplateLogIn, $args);
  }
}