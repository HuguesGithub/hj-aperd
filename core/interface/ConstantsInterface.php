<?php
/**
 * Interface Constants
 * @author Hugues
 * @version 1.21.06.10
 * @since 1.21.06.04
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
  const AJAX_UPLOAD          = 'ajaxUpload';

  /////////////////////////////////////////////////
  // Attributs
  const ATTR_CLASS             = 'class';
  const ATTR_HREF              = 'href';
  const ATTR_ID                = 'id';
  const ATTR_NAME              = 'name';
  const ATTR_PLACEHOLDER       = 'placeholder';
  const ATTR_READONLY          = 'readonly';
  const ATTR_REQUIRED          = 'required';
  const ATTR_ROWS              = 'rows';
  const ATTR_SELECTED          = 'selected';
  const ATTR_TYPE              = 'type';
  const ATTR_VALUE             = 'value';

  /////////////////////////////////////////////////
  // On conserve malgré tout quelques constantes
  const CST_ACTION           = 'action';
  const CST_BLANK            = ' ';
  const CST_BULK             = 'Bulk';
  const CST_BULK_EXPORT      = 'Bulk-export';
  const CST_BULK_TRASH       = 'Bulk-trash';
  const CST_CHECKED          = 'checked';
  const CST_CREATE           = 'create';
  const CST_CREATION         = 'Création';
  const CST_DEFAULT_SELECT   = 'Choisir...';
  const CST_DELETE           = 'delete';
  const CST_EDITION          = 'Édition';
  const CST_EDIT             = 'edit';
  const CST_EXPORT           = 'export';
  const CST_FORMCONTROL      = 'form-control';
  const CST_HIDDEN           = 'hidden';
  const CST_ID               = 'id';
  const CST_IMPORT           = 'import';
  const CST_MD_SELECT        = 'form-control md-select';
  const CST_MD_TEXTAREA      = 'form-control md-textarea';
  const CST_ONGLET           = 'onglet';
  const CST_POST             = 'post';
  const CST_POSTACTION       = 'postAction';
  const CST_SELECTED         = 'selected';
  const CST_SUPPRESSION      = 'Suppression';
  const CST_TEXT             = 'text';
  const CST_TRASH            = 'trash';

  /////////////////////////////////////////////////
  // Fields
  const FIELD_ID                  = 'id';
  const FIELD_ADHERENT            = 'adherent';
  const FIELD_ADMINISTRATION_ID   = 'administrationId';
  const FIELD_ANNEESCOLAIRE       = 'anneeScolaire';
  const FIELD_ANNEESCOLAIRE_ID    = 'anneeScolaireId';
  const FIELD_AUTEURREDACTION     = 'auteurRedaction';
  const FIELD_BILANELEVES         = 'bilanEleves';
  const FIELD_BILANPARENTS        = 'bilanParents';
  const FIELD_BILANPROFPRINCIPAL  = 'bilanProfPrincipal';
  const FIELD_CLASSE_ID           = 'classeId';
  const FIELD_COMPTERENDU_ID      = 'compteRenduId';
  const FIELD_CONFIG_KEY          = 'configKey';
  const FIELD_CONFIG_VALUE        = 'configValue';
  const FIELD_CRKEY               = 'crKey';
  const FIELD_DATECONSEIL         = 'dateConseil';
  const FIELD_DATEREDACTION       = 'dateRedaction';
  const FIELD_DELEGUE             = 'delegue';
  const FIELD_DIVISION_ID         = 'divisionId';
  const FIELD_ENFANT1             = 'enfant1';
  const FIELD_ENFANT2             = 'enfant2';
  const FIELD_ENSEIGNANT_ID       = 'enseignantId';
  const FIELD_GENRE               = 'genre';
  const FIELD_LABELCLASSE         = 'labelClasse';
  const FIELD_LABELDIVISION       = 'labelDivision';
  const FIELD_LABELMATIERE        = 'labelMatiere';
  const FIELD_LABELPOSTE          = 'labelPoste';
  const FIELD_MAILCONTACT         = 'mailContact';
  const FIELD_MAILPARENT          = 'mailParent';
  const FIELD_MATIERE_ID          = 'matiereId';
  const FIELD_NBCOMPLIMENTS       = 'nbCompliments';
  const FIELD_NBELEVES            = 'nbEleves';
  const FIELD_NBENCOURAGEMENTS    = 'nbEncouragements';
  const FIELD_NBFELICITATIONS     = 'nbFelicitations';
  const FIELD_NBMGCPT             = 'nbMgComportement';
  const FIELD_NBMGTVL             = 'nbMgTravail';
  const FIELD_NBMGCPTTVL          = 'nbMgComportementTravail';
  const FIELD_NOMELEVE            = 'nomEleve';
  const FIELD_NOMENSEIGNANT       = 'nomEnseignant';
  const FIELD_NOMPARENT           = 'nomParent';
  const FIELD_NOMTITULAIRE        = 'nomTitulaire';
  const FIELD_OBSERVATIONS        = 'observations';
  const FIELD_PARENT1             = 'parent1';
  const FIELD_PARENT2             = 'parent2';
  const FIELD_PARENT_ID           = 'parentId';
  const FIELD_PRENOMELEVE         = 'prenomEleve';
  const FIELD_PRENOMENSEIGNANT    = 'prenomEnseignant';
  const FIELD_PRENOMPARENT        = 'prenomParent';
  const FIELD_PROFPRINCIPAL_ID    = 'profPrincipalId';
  const FIELD_STATUS              = 'status';
  const FIELD_TRIMESTRE           = 'trimestre';

  /////////////////////////////////////////////////
  // Formats
  const FORMAT_DATE_JJMMAAAA   = 'jj/mm/aaaa';
  const FORMAT_DATE_YMDHIS     = 'Y-m-d H:i:s';

  /////////////////////////////////////////////////
  // Message
  const MSG_BULK_DELETE_IMPOSSIBLE  = 'Suppressions impossibles : aucun élément sélectionné.';
  const MSG_BULK_EXPORT_IMPOSSIBLE  = 'Export impossible : aucun élément sélectionné.';
  const MSG_BULK_ACTION_INDEFINIE   = 'Action Bulk indéterminée : <strong>%s</strong>.';
  const MSG_BULK_DELETE_SUCCESS     = 'Suppressions réussies.';
  const MSG_CONFIRM_SUPPR_MATIERES  = 'Voulez-vous vraiment <strong>supprimer</strong> les Matières <strong>%s</strong>.';
  const MSG_CONFIRM_SUPPR_MATIERE   = 'Voulez-vous vraiment <strong>supprimer</strong> la Matière <strong>%s</strong>.';
  const MSG_CONFIRM_SUPPR_DIVISIONS = 'Voulez-vous vraiment <strong>supprimer</strong> les Division <strong>%s</strong>.';
  const MSG_CONFIRM_SUPPR_DIVISION  = 'Voulez-vous vraiment <strong>supprimer</strong> la Division <strong>%s</strong>.';
  const MSG_CONFIRM_SUPPR_ENSEIGNANTS  = 'Voulez-vous vraiment <strong>supprimer</strong> les Enseignants <strong>%s</strong>.';
  const MSG_CONFIRM_SUPPR_ENSEIGNANT = 'Voulez-vous vraiment <strong>supprimer</strong> l\'Enseignant <strong>%s</strong>.';
  const MSG_CONFIRM_SUPPR_QUESTIONNAIRES = 'Voulez-vous vraiment <strong>supprimer</strong> les Clefs <strong>%s</strong>.';
  const MSG_CONFIRM_SUPPR_QUESTIONNAIRE  = 'Voulez-vous vraiment <strong>supprimer</strong> la Clef <strong>%s</strong>.';
  const MSG_CONFIRM_SUPPR_ADMINISTRATIONS = 'Voulez-vous vraiment <strong>supprimer</strong> les Administratifs <strong>%s</strong>.';
  const MSG_CONFIRM_SUPPR_ADMINISTRATION  = 'Voulez-vous vraiment <strong>supprimer</strong> l\'Administratif <strong>%s</strong>.';
  const MSG_CONFIRM_SUPPR_ANNEESCOLAIRES = 'Voulez-vous vraiment <strong>supprimer</strong> les Années Scolaires <strong>%s</strong>.';
  const MSG_CONFIRM_SUPPR_ANNEESCOLAIRE  = 'Voulez-vous vraiment <strong>supprimer</strong> l\'Année Scolaire <strong>%s</strong>.';
  const MSG_CONFIRM_SUPPR_PARENTS    = 'Voulez-vous vraiment <strong>supprimer</strong> les Parents <strong>%s</strong>.';
  const MSG_CONFIRM_SUPPR_PARENT     = 'Voulez-vous vraiment <strong>supprimer</strong> le Parent <strong>%s</strong>.';
  const MSG_CONFIRM_SUPPR_PARENT_DELEGUES    = 'Voulez-vous vraiment <strong>supprimer</strong> les Parents Délégués <strong>%s</strong>.';
  const MSG_CONFIRM_SUPPR_PARENT_DELEGUE     = 'Voulez-vous vraiment <strong>supprimer</strong> le Parent Délégué <strong>%s</strong>.';
  const MSG_CONFIRM_SUPPR_ELEVES    = 'Voulez-vous vraiment <strong>supprimer</strong> les Elèves <strong>%s</strong>.';
  const MSG_CONFIRM_SUPPR_ELEVE     = 'Voulez-vous vraiment <strong>supprimer</strong> l\'Elève <strong>%s</strong>.';
  const MSG_ERREUR_CONTROL_ENTETE    = 'La première ligne ne correspond pas aux champs attendus : <strong>%s</strong>.';
  const MSG_ERREUR_CONTROL_EXISTENCE = 'Champ obligatoire non saisi. ';
  const MSG_ERREUR_CONTROL_UNICITE   = 'Champ qui ne respecte pas le critère d\'unicité. ';
  const MSG_ERREUR_CONTROL_INEXISTENCE = 'Une donnée utilisée (%s) n\'existe pas en base. ';
  const MSG_ERREUR_CONTROL_ID        = 'Identifiant non valide. ';
  const MSG_ERREUR_CONTROL_FORMAT    = 'Format attendu (<strong>%s</strong>) non respecté. ';
  const MSG_SUCCESS_DELETE           = 'Suppression réussie.';
  const MSG_SUCCESS_PARTIEL_IMPORT   = 'L\'importation a été partiellement effectuée.';
  const MSG_SUCCESS_CREATE           = 'Création réussie.';
  const MSG_SUCCESS_UPDATE           = 'Mise à jour réussie. ';
  const MSG_SUCCESS_IMPORT           = 'L\'importation des données s\'est correctement déroulée.';
  const MSG_SUCCESS_EXPORT           = 'Exportation réussie. Le fichier peut être téléchargé <a href="%s">ici</a>.';

  /////////////////////////////////////////////////
  // Notifications
  const NOTIF_DANGER           = 'danger';
  const NOTIF_INFO             = 'info';
  const NOTIF_IS_INVALID       = 'is-invalid';
  const NOTIF_SUCCESS          = 'success';
  const NOTIF_WARNING          = 'warning';

  /////////////////////////////////////////////////
  // Allowed Pages :
  const PAGE_ADMINISTRATION    = 'administration';
  const PAGE_ANNEE_SCOLAIRE    = 'annee-scolaire';
  const PAGE_COMPO_DIVISION    = 'compodivision';
  const PAGE_COMPTE_RENDU      = 'compte-rendu';
  const PAGE_CONFIGURATION     = 'configuration';
  const PAGE_DIVISION          = 'division';
  const PAGE_ELEVE             = 'eleve';
  const PAGE_ENSEIGNANT        = 'enseignant';
  const PAGE_MATIERE           = 'matiere';
  const PAGE_PARENT            = 'parent';
  const PAGE_PARENT_DELEGUE    = 'parent-delegue';
  const PAGE_QUESTIONNAIRE     = 'questionnaire';

  /////////////////////////////////////////////////
  // Statuts :
  const STATUS_ARCHIVED        = 'archived';
  const STATUS_FUTURE          = 'future';
  const STATUS_PUBLISHED       = 'published';

  /////////////////////////////////////////////////
  // Tags
  const TAG_A                  = 'a';
  const TAG_DIV                = 'div';
  const TAG_INPUT              = 'input';
  const TAG_OPTION             = 'option';
  const TAG_SELECT             = 'select';
  const TAG_SPAN               = 'span';
  const TAG_STRONG             = 'strong';
  const TAG_TD                 = 'td';
  const TAG_TEXTAREA           = 'textarea';
  const TAG_TR                 = 'tr';

  const TYPE_DIVISION          = 'division';
  const TYPE_COMPODIVISION     = 'ClasseScolaire';

  /////////////////////////////////////////////////
  // Wordpress
  const WP_CURPAGE             = 'cur_page';
  const WP_ORDER               = 'order';
  const WP_ORDERBY             = 'orderby';

  /////////////////////////////////////////////////
  // Divers
  const EOL                    = "\r\n";
  const JOKER_SEARCH           = '%';
  const ORDER_ASC              = 'ASC';
  const ORDER_DESC             = 'DESC';
  const SEP                    = ';';

}
