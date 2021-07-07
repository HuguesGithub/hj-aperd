    <div id="schema-base" class="card-columns">

      <div id="compte_rendu" class="card bg-light">
        <div class="card-header"><strong>wp_14_aperd_compte_rendu</strong></div>
        <div class="card-body">
          <ul class="list-group list-group-condensed">
            <li class="list-group-item list-group-item-action d-flex justify-content-between" data-key="compte_rendu">id <span class="badge badge-info" title="Clef">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between" data-foreign-key="#anneeScolaire">anneeScolaireId <span class="badge badge-danger" title="required">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between" data-foreign-key="#division">divisionId <span class="badge badge-danger" title="required">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between" data-foreign-key="#administration">administrationId <span class="badge badge-danger" title="required">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between" data-foreign-key="#prof_princ">profPrincId <span class="badge badge-danger" title="required">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between" data-foreign-key="#eleve">delegueEleve1Id <span class="badge badge-danger" title="required">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between" data-foreign-key="#eleve">delegueEleve2Id <span class="badge badge-danger" title="required">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between" data-foreign-key="#parent-delegue">delegueParent1Id <span class="badge badge-danger" title="required">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between" data-foreign-key="#parent-delegue">delegueParent2Id <span class="badge badge-danger" title="required">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between text-success">crKey <span class="badge badge-danger" title="required">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between">trimestre <span class="badge badge-danger" title="required">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between">nbEleves <span class="badge badge-danger" title="required">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between text-success">dateConseil <span class="badge badge-danger" title="required">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between text-success">bilanProfPrincipal</li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between text-success">bilanEleves</li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between text-success">bilanParents</li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between">nbEncouragements</li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between">nbCompliments</li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between">nbFelicitations</li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between">nbMgComportement</li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between">nbMgTravail</li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between">nbMgComportementTravail</li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between text-success">dateRedaction <span class="badge badge-danger" title="required">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between text-success">auteurRedaction <span class="badge badge-danger" title="required">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between text-success">status</li>
          </ul>
        </div>
      </div>

      <div id="anneeScolaire" class="card bg-light">
        <div class="card-header"><strong><a href="http://aperd.jhugues.fr/wp-admin/admin.php?page=hj-aperd/admin_manage.php&onglet=annee-scolaire">wp_14_aperd_annee_scolaire</a></strong></div>
        <div class="card-body">
          <ul class="list-group list-group-condensed">
            <li class="list-group-item list-group-item-action d-flex justify-content-between" data-key="anneeScolaire">id <span class="badge badge-info" title="Clef">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between text-success">anneeScolaire <span class="badge badge-danger" title="required">&nbsp;</span></li>
          </ul>
        </div>
      </div>

      <div id="division" class="card bg-light">
        <div class="card-header"><strong><a href="http://aperd.jhugues.fr/wp-admin/admin.php?page=hj-aperd/admin_manage.php&onglet=division">wp_14_aperd_division</a></strong></div>
        <div class="card-body">
          <ul class="list-group list-group-condensed">
            <li class="list-group-item list-group-item-action d-flex justify-content-between" data-key="division">id <span class="badge badge-info" title="Clef">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between text-success">labelDivision <span class="badge badge-danger" title="required">&nbsp;</span></li>
          </ul>
        </div>
      </div>

      <div id="administration" class="card bg-light">
        <div class="card-header"><strong><a href="http://aperd.jhugues.fr/wp-admin/admin.php?page=hj-aperd/admin_manage.php&onglet=administration">wp_14_aperd_administration</a></strong></div>
        <div class="card-body">
          <ul class="list-group list-group-condensed">
            <li class="list-group-item list-group-item-action d-flex justify-content-between" data-key="administration">id <span class="badge badge-info" title="Clef">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between text-success">genre</li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between text-success">nomTitulaire <span class="badge badge-danger" title="required">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between text-success">labelPoste <span class="badge badge-danger" title="required">&nbsp;</span></li>
          </ul>
        </div>
      </div>

      <div id="prof_princ" class="card bg-light">
        <div class="card-header"><strong><a href="http://aperd.jhugues.fr/wp-admin/admin.php?page=hj-aperd/admin_manage.php&onglet=enseignant">wp_14_aperd_prof_princ</a></strong></div>
        <div class="card-body">
          <ul class="list-group list-group-condensed">
            <li class="list-group-item list-group-item-action d-flex justify-content-between" data-key="prof_princ">id <span class="badge badge-info" title="Clef">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between" data-foreign-key="#enseignant">enseignantId <span class="badge badge-danger" title="required">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between" data-foreign-key="#division">divisionId <span class="badge badge-danger" title="required">&nbsp;</span></li>
          </ul>
        </div>
      </div>

      <div id="eleve" class="card bg-light">
        <div class="card-header"><strong><a href="http://aperd.jhugues.fr/wp-admin/admin.php?page=hj-aperd/admin_manage.php&onglet=eleve">wp_14_aperd_eleve</a></strong></div>
        <div class="card-body">
          <ul class="list-group list-group-condensed">
            <li class="list-group-item list-group-item-action d-flex justify-content-between" data-key="eleve">id <span class="badge badge-info" title="Clef">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between text-success">nomEleve <span class="badge badge-danger" title="required">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between text-success">prenomEleve <span class="badge badge-danger" title="required">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between" data-foreign-key="#division">divisionId <span class="badge badge-danger" title="required">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between">delegue</li>
          </ul>
        </div>
      </div>

      <div id="parent-delegue" class="card bg-light">
        <div class="card-header"><strong><a href="http://aperd.jhugues.fr/wp-admin/admin.php?page=hj-aperd/admin_manage.php&onglet=parent-delegue">wp_14_aperd_parent_delegue</a></strong></div>
        <div class="card-body">
          <ul class="list-group list-group-condensed">
            <li class="list-group-item list-group-item-action d-flex justify-content-between" data-key="parent-delegue">id <span class="badge badge-info" title="Clef">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between" data-foreign-key="#parent">parentId <span class="badge badge-danger" title="required">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between" data-foreign-key="#division">divisionId <span class="badge badge-danger" title="required">&nbsp;</span></li>
          </ul>
        </div>
      </div>

      <div id="enseignant" class="card bg-light">
        <div class="card-header"><strong><a href="http://aperd.jhugues.fr/wp-admin/admin.php?page=hj-aperd/admin_manage.php&onglet=enseignant">wp_14_aperd_enseignant</a></strong></div>
        <div class="card-body">
          <ul class="list-group list-group-condensed">
            <li class="list-group-item list-group-item-action d-flex justify-content-between" data-key="enseignant">id <span class="badge badge-info" title="Clef">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between text-success">genre</li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between text-success">nomEnseignant <span class="badge badge-danger" title="required">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between text-success">prenomEnseignant</li>
          </ul>
        </div>
      </div>

      <div id="parent" class="card bg-light">
        <div class="card-header"><strong><a href="http://aperd.jhugues.fr/wp-admin/admin.php?page=hj-aperd/admin_manage.php&onglet=parent">wp_14_aperd_parent</a></strong></div>
        <div class="card-body">
          <ul class="list-group list-group-condensed">
            <li class="list-group-item list-group-item-action d-flex justify-content-between" data-key="parent">id <span class="badge badge-info" title="Clef">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between text-success">nomParent <span class="badge badge-danger" title="required">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between text-success">prenomParent <span class="badge badge-danger" title="required">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between text-success">mailParent <span class="badge badge-danger" title="required">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between">adherent</li>
          </ul>
        </div>
      </div>

      <div id="enseignant_matiere" class="card bg-light">
        <div class="card-header"><strong><a href="http://aperd.jhugues.fr/wp-admin/admin.php?page=hj-aperd/admin_manage.php&onglet=enseignant">wp_14_aperd_enseignant_matiere</a></strong></div>
        <div class="card-body">
          <ul class="list-group list-group-condensed">
            <li class="list-group-item list-group-item-action d-flex justify-content-between" data-key="enseignant_matiere">id <span class="badge badge-info" title="Clef">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between" data-foreign-key="#enseignant">enseignantId <span class="badge badge-danger" title="required">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between" data-foreign-key="#matiere">matiereId <span class="badge badge-danger" title="required">&nbsp;</span></li>
          </ul>
        </div>
      </div>

      <div id="bilan_matiere" class="card bg-light">
        <div class="card-header"><strong>wp_14_aperd_bilan_matiere</strong></div>
        <div class="card-body">
          <ul class="list-group list-group-condensed">
            <li class="list-group-item list-group-item-action d-flex justify-content-between" data-key="bilan_matiere">id <span class="badge badge-info" title="Clef">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between" data-foreign-key="#compte_rendu">compteRenduId <span class="badge badge-danger" title="required">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between" data-foreign-key="#enseignant_matiere">enseignantMatiereId <span class="badge badge-danger" title="required">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between text-success">status</li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between text-success">observations</li>
          </ul>
        </div>
      </div>

      <div id="matiere" class="card bg-light">
        <div class="card-header"><strong><a href="http://aperd.jhugues.fr/wp-admin/admin.php?page=hj-aperd/admin_manage.php&onglet=matiere">wp_14_aperd_matiere</a></strong></div>
        <div class="card-body">
          <ul class="list-group list-group-condensed">
            <li class="list-group-item list-group-item-action d-flex justify-content-between" data-key="matiere">id <span class="badge badge-info" title="Clef">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between text-success">labelMatiere <span class="badge badge-danger" title="required">&nbsp;</span></li>
          </ul>
        </div>
      </div>

      <div class="card bg-light">
        <div class="card-header"><strong><a href="http://aperd.jhugues.fr/wp-admin/admin.php?page=hj-aperd/admin_manage.php&onglet=matiere">wp_14_aperd_compo_division</a></strong></div>
        <div class="card-body">
          <ul class="list-group list-group-condensed">
            <li class="list-group-item list-group-item-action d-flex justify-content-between">id <span class="badge badge-info" title="Clef">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between" data-foreign-key="#division">divisionId <span class="badge badge-danger" title="required">&nbsp;</span></li>
            <li class="list-group-item list-group-item-action d-flex justify-content-between" data-foreign-key="#enseignant_matiere">enseignantMatiereId <span class="badge badge-danger" title="required">&nbsp;</span></li>
          </ul>
        </div>
      </div>

    </div>
<script>
  var defaultTab = '';
</script>
