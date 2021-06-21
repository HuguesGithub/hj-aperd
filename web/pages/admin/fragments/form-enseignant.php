        <div class="row">
          <div class="col-md">
            <div class="form-floating mb-3">
              <input id="genre" type="text" class="form-control" placeholder="Genre" value="%1$s" name="genre">
              <label for="genre">Genre</label>
            </div>
          </div>
          <div class="col-md">
            <div class="form-floating mb-3">
              <input id="prenomEnseignant" type="text" class="form-control" placeholder="Prénom Enseignant" value="%3$s" name="prenomEnseignant">
              <label for="prenomEnseignant">Prénom Enseignant</label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md">
            <div class="form-floating mb-3">
              <input id="nomEnseignant" type="text" class="form-control required" placeholder="Nom Enseignant" value="%2$s" name="nomEnseignant" required>
              <label for="nomEnseignant">Nom Enseignant</label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md">
            <label for="matiereId">Matière(s)</label>
            %4$s
          </div>
        </div>

        <hr>

        <div data-toggle="collapse" data-target="#profPrincipalBlock" style="margin-top:-20px; text-align:right;">
          <span class="badge badge-dark" style="cursor: pointer;"><span class="oi oi-caret-bottom" title="Professeur Principal"></span></span>
        </div>
        <div id="profPrincipalBlock" class="form-group row collapse" style="box-shadow: inset 4px 0 0 0 #72aee6; padding-left: 10px;">
          <div class="col-md">
            <div class="form-floating">
              %5$s
              <label for="divisionId">Division</label>
            </div>
          </div>
          <div class="col-md">
            <div class="form-floating">
              %6$s
              <label for="anneeScolaireId">Année Scolaire</label>
            </div>
          </div>
        </div>
