<div class="wrap">
  <h1 class="wp-heading-inline">Divisions</h1>
  <hr class="wp-header-end">

  <ul class="nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link" href="#tabDivision">Divisions</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#tabClasseScolaire">Classes Scolaires</a>
    </li>
  </ul>

  <div class="row" id="tabDivision">
    <div class="col-8">
      <div class="card-body">
        <form action="#" method="post" id="post-filters">
          <table class="table table-striped table-bordered table-hover table-sm" aria-describedby="Liste des divisions existantes">
            <thead>
              <tr>
                <td id="cb" class="manage-column column-cb check-column"><input id="cb-select-all-1" type="checkbox"></td>
                <th scope="col" id="labelDivision" class="manage-column">Libellé de la Division</th>
              </tr>
            </thead>
            <tbody id="the-list">%2$s</tbody>
            <tfoot>
              <tr>
                <td class="manage-column column-cb check-column"><input id="cb-select-all-2" type="checkbox"></td>
                <th scope="col" class="manage-column">Libellé de la Division</th>
              </tr>
            </tfoot>
          </table>
        </form>
      </div>
    </div>

    <div class="col-4">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">%3$s</h4>
          <form action="#" method="post" id="post-add">
            <div class="form-group">
              <label for="labelDivision">Label de la Division</label>
              <input id="labelDivision" type="text" class="form-control" placeholder="Label de la Division" value="%4$s" name="labelDivision">
            </div>
            <br>
            <div class="form-row">
              <input type="hidden" name="id" value="%5$s"/>
              <input type="hidden" name="type" value="Division"/>
              <input type="submit" name="postAction" value="%3$s" class="btn btn-info"/>
              <a href="%6$s" class="btn btn-outline-default">Annuler</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="row" id="tabClasseScolaire">
    <div class="col-8">
      <div class="card-body">
        <form action="#" method="post" id="post-filters">
          <div class="tablenav top" style="height: inherit;">
            <div class="actions" style="margin-bottom: 5px;">
              %10$s
              <input type="hidden" name="type" value="ClasseScolaire">
              <input type="submit" name="filter_action" class="btn btn-info" value="Filtrer">
            </div>
          </div>
          <table class="table table-striped table-bordered table-hover table-sm" aria-describedby="Répartition Matières / Enseignants par Divisions, par Années">
            <thead>
              <tr>
                <td id="cb" class="manage-column column-cb check-column"><input id="cb-select-all-1" type="checkbox"></td>
                <th scope="col" id="anneeScolaire" class="manage-column">Année Scolaire</th>
                <th scope="col" id="labelDivision" class="manage-column">Libellé de la Division</th>
                <th scope="col" id="labelMatiere" class="manage-column">Matière</th>
                <th scope="col" id="nomEnseignant" class="manage-column">Enseignant</th>
              </tr>
            </thead>
            <tbody id="the-list">%9$s</tbody>
            <tfoot>
              <tr>
                <td class="manage-column column-cb check-column"><input id="cb-select-all-2" type="checkbox"></td>
                <th scope="col" class="manage-column">Année Scolaire</th>
                <th scope="col" class="manage-column">Libellé de la Division</th>
                <th scope="col" class="manage-column">Matière</th>
                <th scope="col" class="manage-column">Enseignant</th>
              </tr>
            </tfoot>
          </table>
        </form>
      </div>
    </div>

    <div class="col-4">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">%16$s</h4>
          <form action="#" method="post" id="post-add">
            <div class="form-group">
              <label for="anneeScolaireId">Année Scolaire</label>%11$s
              <label for="divisionId">Division</label>%12$s
              <label for="matiereId">Matière</label>%13$s
              <label for="enseignantId">Enseignant</label>%14$s
            </div>
            <br>
            <div class="form-row">
              <input type="hidden" name="id" value="%15$s"/>
              <input type="hidden" name="type" value="ClasseScolaire"/>
              <input type="submit" name="postAction" value="%16$s" class="btn btn-info"/>
              <a href="%17$s" class="btn btn-outline-default">Annuler</a>
            </div>
          </form>
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Année complète</h4>
          <caption>Répartition Enseignants/Matières par Divisions et par Année scolaire</caption>
          <form action="#" method="post" id="post-add" class="md-form">
            %7$s
            <div class="form-group">
              <textarea class="form-control" name="fileContent" rows="3"></textarea>
            </div>
            <div class="form-row">
              <input type="hidden" name="type" value="ClasseScolaire"/>
              <input type="submit" name="postAction" value="Upload" class="btn btn-info"/>
              <a href="%8$s" class="btn btn-outline-default">Annuler</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

</div>
<script>
  var defaultTab = '#%1$s';
</script>
