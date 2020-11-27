[Administration]
select="SELECT id, labelPoste, nomTitulaire "
from="FROM wp_14_aperd_administration "
where="WHERE labelPoste LIKE '%s' AND nomTitulaire LIKE '%s' "
insert="INSERT INTO wp_14_aperd_administration (labelPoste, nomTitulaire) VALUES ('%s', '%s');"
update="UPDATE wp_14_aperd_administration SET labelPoste='%s', nomTitulaire='%s' "

[AnneeScolaire]
select="SELECT id, anneeScolaire "
from="FROM wp_14_aperd_annee_scolaire "
where="WHERE anneeScolaire LIKE '%s' "
insert="INSERT INTO wp_14_aperd_annee_scolaire (anneeScolaire) VALUES ('%s');"
update="UPDATE wp_14_aperd_annee_scolaire SET anneeScolaire='%s' "

[BilanMatiere]
select="SELECT id, compteRenduId, matiereId, enseignantId, status, observations "
from="FROM wp_14_aperd_bilan_matiere "
where="WHERE compteRenduId LIKE '%s' AND matiereId LIKE '%s' "
insert="INSERT INTO wp_14_aperd_bilan_matiere (compteRenduId, matiereId, enseignantId, status, observations) VALUES ('%s', '%s', '%s', '%s', '%s');"
update="UPDATE wp_14_aperd_bilan_matiere SET compteRenduId='%s', matiereId='%s', enseignantId='%s', status='%s', observations='%s' "

[ClasseScolaire]
select="SELECT id, labelClasse "
from="FROM wp_14_aperd_classe "
where="WHERE labelClasse LIKE '%s' "
insert="INSERT INTO wp_14_aperd_classe (labelClasse) VALUES ('%s');"
update="UPDATE wp_14_aperd_classe SET labelClasse='%s' "

[CompoClasse]
select="SELECT id, anneeScolaireId, classeId, matiereId, enseignantId "
from="FROM wp_14_aperd_compo_classe "
where="WHERE anneeScolaireId LIKE '%s' AND classeId LIKE '%s' AND matiereId LIKE '%s' AND enseignantId LIKE '%s' "
insert="INSERT INTO wp_14_aperd_compo_classe (anneeScolaireId, classeId, matiereId, enseignantId) VALUES ('%s', '%s', '%s', '%s');"
update="UPDATE wp_14_aperd_compo_classe SET anneeScolaireId='%s', classeId='%s', matiereId='%s', enseignantId='%s' "

[CompteRendu]
select="SELECT id, crKey, anneeScolaireId, trimestre, classeId, nbEleves, dateConseil, administrationId, enseignantId, parent1, parent2, enfant1, enfant2, bilanProfPrincipal, bilanEleves, bilanParents, nbEncouragements, nbCompliments, nbFelicitations, nbMgComportement, nbMgTravail, nbMgComportementTravail, dateRedaction, auteurRedaction, mailContact, status "
from="FROM wp_14_aperd_compte_rendu "
where="WHERE crKey LIKE '%s' AND anneeScolaireId LIKE '%s' AND trimestre LIKE '%s' AND classeId LIKE '%s' AND status LIKE '%s' "
insert="INSERT INTO wp_14_aperd_compte_rendu (crKey, anneeScolaireId, trimestre, classeId, nbEleves, dateConseil, administrationId, enseignantId, parent1, parent2, enfant1, enfant2, bilanProfPrincipal, bilanEleves, bilanParents, nbEncouragements, nbCompliments, nbFelicitations, nbMgComportement, nbMgTravail, nbMgComportementTravail, dateRedaction, auteurRedaction, mailContact, status) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');"
update="UPDATE wp_14_aperd_compte_rendu SET crKey='%s', anneeScolaireId='%s', trimestre='%s', classeId='%s', nbEleves='%s', dateConseil='%s', administrationId='%s', enseignantId='%s', parent1='%s', parent2='%s', enfant1='%s', enfant2='%s', bilanProfPrincipal='%s', bilanEleves='%s', bilanParents='%s', nbEncouragements='%s', nbCompliments='%s', nbFelicitations='%s', nbMgComportement='%s', nbMgTravail='%s', nbMgComportementTravail='%s', dateRedaction='%s', auteurRedaction='%s', mailContact='%s', status='%s' "

[Enseignant]
select="SELECT id, nomEnseignant, matiereId, status "
from="FROM wp_14_aperd_enseignant "
where="WHERE nomEnseignant LIKE '%s' AND matiereId LIKE '%s' AND status LIKE '%s' "
insert="INSERT INTO wp_14_aperd_enseignant (nomEnseignant, matiereId, status) VALUES ('%s', '%s', '%s');"
update="UPDATE wp_14_aperd_enseignant SET nomEnseignant='%s', matiereId='%s', status='%s' "

[Matiere]
select="SELECT id, labelMatiere "
from="FROM wp_14_aperd_matiere "
where="WHERE labelMatiere LIKE '%s' "
insert="INSERT INTO wp_14_aperd_matiere (labelMatiere) VALUES ('%s');"
update="UPDATE wp_14_aperd_matiere SET labelMatiere='%s' "

[ProfPrincipal]
select="SELECT id, anneeScolaireId, classeId, enseignantId "
from="FROM wp_14_aperd_prof_princ "
where="WHERE anneeScolaireId LIKE '%s' AND classeId LIKE '%s' AND enseignantId LIKE '%s' "
insert="INSERT INTO wp_14_aperd_prof_princ (anneeScolaireId, classeId, enseignantId) VALUES ('%s', '%s', '%s');"
update="UPDATE wp_14_aperd_prof_princ SET anneeScolaireId='%s', classeId='%s', enseignantId='%s' "




