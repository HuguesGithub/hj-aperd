        <div class="row">
          <div class="col-md">
            <div class="form-floating mb-3">
              <input id="nomEleve" type="text" class="form-control form-control-sm required" placeholder="Nom Elève" value="%1$s" name="nomEleve" required>
              <label for="nomEleve">Nom Elève</label>
            </div>
          </div>
          <div class="col-md">
            <div class="form-floating mb-3">
              <input id="prenomEleve" type="text" class="form-control form-control-sm" placeholder="Prénom Elève" value="%2$s" name="prenomEleve">
              <label for="prenomEleve">Prénom Elève</label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md">
            <div class="form-floating mb-3">
              %3$s
              <label for="divisionId">Division Elève</label>
            </div>
          </div>
          <div class="col-md">
            <div class="input-group input-group-lg mb-3">
              <span class="input-group-text label-checkbox">Délégué ?</span>
              <div class="input-group-text">
                <input id="delegue" type="checkbox" class="form-check-input" value="Oui" name="delegue"%4$s style="position: initial;">
              </div>
            </div>
          </div>
        </div>
