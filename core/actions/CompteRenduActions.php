<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * CompteRenduActions
 * @author Hugues
 * @version 1.00.00
 * @since 1.00.00
 */
class CompteRenduActions extends LocalActions
{
  protected $urlFragmentNotification = 'web/pages/public/fragments/fragment-notification.php';
  protected $urlPdfFiles = 'http://aperd.jhugues.fr/wp-content/plugins/hj-aperd/web/rsc/pdf-files/';
  /**
   * Constructeur
   */
  public function __construct($post)
  {
    parent::__construct();
    $this->CompteRenduServices = new CompteRenduServices();
    $this->BilanMatiereServices = new BilanMatiereServices();
    $this->post = $post;
  }
  /**
   * Point d'entrée des méthodes statiques.
   * @param array $post
   * @return string
   **/
  public static function dealWithStatic($post)
  {
    $returned = '';
    $Act = new CompteRenduActions($post);
    switch ($post[self::AJAX_ACTION]) {
      case self::AJAX_SAVE :
        $returned = $Act->dealWithSave();
      break;
      case self::AJAX_SEARCH :
        $returned = $Act->dealWithSearch();
      break;
      case self::AJAX_GETNEWMATIERE    :
        $returned = $Act->dealWithGetNewMatiere();
      break;
      default :
        $returned = '';
      break;
    }
    return $returned;
  }

  /**
   * Récupération du contenu de la page via une requête Ajax.
   * @return string
   */
  public function dealWithGetNewMatiere()
  {
    $Bean = new BilanMatiereBean();
    $content = $Bean->getFragmentObservationMatiere();
    return '{"blocMatiere": '.json_encode($content).'}';
  }

  /**
   * @return CompteRendu
   */
  public function dealWithSearch()
  {
    ////////////////////////////////////////////////////////////////////////////
    // On initialise le Compte-Rendu recherché.
    $crKey = $this->post[self::FIELD_CRKEY];
    $CompteRendu = $this->CompteRenduServices->getCompteRenduByCrKey($crKey);
    $CompteRenduBean = $CompteRendu->getBean();
    if ($crKey!='' && $CompteRendu->getId()=='') {
      $attributes = array(
        self::NOTIF_DANGER,
        'Clé inexistante',
        "<br>Vous devriez vérifier la saisie de votre clé, elle semble ne pas exister. Si le problème persiste, contactez l'administrateur.",
      );
      $CompteRendu->setNotifications($CompteRenduBean->getRender($this->urlFragmentNotification, $attributes));
    } else {
      $msgAlert = '<br>Voici le Compte-Rendu correspondant au code saisi (<a href="?'.self::FIELD_CRKEY.'='.$crKey.'">'.$crKey.'</a>).';
      if ($CompteRendu->getValue(self::FIELD_STATUS)==self::STATUS_ARCHIVED) {
        $msgAlert .= "<br>Toutefois, ce Compte-Rendu est une version archivée, il ne peut pas être modifié.";
      }
      // Construction du PDF associé.
      $nbPages = AperdPDF::buildPdf($CompteRendu);
      // Et on ajoute une alerte pour information.
      $msgAlert .= '<br>Vous pouvez télécharger une version <a href="'.$this->urlPdfFiles.$crKey.'.pdf">PDF</a>. (clic droit enregistrer sous)';
      $attributes = array(
        self::NOTIF_INFO,
        'Compte-Rendu retrouvé',
        $msgAlert,
      );
      $notifications = $CompteRenduBean->getRender($this->urlFragmentNotification, $attributes);
      if ($nbPages>=3) {
        $attributes = array(
          self::NOTIF_WARNING,
          'Trop de pages',
          'Attention, le PDF généré à partir de votre compte rendu comporte 3 pages ou plus.<br>Merci de le reprendre pour réduire le nombre de pages à 2.',
        );
        $notifications .= $CompteRenduBean->getRender($this->urlFragmentNotification, $attributes);
      }
      $CompteRendu->setNotifications($notifications);
    }
    return $CompteRendu;
  }

  /**
   * @return CompteRendu
   */
  public function dealWithSave()
  {
    $CompteRendu = new CompteRendu();
    ////////////////////////////////////////////////////////////////////////////
    // On initialise le Compte-Rendu saisi.
    $CompteRendu->setByPost($this->post);
    $CompteRenduBean = $CompteRendu->getBean();
    // Les champs obligatoires ont-ils été remplis ?
    if (!$CompteRendu->checkMandatory()) {
      // Si ce n'est pas le cas, on retourne une erreur.
      $attributes = array(
        self::NOTIF_DANGER,
        'Champs Obligatoires non remplis',
        "<br>Vous avez visiblement omis de renseigner certains champs. Les champs en question devraient être signalés en rouge.<br>Corrigez le formulaire et re-soumettez-le. Si le problème persiste, contactez l'administrateur.",
      );
      $CompteRendu->setNotifications($CompteRenduBean->getRender($this->urlFragmentNotification, $attributes));
    } else {
      // On recherche un ancien Compte-Rendu avec les critères suivants :
      //  - anneeScolaireId
      //  - trimestre
      //  - classeId
      $args = array(
        self::FIELD_ANNEESCOLAIRE_ID => $CompteRendu->getValue(self::FIELD_ANNEESCOLAIRE_ID),
        self::FIELD_TRIMESTRE => $CompteRendu->getValue(self::FIELD_TRIMESTRE),
        self::FIELD_CLASSE_ID => $CompteRendu->getValue(self::FIELD_CLASSE_ID),
        self::FIELD_STATUS => self::STATUS_PUBLISHED,
      );
      $CompteRendus = $this->CompteRenduServices->getCompteRendusWithFilters($args);
      $notifications = '';
      if (!empty($CompteRendus)) {
        // On a une ancienne version
        $OldCompteRendu = array_shift($CompteRendus);
        $OldCompteRendu->setStatus(self::STATUS_ARCHIVED);
        $this->CompteRenduServices->updateLocal($OldCompteRendu);
        // On ajoute une alerte pour information.
        $attributes = array(
          self::NOTIF_WARNING,
          'Ancienne version archivée',
          "<br>Une ancienne version de ce Compte-Rendu existait déjà. Elle a été archivée et ne peut plus être modifiée. Elle reste toutefois consultable.",
        );
        $notifications = $CompteRenduBean->getRender($this->urlFragmentNotification, $attributes);
      }
      // Quoiqu'il en soit, on sauvegarde la nouvelle.
      $CompteRendu->setStatus(self::STATUS_PUBLISHED);
      $this->CompteRenduServices->insertLocal($CompteRendu);
      $crKey = $CompteRendu->getValue(self::FIELD_CRKEY);
      $this->dealWithSaveObservations();
      // Construction du PDF associé.
      $nbPages = AperdPDF::buildPdf($CompteRendu);
      // Et on ajoute une alerte pour information.
      $msgAlert  = '<br>Votre Compte-Rendu a été sauvegardé. Il peut désormais être consulté en utilisant cette clé : <a href="?';
      $msgAlert .= self::FIELD_CRKEY.'='.$crKey.'">'.$crKey.'</a>.<br>Vous pouvez télécharger une version <a href="'.$this->urlPdfFiles;
      $msgAlert .= $crKey.'.pdf">PDF</a>. (clic droit enregistrer sous)';
      $attributes = array(
        self::NOTIF_SUCCESS,
        'Version sauvegardée',
        $msgAlert,
      );
      $notifications .= $CompteRenduBean->getRender($this->urlFragmentNotification, $attributes);
      if ($nbPages>=3) {
        $attributes = array(
          self::NOTIF_WARNING,
          'Trop de pages',
          'Attention, le PDF généré à partir de votre compte rendu comporte 3 pages ou plus.<br>Merci de le reprendre pour réduire le nombre de pages à 2.',
        );
        $notifications .= $CompteRenduBean->getRender($this->urlFragmentNotification, $attributes);
      }
      $CompteRendu->setNotifications($notifications);
    }
    return $CompteRendu;
  }

  private function dealWithSaveObservations()
  {
    $id = MySQL::getLastInsertId();
    foreach ($this->post[self::FIELD_MATIERE_ID.'s'] as $key => $value) {
      if ($value==-1) {
        continue;
      }
      $attributes = array(
        self::FIELD_COMPTERENDU_ID => $id,
        self::FIELD_MATIERE_ID => $value,
        self::FIELD_ENSEIGNANT_ID => array_shift($this->post[self::FIELD_ENSEIGNANT_ID.'s']),
        self::FIELD_STATUS => array_shift($this->post[self::FIELD_STATUS]),
        self::FIELD_OBSERVATIONS => stripslashes(array_shift($this->post[self::FIELD_OBSERVATIONS])),
      );
      $BilanMatiere = new BilanMatiere();
      $BilanMatiere->setByPost($attributes);
      $this->BilanMatiereServices->insertLocal($BilanMatiere);
    }
  }
}
