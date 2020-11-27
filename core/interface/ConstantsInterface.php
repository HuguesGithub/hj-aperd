<?php
/**
 * Interface Constants
 * @author Hugues
 * @version 1.00.01
 * @since 1.00.00
 */
interface ConstantsInterface
{
  /////////////////////////////////////////////////
  // Action Ajax
  const AJAX_ACTION          = 'ajaxAction';
  const AJAX_GETNEWMATIERE   = 'getNewMatiere';
  const AJAX_PAGED           = 'paged';
  const AJAX_SAVE            = 'save';
  const AJAX_SEARCH          = 'search';

  /////////////////////////////////////////////////
  // Attributs
  const ATTR_CLASS             = 'class';
  const ATTR_ID                = 'id';
  const ATTR_NAME              = 'name';
  const ATTR_PLACEHOLDER       = 'placeholder';
  const ATTR_REQUIRED          = 'required';
  const ATTR_ROWS              = 'rows';
  const ATTR_SELECTED          = 'selected';
  const ATTR_TYPE              = 'type';
  const ATTR_VALUE             = 'value';

  /////////////////////////////////////////////////
  // On conserve malgré tout quelques constantes
  const CST_DEFAULT_SELECT   = 'Choisir...';
  const CST_EDIT             = 'edit';
  const CST_FORMCONTROL      = 'form-control';
  const CST_ID               = 'id';
  const CST_MD_SELECT        = 'form-control md-select';
  const CST_MD_TEXTAREA      = 'form-control md-textarea';
  const CST_ONGLET           = 'onglet';
  const CST_POSTACTION       = 'postAction';
  const CST_SELECTED         = 'selected';
  const CST_TEXT             = 'text';

  /////////////////////////////////////////////////
  // Fields
  const FIELD_ID                  = 'id';
  const FIELD_ADMINISTRATION_ID   = 'administrationId';
  const FIELD_ANNEESCOLAIRE       = 'anneeScolaire';
  const FIELD_ANNEESCOLAIRE_ID    = 'anneeScolaireId';
  const FIELD_AUTEURREDACTION     = 'auteurRedaction';
  const FIELD_BILANELEVES         = 'bilanEleves';
  const FIELD_BILANPARENTS        = 'bilanParents';
  const FIELD_BILANPROFPRINCIPAL  = 'bilanProfPrincipal';
  const FIELD_CLASSE_ID           = 'classeId';
  const FIELD_COMPTERENDU_ID      = 'compteRenduId';
  const FIELD_CRKEY               = 'crKey';
  const FIELD_DATECONSEIL         = 'dateConseil';
  const FIELD_DATEREDACTION       = 'dateRedaction';
  const FIELD_ENFANT1             = 'enfant1';
  const FIELD_ENFANT2             = 'enfant2';
  const FIELD_ENSEIGNANT_ID       = 'enseignantId';
  const FIELD_LABELCLASSE         = 'labelClasse';
  const FIELD_LABELMATIERE        = 'labelMatiere';
  const FIELD_LABELPOSTE          = 'labelPoste';
  const FIELD_MAILCONTACT         = 'mailContact';
  const FIELD_MATIERE_ID          = 'matiereId';
  const FIELD_NBCOMPLIMENTS       = 'nbCompliments';
  const FIELD_NBELEVES            = 'nbEleves';
  const FIELD_NBENCOURAGEMENTS    = 'nbEncouragements';
  const FIELD_NBFELICITATIONS     = 'nbFelicitations';
  const FIELD_NBMGCPT             = 'nbMgComportement';
  const FIELD_NBMGTVL             = 'nbMgTravail';
  const FIELD_NBMGCPTTVL          = 'nbMgComportementTravail';
  const FIELD_NOMENSEIGNANT       = 'nomEnseignant';
  const FIELD_NOMTITULAIRE        = 'nomTitulaire';
  const FIELD_OBSERVATIONS        = 'observations';
  const FIELD_PARENT1             = 'parent1';
  const FIELD_PARENT2             = 'parent2';
  const FIELD_PROFPRINCIPAL_ID    = 'profPrincipalId';
  const FIELD_STATUS              = 'status';
  const FIELD_TRIMESTRE           = 'trimestre';

  /////////////////////////////////////////////////
  // Formats
  const FORMAT_DATE_JJMMAAAA   = 'jj/mm/aaaa';
  const FORMAT_DATE_YMDHIS     = 'Y-m-d H:i:s';

  /////////////////////////////////////////////////
  // Notifications
  const NOTIF_DANGER           = 'danger';
  const NOTIF_INFO             = 'info';
  const NOTIF_IS_INVALID       = 'is-invalid';
  const NOTIF_SUCCESS          = 'success';
  const NOTIF_WARNING          = 'warning';

  /////////////////////////////////////////////////
  // Allowed Pages :
  const PAGE_CLASSE            = 'classe';
  const PAGE_COMPTE_RENDU      = 'compte-rendu';
  const PAGE_CONFIGURATION     = 'configuration';
  const PAGE_ENSEIGNANT        = 'enseignant';
  const PAGE_MATIERE           = 'matiere';

  /////////////////////////////////////////////////
  // Statuts :
  const STATUS_ARCHIVED        = 'archived';
  const STATUS_PUBLISHED       = 'published';

  /////////////////////////////////////////////////
  // Tags
  const TAG_INPUT              = 'input';
  const TAG_OPTION             = 'option';
  const TAG_SELECT             = 'select';
  const TAG_TEXTAREA           = 'textarea';

  /////////////////////////////////////////////////
  // Divers
  const JOKER_SEARCH           = '%';
  const ORDER_ASC              = 'ASC';

}
