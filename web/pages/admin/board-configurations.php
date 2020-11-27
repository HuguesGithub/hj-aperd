<div class="wrap">
  <h1 class="wp-heading-inline">Configurations</h1>
  <hr class="wp-header-end">

  <ul class="nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link active" href="#tabAdministration">Administratif</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#tabAnneeScolaire">Année Scolaire</a>
    </li>
  </ul>

  <div class="row" id="tabAdministration">
    <div class="col-8">
      <div class="card-body">
        <form action="#" method="post" id="post-filters">
          <table class="table table-striped table-bordered table-hover table-sm">
            <thead>
              <tr>
                <td id="cb" class="manage-column column-cb check-column"><input id="cb-select-all-1" type="checkbox"></td>
                <th scope="col" id="nomTitulaire" class="manage-column">Nom Titulaire</th>
                <th scope="col" id="labelPoste" class="manage-column">Libellé du poste</th>
              </tr>
            </thead>
            <tbody id="the-list">%1$s</tbody>
            <tfoot>
              <tr>
                <td class="manage-column column-cb check-column"><input id="cb-select-all-2" type="checkbox"></td>
                <th scope="col" class="manage-column">Nom Titulaire</th>
                <th scope="col" class="manage-column">Libellé du poste</th>
              </tr>
            </tfoot>
          </table>
        </form>
      </div>
    </div>

    <div class="col-4">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">%2$s</h4>
          <form action="#" method="post" id="post-add">
            <div class="form-group">
              <label for="nomTitulaire">Nom Titulaire</label>
              <input id="nomTitulaire" type="text" class="form-control" placeholder="Nom Titulaire" value="%3$s" name="nomTitulaire">
            </div>
            <div class="form-group">
              <label for="labelPoste">Libellé du poste</label>
              <input id="labelPoste" type="text" class="form-control" placeholder="Libellé du poste" value="%4$s" name="labelPoste">
            </div>
            <br>
            <div class="form-row">
              <input type="hidden" name="id" value="%5$s"/>
              <input type="hidden" name="type" value="Administration"/>
              <input type="submit" name="postAction" value="%2$s" class="btn btn-info"/>
              <a href="%6$s" class="btn btn-outline-default">Annuler</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="row" id="tabAnneeScolaire">
    <div class="col-8">
      <div class="card-body">
        <form action="#" method="post" id="post-filters">
          <table class="table table-striped table-bordered table-hover table-sm">
            <thead>
              <tr>
                <td id="cb" class="manage-column column-cb check-column"><input id="cb-select-all-1" type="checkbox"></td>
                <th scope="col" id="anneeScolaire" class="manage-column">Année Scolaire</th>
              </tr>
            </thead>
            <tbody id="the-list">%8$s</tbody>
            <tfoot>
              <tr>
                <td class="manage-column column-cb check-column"><input id="cb-select-all-2" type="checkbox"></td>
                <th scope="col" class="manage-column">Année Scolaire</th>
              </tr>
            </tfoot>
          </table>
        </form>
      </div>
    </div>

    <div class="col-4">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">%9$s</h4>
          <form action="#" method="post" id="post-add">
            <div class="form-group">
              <label for="anneeScolaire">Libellé de l'année scolaire</label>
              <input id="anneeScolaire" type="text" class="form-control" placeholder="Année Scolaire" value="%10$s" name="anneeScolaire">
            </div>
            <br>
            <div class="form-row">
              <input type="hidden" name="id" value="%11$s"/>
              <input type="hidden" name="type" value="AnneeScolaire"/>
              <input type="submit" name="postAction" value="%9$s" class="btn btn-info"/>
              <a href="%12$s" class="btn btn-outline-default">Annuler</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

</div>
<script>
  var defaultTab = '#%7$s';
</script>
