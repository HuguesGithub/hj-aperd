<section id="page-compte-rendus" class="container">
  <section id="notifications" class="row">%7$s</section>

  <section id="searchCr" class="row">
    <fieldset>
      <legend>Saisissez l'identifiant de votre Compte-Rendu</legend>
      <form method="post" action="#" id="lookForCr">
        <div class="input-group input-group-sm mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text">Identifiant</span>
          </div>
          <input type="text" class="form-control" aria-label="Identifiant" name="crKey">
          <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="submit" name="search">Rechercher</button>
          </div>
        </div>
      </form>
    </fieldset>
  </section>

  <section id="formCr" class="row">
    <fieldset>
      <legend>Éditez votre Compte-Rendu</legend>
      <form method="post" action="#" id="editCr" class="md-form">
        <ul class="stepper parallel">
          <li class="step active">
            <div class="step-title waves-effect waves-dark">Informations diverses</div>
            <div class="step-new-content">
              <div class="form-row">
                <!-- Année Scolaire, Trimestre, Classe et Nombre d'élèves -->
                <div class="form-group col-md-3">
                  <label for="anneeScolaireId">Année Scolaire</label>%1$s
                </div>
                <div class="form-group col-md-3">
                  <label for="trimestre">Trimestre</label>%6$s
                </div>
                <div class="form-group col-md-3">
                  <label for="classeId">Classe</label>%2$s
                </div>
                <div class="form-group col-md-3">
                  <label for="nbEleves">Nombre d'élèves</label>%8$s
                </div>
                <!-- Fin Année Scolaire, Trimestre, Classe et Nombre d'élèves -->
                <!-- Date, Présidence et Professeur Principal du conseil de classe -->
                <div class="form-group col-md-4">
                  <label for="dateConseil">Date du Conseil</label>%9$s
                </div>
                <div class="form-group col-md-4">
                  <label for="administrationId">Présidence</label>%3$s
                </div>
                <div class="form-group col-md-4">
                  <label for="enseignantId">Professeur Principal</label>%4$s
                </div>
                <!-- Fin Date, Présidence et Professeur Principal du conseil de classe -->
                <!-- Parents & Elèves Délégués -->
                <div class="form-group col-md-6">
                  <label for="parent1">Parents Délégués</label>%10$s
                </div>
                <div class="form-group col-md-6">
                  <label for="parent2">&nbsp;</label>%11$s
                </div>
                <div class="form-group col-md-6">
                  <label for="eleve1">Élèves Délégués</label>%12$s
                </div>
                <div class="form-group col-md-6">
                  <label for="eleve2">&nbsp;</label>%13$s
                </div>
                <!-- Fin Parents & Elèves Délégués -->
              </div>
            </div>
          </li>
          <li class="step">
            <div class="step-title waves-effect waves-dark">Observations</div>
            <div class="step-new-content">
              <div id="divMatieres" class="form-row">
                <h3>Bilan de la classe</h3>
                <div class="form-group col-md-12">%14$s
                  <label for="bilanProfPrincipal">Bilan du professeur principal</label>
                </div>
                <h3>Bilan par matière</h3>
                <!-- Row Matière 01 -->
                %5$s
                <!-- Fin Matière 01 -->
              </div>
            </div>
          </li>
          <li class="step">
            <div class="step-title waves-effect waves-dark">Bilans</div>
            <div class="step-new-content">
              <!-- Compte-rendus -->
              <div class="form-row">
                <div class="form-group col-md-12">%15$s
                  <label for="bilanEleves">Bilan des représentants des élèves</label>
                </div>
                <div class="form-group col-md-12">%16$s
                  <label for="bilanParents">Bilan des représentants des parents</label>
                </div>
              </div>
              <!-- Fin Compte-rendus -->
            </div>
          </li>
          <li class="step">
            <div class="step-title waves-effect waves-dark">Attributions & Mises en garde</div>
            <div class="step-new-content">
              <!-- Attributions & Mises en garde -->
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="nbEncouragements">Encouragements</label>%17$s
                  <div class="valid-feedback">Looks good!</div>
                </div>
                <div class="form-group col-md-4">
                  <label for="nbCompliments">Compliments</label>%18$s
                </div>
                <div class="form-group col-md-4">
                  <label for="nbFelicitations">Félicitations</label>%19$s
                </div>
                <div class="form-group col-md-4">
                  <label for="nbMgComportement"><abbr title="Mise en garde Comportement">MGC</abbr></label>%20$s
                </div>
                <div class="form-group col-md-4">
                  <label for="nbMgTravail"><abbr title="Mise en garde Travail">MGT</abbr></label>%21$s
                </div>
                <div class="form-group col-md-4">
                  <label for="nbMgComportementTravail"><abbr title="Mise en garde Comportement &amp; Travail">MGCT</abbr></label>%22$s
                </div>
              </div>
              <!-- Fin Attributions & Mises en garde -->
            </div>
          </li>
          <li class="step">
            <div class="step-title waves-effect waves-dark">Rédactions</div>
            <div class="step-new-content">
              <!-- Rédaction -->
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="dateRedaction">Rédigé le</label>%23$s
                </div>
                <div class="form-group col-md-4">
                  <label for="auteurRedaction">Rédigé par</label>%24$s
                </div>
                <div class="form-group col-md-4">
                  <label for="mailContact">Mail de contact</label>%25$s
                </div>
              </div>
              <!-- Fin Rédaction -->
            </div>
          </li>
          <li class="step">
            <div class="step-title waves-effect waves-dark">Validation</div>
            <div class="step-new-content">
              <div class="form-row">
                <button class="btn btn-primary btn-sm" type="submit" name="save">Envoyer</button>
              </div>
            </div>
          </li>
        </ul>
      </form>
    </fieldset>
  </section>
</section>
