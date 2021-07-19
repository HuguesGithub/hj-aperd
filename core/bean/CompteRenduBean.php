<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CompteRenduBean
 * @author Hugues
 * @version 1.21.07.16
 * @since 1.21.06.01
 */
class CompteRenduBean extends LocalBean
{
  protected $urlTemplateRowAdmin = 'web/pages/admin/fragments/row-compte-rendu.php';
  /**
   * Class Constructor
   * @param CompteRendu $CompteRendu
   * @version 1.00.00
   * @since 1.00.00
   */
  public function __construct($CompteRendu='')
  {
    parent::__construct();
    $this->BilanMatiereServices = new BilanMatiereServices();
    $this->CompteRenduServices = new CompteRenduServices();
    $this->CompteRendu = ($CompteRendu=='' ? new CompteRendu() : $CompteRendu);
  }

  /**
   * @return string
   * @version 1.21.07.16
   * @since 1.21.07.15
   */
  public function getCard($trimestre='')
  {
    $urlTemplateCard = 'web/pages/public/fragments/card-compte-rendu.php';
    $Division = $this->getDivision();

    ///////////////////////////////////////////////////
    // On récupère le Trimestre
    $strTrimestre = ($this->CompteRendu->getTrimestre() ? $this->CompteRendu->getTrimestre() : $trimestre);
    // On récupère le libellé de la Division
    $strLabelDivision = ($this->CompteRendu->getDivision()->getLabelDivision() ? $this->CompteRendu->getDivision()->getLabelDivision() : $Division->getLabelDivision());
    // On récupère la Date
    $strDate = ($this->CompteRendu->getDateConseil()!='' ? $this->CompteRendu->getDateConseil() : 'Non définie');
    // On récupère le Nom de la Présidence
    $Administration = $this->CompteRendu->getAdministration();
    $strPresidence = ($Administration->getId()!='' ? $Administration->getGenre().self::CST_BLANK.$Administration->getNomTitulaire() : 'Non définie');
    // On défini le lien vers la page de saisie des Comptes Rendus
    $strUrlCard = get_permalink(get_page_by_path(self::PAGE_COMPTE_RENDU)).'?trimestre='.$strTrimestre;

    // On récupère le statut
    $status = $this->CompteRendu->getStatus();
    $strStatut = $this->getLibelleForStatus($status);
    switch ($status) {
      case self::STATUS_FUTURE :
        $libelleAction = 'Aller le rédiger';
      break;
      case self::STATUS_WORKING :
        $libelleAction = 'Poursuivre la rédaction';
      break;
      case self::STATUS_PENDING :
        $strStatut = 'A valider par P2';
        // Si P2 :
        $libelleAction = 'Aller le valider';
        // Sinon
        // Poursuivre la rédaction
      break;
      case self::STATUS_PUBLISHED:
      case self::STATUS_MAILED:
        $libelleAction = 'Aller le consulter';
        // Dès lors qu'il est Validé ou Envoyé, le lien doit pointer vers le PDF
        $strUrlCard = '#';
      break;
      default :
        $libelleAction = 'Aller le créer';
      break;
    }
    ///////////////////////////////////////////////////

    ///////////////////////////////////////////////////
    // On défini le Template et on le restitue
    $args = array(
      // Numéro du Trimestre - 1
      $strTrimestre,
      // Libellé de la Division - 2
      $strLabelDivision,
      // Date du conseil - 3
      $strDate,
      // Nom du Président - 4
      $strPresidence,
      // Statut du conseil de classe - 5
      $strStatut,
      // Lien vers le Compte-rendu - 6
      $strUrlCard,
      // Libellé du lien - 7
      $libelleAction,
    );

    return $this->getRender($urlTemplateCard, $args);
  }

  /**
   * @param array $args
   * @return string
   * @version 1.21.07.16
   * @since 1.21.06.01
   */
  public function getRowForAdminPage($checked=false, $args=array())
  {
    $queryArgs = array_merge(
      $args,
      array(
        self::CST_ONGLET     => self::PAGE_COMPTE_RENDU,
        self::CST_POSTACTION => self::CST_EDIT,
        self::FIELD_ID       => $this->CompteRendu->getId(),
        self::ATTR_TYPE      => '',
      )
    );
    $urlEdition = $this->getQueryArg($queryArgs);
    // Création du lien de suppression
    $queryArgs[self::CST_POSTACTION] = self::CST_DELETE;
    $urlSuppression = $this->getQueryArg($queryArgs);

    $status = $this->CompteRendu->getStatus();
    switch ($status) {
      case self::STATUS_FUTURE :
      case self::STATUS_WORKING :
      case self::STATUS_PENDING :
        $linkedUrl = '/compte-rendu/?trimestre='.$this->CompteRendu->getTrimestre();
      break;
      case self::STATUS_PUBLISHED :
      case self::STATUS_MAILED :
        $linkedUrl = 'http://storage.jhugues.fr/Storage/Aperd/'.$this->CompteRendu->getUrlPdf();
      break;
      default :
        $linkedUrl = '#';
      break;
    }

    $attributes = array(
      // Identifiant du Compte Rendu - 1
      $this->CompteRendu->getId(),
      // Url d'édition du Compte Rendu - 2
      $urlEdition,
      // Trimestre - 3
      'T'.$this->CompteRendu->getTrimestre(),
      // Division - 4
      $this->CompteRendu->getDivision()->getLabelDivision(),
      // Statut - 5
      '<a href="'.$linkedUrl.'">'.$this->getLibelleForStatus($status).'</a>',
      // Date du conseil de classe - 6
      $this->CompteRendu->getDateConseil(),
      // Présidence - 7
      $this->CompteRendu->getAdministration()->getNomTitulaire(),
      // Url de suppression - 8
      $urlSuppression,
      // Sélectionnée ou non - 9
      $checked ? self::CST_BLANK.self::CST_CHECKED : '',
    );
    return $this->getRender($this->urlTemplateRowAdmin, $attributes);
  }

  public function getStep6()
  {
    $content  = '<div class="row apercuPdf">';
    // Première page
    $content .= '<div class="col-md-6 border">';
    $content .= $this->getStep1();
    $content .= $this->getStep2BilanMatieres();
    $content .= '</div>';
    // Deuxième page
    $content .= '<div class="col-md-6 border">';
    $content .= $this->getStep2BilanProf();
    $content .= $this->getStep3();
    $content .= $this->getStep4();
    $content .= $this->getStep5();
    $content .= '</div></div>';
    return $content;
  }

  public function getStep5()
  {
    $urlTemplateStep = 'web/pages/public/fragments/apercu-compte-rendu-step5.php';

    $valeur = $this->CompteRendu->getValue(self::FIELD_DATEREDACTION);
    $strDateRedaction = (empty($valeur) ? $this->getStringDonneesManquantes('Date de rédaction') : $valeur);

    $valeur = $this->CompteRendu->getValue(self::FIELD_AUTEURREDACTION);
    $strAuteurRedaction = (empty($valeur) ? $this->getStringDonneesManquantes('Auteur du Compte-Rendu') : $valeur);

    $args = array(
      // Date Compte Rendu - 1
      $strDateRedaction,
      // Auteur(s) Compte Rendu - 2
      $strAuteurRedaction,
      // Accord auteur(s) - 3
      (strpos($strAuteurRedaction, ' et ')!==false ? 'leur' : 'sa')
    );

    return $this->getRender($urlTemplateStep, $args);
  }

  public function getStep4()
  {
    $urlTemplateStep4 = 'web/pages/public/fragments/apercu-compte-rendu-step4.php';
    $strNomRenseigne  = '<strong>Non renseigné</strong>';
    /////////////////////////////////////////////////////////////////////////
    // Formattage nbFelicitations
    $valeur = $this->CompteRendu->getValue(self::FIELD_NBFELICITATIONS);
    $frmtNbFelicitations = ($valeur==-1 ? $strNomRenseigne : $valeur);
    // Formattage nbFelicitations
    $valeur = $this->CompteRendu->getValue(self::FIELD_NBMGTVL);
    $frmtNbMgTvl = ($valeur==-1 ? $strNomRenseigne : $valeur);
    // Formattage nbFelicitations
    $valeur = $this->CompteRendu->getValue(self::FIELD_NBCOMPLIMENTS);
    $frmtNbCompliments = ($valeur==-1 ? $strNomRenseigne : $valeur);
    // Formattage nbFelicitations
    $valeur = $this->CompteRendu->getValue(self::FIELD_NBMGCPT);
    $frmtNbMgCpt = ($valeur==-1 ? $strNomRenseigne : $valeur);
    // Formattage nbFelicitations
    $valeur = $this->CompteRendu->getValue(self::FIELD_NBENCOURAGEMENTS);
    $frmtNbEncouragements = ($valeur==-1 ? $strNomRenseigne : $valeur);
    // Formattage nbFelicitations
    $valeur = $this->CompteRendu->getValue(self::FIELD_NBMGCPTTVL);
    $frmtNbMgCptTvl = ($valeur==-1 ? $strNomRenseigne : $valeur);
    /////////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////////
    // On enrichi le Template puis on le retourne.
    $args = array(
      // Nombre de Félicitations - 1
      $frmtNbFelicitations,
      // Nombre de Mises en Garde Travail - 2
      $frmtNbMgTvl,
      // Nombre de Compliments - 3
      $frmtNbCompliments,
      // Nombre de Mises en Garde Comportement - 4
      $frmtNbMgCpt,
      // Nombre de Encouragements - 5
      $frmtNbEncouragements,
      // Nombre de Mises en Garde Comportement et Travail - 6
      $frmtNbMgCptTvl,
    );
    return $this->getRender($urlTemplateStep4, $args);
  }

  public function getStep3()
  {
    $urlTemplateStep3 = 'web/pages/public/fragments/apercu-compte-rendu-step3.php';
    /////////////////////////////////////////////////////////////////////////
    // Formattage du Bilan Elèves
    $valeur = str_replace(array("\r\n", "\r", "\n"), array("<br>", "<br>", "<br>"), $this->CompteRendu->getValue(self::FIELD_BILANELEVES));
    $frmtBilanEleves  = (empty($valeur) ? $this->getStringDonneesManquantes('Bilan Délégués Elèves') : $valeur);
    // Formattage du Bilan Parents
    $valeur = str_replace(array("\r\n", "\r", "\n"), array("<br>", "<br>", "<br>"), $this->CompteRendu->getValue(self::FIELD_BILANPARENTS));
    $frmtBilanParents = (empty($valeur) ? $this->getStringDonneesManquantes('Bilan Délégués Parents') : $valeur);
    /////////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////////
    // On enrichi le Template puis on le retourne.
    $args = array(
      // Bilan Elèves - 1
      $frmtBilanEleves,
      // Bilan Parents - 2
      $frmtBilanParents,
    );
    return $this->getRender($urlTemplateStep3, $args);
  }

  public function getStep2BilanProf()
  {
    $urlTemplateStep = 'web/pages/public/fragments/apercu-compte-rendu-step2-bilan-profs.php';

    $valeur = $this->CompteRendu->getValue(self::FIELD_BILANPROFPRINCIPAL);
    $content = (empty($valeur) ? $this->getStringDonneesManquantes('Bilan Professeur Principal') : $valeur);

    $args = array(
      // Contenu du tbody : liste des Bilans - 1
      $content,
    );
    return $this->getRender($urlTemplateStep, $args);
  }

  private function getCellNonSaisie()
  { return '<td class="bg-danger">Non saisi</td>'; }

  public function getStep2BilanMatieres()
  {
    $urlTemplateStep = 'web/pages/public/fragments/apercu-compte-rendu-step2-bilan-matieres.php';

    $args = array(
      self::FIELD_COMPTERENDU_ID => $this->CompteRendu->getId(),
    );
    $BilanMatieres = $this->BilanMatiereServices->getBilanMatieresWithFilters($args);

    $content = '';
    foreach ($BilanMatieres as $BilanMatiere) {
      $content .= '<tr><td>'.$BilanMatiere->getMatiere()->getLabelMatiere();
      if ($BilanMatiere->getEnseignantId()=='') {
        $content .= $this->getCellNonSaisie();
      } else {
        $nomEnseignant = $BilanMatiere->getEnseignant()->getGenre().' '.$BilanMatiere->getEnseignant()->getNomEnseignant();
        $content .= '<br>'.$nomEnseignant.'</td>';
      }
      if ($BilanMatiere->getStrStatut()=='') {
        $content .= $this->getCellNonSaisie();
      } else {
        $content .= $this->getBalise(self::TAG_TD, $BilanMatiere->getStrStatut());
      }
      if ($BilanMatiere->getObservations()=='') {
        $content .= $this->getCellNonSaisie();
      } else {
        $content .= $this->getBalise(self::TAG_TD, $BilanMatiere->getObservations());
      }
      $content .= '</tr>';
    }

    $args = array(
      // Contenu du tbody : liste des Bilans - 1
      $content,
    );
    return $this->getRender($urlTemplateStep, $args);
  }

  public function getStep2()
  {
    $content  = $this->getStep2BilanProf();
    $content .= $this->getStep2BilanMatieres();
    return $content;
  }

  public function getStep1()
  {
    $urlTemplateStep1 = 'web/pages/public/fragments/apercu-compte-rendu-step1.php';

    /////////////////////////////////////////////////////////////////////////
    // Formattage du Trimestre
    $trim = $this->CompteRendu->getValue(self::FIELD_TRIMESTRE);
    $frmtTrimestre = $trim.($trim==1 ? 'er' : 'ème');
    // Formattage Effectif
    $valeur = $this->CompteRendu->getValue(self::FIELD_NBELEVES);
    $frmtEffectif  = ($valeur==0 ? $this->getStringDonneesManquantes('Nombre d\'élèves') : $valeur);
    // Formattage Date
    $valeur = $this->CompteRendu->getValue(self::FIELD_DATECONSEIL);
    $frmtDate      = (empty($valeur) ? $this->getStringDonneesManquantes('Date du Conseil') : $valeur);
    // Formattage Présidence
    $valeur = $this->CompteRendu->getAdministrationId();
    $frmtPresidence = ($valeur==0 ? $this->getStringDonneesManquantes('Présidence') : $this->CompteRendu->getAdministration()->getFullName());
    // Formattage Prof Principal
    $valeur = $this->CompteRendu->getValue(self::FIELD_PROFPRINCIPAL_ID);
    $texte = ($valeur==0 ? $this->getStringDonneesManquantes('Professeur Principal') : $this->CompteRendu->getProfPrincipal()->getProfPrincipal());
    $frmtProfPrinc = $texte;
    // Formattage Parents Délégués
    $valeur = $this->CompteRendu->getValue(self::FIELD_PARENT1);
    $frmtParentDeleg = (empty($valeur) ? $this->getStringDonneesManquantes('Parents Délégués') : $this->CompteRendu->getStrParentsDelegues());
    // Formattage Elèves Délégués
    $valeur = $this->CompteRendu->getValue(self::FIELD_ENFANT1);
    $frmtEleveDeleg  = (empty($valeur) ? $this->getStringDonneesManquantes('Elèves Délégués') : $this->CompteRendu->getStrElevesDelegues());
    /////////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////////
    // On enrichi le Template puis on le retourne.
    $args = array(
      // Numéro du trimestre - 1
      $frmtTrimestre,
      // Libellé de la Division - 2
      str_replace('0', 'è', $this->CompteRendu->getDivision()->getLabelDivision()),
      // Effectif de la Division - 3
      $frmtEffectif,
      // Date du Conseil - 4
      $frmtDate,
      // Présidence - 5
      $frmtPresidence,
      // Prof Principal - 6
      $frmtProfPrinc,
      // Parents Délégués - 7
      $frmtParentDeleg,
      // Elèves Délégués - 8
      $frmtEleveDeleg,
    );
    return $this->getRender($urlTemplateStep1, $args);
  }

  private function getStringDonneesManquantes($label)
  { return '<strong>Données manquantes : ['.$label.']</strong>'; }

}
