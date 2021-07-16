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
    $strPresidence = ($Administration->getId()!='' ? $Administration->getGenre().self::CST_BLANK.$Administration->getNomAdministration() : 'Non définie');
    // On récupère le statut
    switch ($this->CompteRendu->getStatus()) {
      case self::STATUS_FUTURE :
        $strStatut = 'A rédiger';
        $libelleAction = 'Aller le rédiger';
      break;
      default :
        $strStatut = 'A créer';
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
      get_permalink(get_page_by_path(self::PAGE_COMPTE_RENDU)).'?trimestre='.$strTrimestre,
      // Libellé du lien - 7
      $libelleAction,
    );

    return $this->getRender($urlTemplateCard, $args);
  }

  /**
   * @param array $args
   * @return string
   * @version 1.21.07.05
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
    if ($status=='archived') {
      // Il faudrait un lien vers le PDF;
      $linkToCr = $status;
    } else {
      $linkToCr = '<a href="/compte-rendu/?trimestre='.$this->CompteRendu->getTrimestre().'">'.$status.'</a>';
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
      $linkToCr,
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
    $content .= '</div>';
    $content .= '</div>';
    return $content;
  }

  private function getCloseButton()
  { return '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>'; }

  public function getStep5()
  {
    $content  = $this->getCloseButton();
    $content .= '<div class="pdfParagrapheTitre">Informations générales</div>';
    $content .= "Réunions mensuelles : L'association des Parents d'Élèves se réunit un mercredi par mois (hors vacances scolaires). Vous pouvez également découvrir la vie du collège et les actions de l'association sur son site internet.<br>";

    $valeur = $this->CompteRendu->getValue(self::FIELD_DATEREDACTION);
    $texte  = (empty($valeur) ? '<strong>Données manquantes : [Date de rédaction]</strong>' : $valeur);
    $content .= 'Compte rendu fait le '.$texte;

    $valeur = $this->CompteRendu->getValue(self::FIELD_AUTEURREDACTION);
    $texte  = (empty($valeur) ? '<strong>Données manquantes : [Auteur du Compte-Rendu]</strong>' : $valeur);
    $content .= ' par '.$texte;
    if (!empty($valeur)) {
      $content .= ', sous '.(strpos($texte, ' et ')!==false ? 'leur' : 'sa')." responsabilité.";
    }

    $content .= '</div>';
    return $content;
  }

  private function getDivAttributions($label, $valeur)
  { return '<div class="col-md-6">'.$label.' : '.($valeur==-1 ? '<strong>Non renseigné</strong>' : $valeur).'</div>'; }

  public function getStep4()
  {
    $content  = $this->getCloseButton();
    $content .= '<div class="pdfParagrapheTitre">Attributions du conseil de classe</div>';
    $content .= '<div class="row">';
    $valeur = $this->CompteRendu->getValue(self::FIELD_NBFELICITATIONS);
    $content .= $this->getDivAttributions('Félicitations', $valeur);
    $valeur = $this->CompteRendu->getValue(self::FIELD_NBMGTVL);
    $content .= $this->getDivAttributions('Mises en Garde Travail', $valeur);
    $valeur = $this->CompteRendu->getValue(self::FIELD_NBCOMPLIMENTS);
    $content .= $this->getDivAttributions('Compliments', $valeur);
    $valeur = $this->CompteRendu->getValue(self::FIELD_NBMGCPT);
    $content .= $this->getDivAttributions('Mises en Garde Comportement', $valeur);
    $valeur = $this->CompteRendu->getValue(self::FIELD_NBENCOURAGEMENTS);
    $content .= $this->getDivAttributions('Encouragements', $valeur);
    $valeur = $this->CompteRendu->getValue(self::FIELD_NBMGCPTTVL);
    $content .= $this->getDivAttributions('Mises en Garde Comportement et Travail', $valeur);
    $content .= '</div>';
    $content .= '</div>';
    return $content;
  }

  public function getStep3()
  {
    $content  = $this->getCloseButton();
    $content .= '<div class="pdfParagrapheTitre">Intervention des délégués élèves</div>';
    $content .= '<div>';
    $valeur = str_replace(array("\r\n", "\r", "\n"), array("<br>", "<br>", "<br>"), $this->CompteRendu->getValue(self::FIELD_BILANELEVES));
    $content .= (empty($valeur) ? '<strong>Données manquantes : [Bilan Délégués Elèves]</strong>' : $valeur);
    $content .= '</div>';
    $content .= '<div class="pdfParagrapheTitre">Intervention des délégués parents</div>';
    $content .= '<div>';
    $valeur = str_replace(array("\r\n", "\r", "\n"), array("<br>", "<br>", "<br>"), $this->CompteRendu->getValue(self::FIELD_BILANPARENTS));
    $content .= (empty($valeur) ? '<strong>Données manquantes : [Bilan Délégués Parents]</strong>' : $valeur);
    $content .= '</div>';
    $content .= '</div>';
    return $content;
  }

  public function getStep2BilanProf()
  {
    $content  = $this->getCloseButton();
    $content .= '<div class="pdfParagrapheTitre">Bilan du Professeur Principal</div>';
    $content .= '<div>';
    $valeur = $this->CompteRendu->getValue(self::FIELD_BILANPROFPRINCIPAL);
    $content .= (empty($valeur) ? '<strong>Données manquantes : [Bilan Professeur Principal]</strong>' : $valeur);
    $content .= '</div>';
    $content .= '</div>';
    return $content;
  }

  private function getCellNonSaisie()
  { return '<td class="bg-danger">Non saisi</td>'; }

  public function getStep2BilanMatieres()
  {
    $content  = $this->getCloseButton();
    $content .= '<table class="table table-sm table-striped">';
    $content .= '<tr><th style="width:18%;">Matière (Nom)</th><th style="width:9%;">Statut</th><th>Observations</th></tr>';

    $args = array(
      self::FIELD_COMPTERENDU_ID => $this->CompteRendu->getId(),
    );
    $BilanMatieres = $this->BilanMatiereServices->getBilanMatieresWithFilters($args);
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
        $content .= '<td>'.$BilanMatiere->getStrStatut().'</td>';
      }
      if ($BilanMatiere->getObservations()=='') {
        $content .= $this->getCellNonSaisie();
      } else {
        $content .= '<td>'.$BilanMatiere->getObservations().'</td>';
      }
    }

    $content .= '</table>';
    $content .= '</div>';
    return $content;
  }

  public function getStep2()
  {
    $content  = $this->getStep2BilanProf();
    $content .= $this->getStep2BilanMatieres();
    return $content;
  }

  public function getStep1()
  {
    $content  = $this->getCloseButton();
    // Année Scolaire
    $content .= '<div class="pdfParagrapheTitre" style="text-align: center;">'.'ANNÉE SCOLAIRE '.$this->CompteRendu->getAnneeScolaire()->getAnneeScolaire().'</div>';
    // Trimestre / Classe / Effectifs
    $content .= '<div style="text-align: center;">';
    $trim = $this->CompteRendu->getValue(self::FIELD_TRIMESTRE);
    $frmtTrimestre = $trim.($trim==1 ? 'er' : 'ème');
    $content .= 'Compte-rendu du conseil de classe du '.$frmtTrimestre.' trimestre<br>';
    $valeur = $this->CompteRendu->getValue(self::FIELD_NBELEVES);
    $texte  = ($valeur==0 ? '<strong>Données manquantes : [Nombre d\'élèves]</strong>' : $valeur);
    $content .= 'Classe de : '.str_replace('0', 'è', $this->CompteRendu->getDivision()->getLabelDivision()).'. Effectif de la classe : '.$texte.' élèves';
    $content .= '</div>';
    $content .= '<br>';
    // Texte Introduction
    $content .= '<div>';
    $valeur = $this->CompteRendu->getValue(self::FIELD_DATECONSEIL);
    $texte  = (empty($valeur) ? '<strong>Données manquantes : [Date du Conseil]</strong>' : $valeur);
    $content .= "Le conseil de classe s'est tenu le ".$texte;
    $valeur = $this->CompteRendu->getAdministrationId();
    $texte  = ($valeur==0 ? '<strong>Données manquantes : [Présidence]</strong>' : $this->CompteRendu->getAdministration()->getFullInfo());
    $content .= " sous la présidence de ".$texte;
    $valeur = $this->CompteRendu->getValue(self::FIELD_ENSEIGNANT_ID);
    $texte  = ($valeur==0 ? '<strong>Données manquantes : [Professeur Principal]</strong>' : $this->CompteRendu->getEnseignant()->getProfPrincipal());
    $content .= ", en présence de ".$texte.", des autres professeurs de la classe, ";
    $valeur = $this->CompteRendu->getValue(self::FIELD_PARENT1);
    $texte  = (empty($valeur) ? '<strong>Données manquantes : [Parents Délégués]</strong>' : $this->CompteRendu->getStrParentsDelegues());
    $content .= $texte;
    $valeur = $this->CompteRendu->getValue(self::FIELD_ENFANT1);
    $texte  = (empty($valeur) ? '<strong>Données manquantes : [Elèves Délégués]</strong>' : $this->CompteRendu->getStrElevesDelegues());
    $content .= $texte;
    $content .= '</div>';
    $content .= '</div>';
    return $content;
  }


}
