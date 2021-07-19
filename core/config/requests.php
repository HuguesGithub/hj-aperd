[Administration]
select="SELECT id, genre, nomTitulaire, labelPoste "
from="FROM wp_14_aperd_administration "
where="WHERE labelPoste LIKE '%s' AND nomTitulaire LIKE '%s' "
insert="INSERT INTO wp_14_aperd_administration (genre, nomTitulaire, labelPoste) VALUES ('%s', '%s', '%s');"
update="UPDATE wp_14_aperd_administration SET genre='%s', nomTitulaire='%s', labelPoste='%s' "

[Adulte]
select="SELECT id, nomParent, prenomParent, mailParent, adherent "
from="FROM wp_14_aperd_parent "
where="WHERE nomParent LIKE '%s' AND prenomParent LIKE '%s' AND adherent LIKE '%s' "
insert="INSERT INTO wp_14_aperd_parent (nomParent, prenomParent, mailParent, adherent) VALUES ('%s', '%s', '%s', '%s');"
update="UPDATE wp_14_aperd_parent SET nomParent='%s', prenomParent='%s', mailParent='%s', adherent='%s' "

[AnneeScolaire]
select="SELECT id, anneeScolaire "
from="FROM wp_14_aperd_annee_scolaire "
where="WHERE anneeScolaire LIKE '%s' "
insert="INSERT INTO wp_14_aperd_annee_scolaire (anneeScolaire) VALUES ('%s');"
update="UPDATE wp_14_aperd_annee_scolaire SET anneeScolaire='%s' "

[BilanMatiere]
select="SELECT id, compteRenduId, matiereId, status, moyenneDivision, observations "
from="FROM wp_14_aperd_bilan_matiere "
where="WHERE compteRenduId LIKE '%s' AND matiereId LIKE '%s' "
insert="INSERT INTO wp_14_aperd_bilan_matiere (compteRenduId, matiereId, status, moyenneDivision, observations) VALUES ('%s', '%s', '%s', '%s', '%s');"
update="UPDATE wp_14_aperd_bilan_matiere SET compteRenduId='%s', matiereId='%s', status='%s', moyenneDivision='%s', observations='%s' "

[Division]
select="SELECT id, labelDivision, crKey "
from="FROM wp_14_aperd_division "
where="WHERE labelDivision LIKE '%s' AND crKey LIKE '%s' "
insert="INSERT INTO wp_14_aperd_division (labelDivision, crKey) VALUES ('%s', '%s');"
update="UPDATE wp_14_aperd_division SET labelDivision='%s', crKey='%s' "

[CompoDivision]
select="SELECT id, divisionId, enseignantMatiereId "
from="FROM wp_14_aperd_compo_division "
where="WHERE divisionId LIKE '%s' "
insert="INSERT INTO wp_14_aperd_compo_division (divisionId, enseignantMatiereId) VALUES ('%s', '%s');"
update="UPDATE wp_14_aperd_compo_division SET divisionId='%s', enseignantMatiereId='%s' "

[CompteRendu]
select="SELECT id, trimestre, divisionId, nbEleves, dateConseil, administrationId, profPrincId, delegueEleve1Id, delegueEleve2Id, delegueParent1Id, delegueParent2Id, bilanProfPrincipal, bilanEleves, bilanParents, nbEncouragements, nbCompliments, nbFelicitations, nbMgComportement, nbMgTravail, nbMgComportementTravail, dateRedaction, auteurRedaction, status "
from="FROM wp_14_aperd_compte_rendu "
where="WHERE trimestre LIKE '%s' AND divisionId LIKE '%s' AND status LIKE '%s' "
insert="INSERT INTO wp_14_aperd_compte_rendu (trimestre, divisionId, nbEleves, dateConseil, administrationId, profPrincId, delegueEleve1Id, delegueEleve2Id, delegueParent1Id, delegueParent2Id, bilanProfPrincipal, bilanEleves, bilanParents, nbEncouragements, nbCompliments, nbFelicitations, nbMgComportement, nbMgTravail, nbMgComportementTravail, dateRedaction, auteurRedaction, status) VALUES ('%s',    '%s',       '%s',       '%s',       '%s',             '%s',         '%s',           '%s',           '%s',               '%s',             '%s',               '%s',       '%s',         '%s',             '%s',           '%s',           '%s',             '%s',         '%s',                     '%s',           '%s',           '%s');"
update="UPDATE wp_14_aperd_compte_rendu SET trimestre='%s', divisionId='%s', nbEleves='%s', dateConseil='%s', administrationId='%s', profPrincId='%s', delegueEleve1Id='%s', delegueEleve2Id='%s', delegueParent1Id='%s', delegueParent2Id='%s', bilanProfPrincipal='%s', bilanEleves='%s', bilanParents='%s', nbEncouragements='%s', nbCompliments='%s', nbFelicitations='%s', nbMgComportement='%s', nbMgTravail='%s', nbMgComportementTravail='%s', dateRedaction='%s', auteurRedaction='%s', status='%s' "

[Eleve]
select="SELECT id, nomEleve, prenomEleve, divisionId, delegue "
from="FROM wp_14_aperd_eleve "
where="WHERE nomEleve LIKE '%s' AND prenomEleve LIKE '%s' AND divisionId LIKE '%s' AND delegue LIKE '%s' "
whereOr="WHERE (nomEleve LIKE '%s' OR prenomEleve LIKE '%s') AND divisionId LIKE '%s' AND delegue LIKE '%s' "
insert="INSERT INTO wp_14_aperd_eleve (nomEleve, prenomEleve, divisionId, delegue) VALUES ('%s', '%s', '%s', '%s');"
update="UPDATE wp_14_aperd_eleve SET nomEleve='%s', prenomEleve='%s', divisionId='%s', delegue='%s' "

[Enseignant]
select="SELECT id, genre, nomEnseignant, prenomEnseignant "
from="FROM wp_14_aperd_enseignant "
where="WHERE nomEnseignant LIKE '%s' "
insert="INSERT INTO wp_14_aperd_enseignant (genre, nomEnseignant, prenomEnseignant) VALUES ('%s', '%s', '%s');"
update="UPDATE wp_14_aperd_enseignant SET genre='%s', nomEnseignant='%s', prenomEnseignant='%s' "

[EnseignantMatiere]
select="SELECT id, enseignantId, matiereId "
from="FROM wp_14_aperd_enseignant_matiere "
where="WHERE enseignantId LIKE '%s' AND matiereId LIKE '%s' "
insert="INSERT INTO wp_14_aperd_enseignant_matiere (enseignantId, matiereId) VALUES ('%s', '%s');"
update="UPDATE wp_14_aperd_enseignant_matiere SET enseignantId='%s', matiereId='%s' "

[Matiere]
select="SELECT id, labelMatiere "
from="FROM wp_14_aperd_matiere "
where="WHERE labelMatiere LIKE '%s' "
insert="INSERT INTO wp_14_aperd_matiere (labelMatiere) VALUES ('%s');"
update="UPDATE wp_14_aperd_matiere SET labelMatiere='%s' "

[ParentDelegue]
select="SELECT id, parentId, divisionId "
from="FROM wp_14_aperd_parent_delegue "
where="WHERE parentId LIKE '%s' AND divisionId LIKE '%s' "
insert="INSERT INTO wp_14_aperd_parent_delegue (parentId, divisionId) VALUES ('%s', '%s');"
update="UPDATE wp_14_aperd_parent_delegue SET parentId='%s', divisionId='%s' "

[ProfPrincipal]
select="SELECT id, divisionId, enseignantId "
from="FROM wp_14_aperd_prof_princ "
where="WHERE divisionId LIKE '%s' AND enseignantId LIKE '%s' "
insert="INSERT INTO wp_14_aperd_prof_princ (divisionId, enseignantId) VALUES ('%s', '%s');"
update="UPDATE wp_14_aperd_prof_princ SET divisionId='%s', enseignantId='%s' "

[Questionnaire]
select="SELECT configKey, configValue "
from="FROM wp_14_aperd_config_questionnaire "
where="WHERE configKey LIKE '%s' AND configValue LIKE '%s' "
insert="INSERT INTO wp_14_aperd_config_questionnaire (configKey, configValue) VALUES ('%s', '%s');"
update="UPDATE wp_14_aperd_config_questionnaire SET configKey='%s', configValue='%s' "



