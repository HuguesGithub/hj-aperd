<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CompteRenduBean
 * @author Hugues
 * @version 1.21.06.29
 * @since 1.21.06.01
 */
class CompteRenduBean extends LocalBean
{
  protected $urlTemplateRowAdmin = 'web/pages/admin/fragments/row-compte-rendu.php';
  protected $strNonRenseigne     = '<strong>Non renseigné</strong>';


  /**
   * Class Constructor
   * @param CompteRendu $CompteRendu
   * @version 1.00.00
   * @since 1.00.00
   */
  public function __construct($CompteRendu='')
  {
    $this->BilanMatiereServices = new BilanMatiereServices();
    $this->CompteRenduServices = new CompteRenduServices();
    $this->CompteRendu = ($CompteRendu=='' ? new CompteRendu() : $CompteRendu);
  }

  /**
   */
  public function getRowForAdminPage($args=array())
  {
    $this->queryArgs = array_merge(
      $args,
      array(
        self::CST_ONGLET     => self::PAGE_COMPTE_RENDU,
        self::CST_POSTACTION => self::CST_EDIT,
        self::FIELD_ID       => $this->CompteRendu->getId(),
        self::ATTR_TYPE      => '',
      )
    );

    $status = $this->CompteRendu->getStatus();
    if ($status=='archived') {
      // Il faudrait un lien vers le PDF;
      $linkToCr = $status;
    } else {
      $linkToCr = '<a href="/compte-rendu/?crKey='.$this->CompteRendu->getCrKey().'">'.$status.'</a>';
    }

    $tds = '';
    $tds .= $this->getTdCheckbox($this->CompteRendu);
    $tds .= $this->getTdActions();
    $tds .= $this->getTdStandard('T'.$this->CompteRendu->getTrimestre());
    $tds .= $this->getTdStandard($this->CompteRendu->getDivision()->getLabelDivision());
    $tds .= $this->getTdStandard($linkToCr);
    $tds .= $this->getTdStandard($this->CompteRendu->getDateConseil());
    $tds .= $this->getTdStandard($this->CompteRendu->getAdministration()->getNomTitulaire());
    return $this->getBalise(self::TAG_TR, $tds);
  }

  private function getTdActions()
  {
    $href  = $this->getQueryArg($this->queryArgs);
    $label = $this->CompteRendu->getAnneeScolaire()->getAnneeScolaire();
    return $this->getTdParentActions($label, $href);
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

  public function getStep5()
  {
    $content  = '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>';
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

  /**
   * @return string
   * @version 1.21.06.29
   * @since 1.21.06.01
   */
  public function getStep4()
  {
    $crStep4 = 'web/pages/admin/fragments/cr-step4.php';

    $valeur = $this->CompteRendu->getValue(self::FIELD_NBFELICITATIONS);
    $nbFelicitations = ($valeur==-1 ? $this->strNonRenseigne : $valeur);
    $valeur = $this->CompteRendu->getValue(self::FIELD_NBMGTVL);
    $nbMgTvl = ($valeur==-1 ? $this->strNonRenseigne : $valeur);
    $valeur = $this->CompteRendu->getValue(self::FIELD_NBCOMPLIMENTS);
    $nbCompliments = ($valeur==-1 ? $this->strNonRenseigne : $valeur);
    $valeur = $this->CompteRendu->getValue(self::FIELD_NBMGCPT);
    $nbMgCpt = ($valeur==-1 ? $this->strNonRenseigne : $valeur);
    $valeur = $this->CompteRendu->getValue(self::FIELD_NBENCOURAGEMENTS);
    $nbEnc = ($valeur==-1 ? $this->strNonRenseigne : $valeur);
    $valeur = $this->CompteRendu->getValue(self::FIELD_NBMGCPTTVL);
    $nbMgcCptTvl = ($valeur==-1 ? $this->strNonRenseigne : $valeur);

    $args = array(
      $nbFelicitations,
      $nbMgTvl,
      $nbCompliments,
      $nbMgCpt,
      $nbEnc,
      $nbMgcCptTvl,
    );
    return $this->getRender($crStep4, $args);
  }

  /**
   * @return string
   * @version 1.21.06.29
   * @since 1.21.06.01
   */
  public function getStep3()
  {
    $crStep4 = 'web/pages/admin/fragments/cr-step3.php';

    $valeur = str_replace(array("\r\n", "\r", "\n"), array("<br>", "<br>", "<br>"), $this->CompteRendu->getValue(self::FIELD_BILANELEVES));
    $strBilanEleves = (empty($valeur) ? '<strong>Données manquantes : [Bilan Délégués Elèves]</strong>' : $valeur);
    $valeur = str_replace(array("\r\n", "\r", "\n"), array("<br>", "<br>", "<br>"), $this->CompteRendu->getValue(self::FIELD_BILANPARENTS));
    $strBilanParents = (empty($valeur) ? '<strong>Données manquantes : [Bilan Délégués Parents]</strong>' : $valeur);

    $args = array(
      $strBilanEleves,
      $strBilanParents,
    );
    return $this->getRender($crStep3, $args);
  }

  /**
   * @return string
   * @version 1.21.06.29
   * @since 1.21.06.01
   */
  public function getStep2BilanProf()
  {
    $content  = '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>';
    $content .= '<div class="pdfParagrapheTitre">Bilan du Professeur Principal</div><div>';
    $valeur = $this->CompteRendu->getValue(self::FIELD_BILANPROFPRINCIPAL);
    $content .= (empty($valeur) ? '<strong>Données manquantes : [Bilan Professeur Principal]</strong>' : $valeur);
    $content .= '</div></div>';
    return $content;
  }

  /**
   * @return string
   * @version 1.21.06.29
   * @since 1.21.06.01
   */
  public function getStep2BilanMatieres()
  {
    $BilanMatieres = $this->BilanMatiereServices->getBilanMatieresWithFilters(array(self::FIELD_COMPTERENDU_ID => $this->CompteRendu->getId()));

    $content  = '<div class="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>';
    $content .= '<table class="table table-sm table-striped">';
    $content .= '<tr><th style="width:18%;">Matière (Nom)</th><th style="width:9%;">Statut</th><th>Observations</th></tr>';

    foreach ($BilanMatieres as $BilanMatiere) {
      $content .= '<tr><td>'.$BilanMatiere->getMatiere()->getLabelMatiere();
      if ($BilanMatiere->getEnseignantId()=='') {
        $content .= '<td class="bg-danger">Non saisi</td>';
      } else {
        $nomEnseignant = $BilanMatiere->getEnseignant()->getGenre().' '.$BilanMatiere->getEnseignant()->getNomEnseignant();
        $content .= '<br>'.$nomEnseignant.'</td>';
      }
      if ($BilanMatiere->getStrStatut()=='') {
        $content .= '<td class="bg-danger">Non saisi</td>';
      } else {
        $content .= '<td>'.$BilanMatiere->getStrStatut().'</td>';
      }
      if ($BilanMatiere->getObservations()=='') {
        $content .= '<td class="bg-warning">Non saisi</td>';
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
    $content  = '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>';
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


