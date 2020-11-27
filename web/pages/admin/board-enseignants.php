<div class="wrap">
  <h1 class="wp-heading-inline">Enseignants</h1>
  <hr class="wp-header-end">

  <ul class="nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link active" href="#tabEnseignants">Enseignants</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#tabProfsPrincipaux">Prof. Principaux</a>
    </li>
  </ul>

  <div class="row" id="tabEnseignants">
    <div class="col-8">
      <div class="card-body">
        <form action="#" method="post" id="post-filters" class="md-form">
          <div class="tablenav top" style="height: inherit;">
            <div class="actions" style="margin-bottom: 5px;">
              %8$s%9$s
              <input type="submit" name="filter_action" class="btn btn-info" value="Filtrer">
            </div>
          </div>
          <table class="table table-striped table-bordered table-hover table-sm">
            <thead>
              <tr>
                <td id="cb" class="manage-column column-cb check-column"><input id="cb-select-all-1" type="checkbox"></td>
                <th scope="col" id="nomEnseignant" class="manage-column">Nom Enseignant</th>
                <th scope="col" id="matiereId" class="manage-column">Matière</th>
                <th scope="col" id="status" class="manage-column">Statut</th>
              </tr>
            </thead>
            <tbody id="the-list">%1$s</tbody>
            <tfoot>
              <tr>
                <td class="manage-column column-cb check-column"><input id="cb-select-all-2" type="checkbox"></td>
                <th scope="col" class="manage-column">Nom Enseignant</th>
                <th scope="col" class="manage-column">Matière</th>
                <th scope="col" class="manage-column">Statut</th>
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
          <form action="#" method="post" id="post-add" class="md-form">
            <div class="form-group">
              <label for="nomEnseignant">Nom Enseignant</label>
              <input id="nomEnseignant" type="text" class="form-control" placeholder="Nom Enseignant" value="%3$s" name="nomEnseignant">
            </div>
            <div class="form-group">
              <label for="matiereId">Matière</label>%5$s
            </div>
            <div class="form-group">
              <label for="status">Statut</label>%7$s
            </div>
            <div class="form-row">
              <input type="hidden" name="id" value="%6$s" class="btn btn-info"/>
              <input type="hidden" name="type" value="Enseignants"/>
              <input type="submit" name="postAction" value="%2$s" class="btn btn-info"/>
              <a href="%4$s" class="btn btn-outline-default">Annuler</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="row" id="tabProfsPrincipaux">
    <div class="col-8">
      <div class="card-body">
        <form action="#" method="post" id="post-filters" class="md-form">
          <div class="tablenav top" style="height: inherit;">
            <div class="actions" style="margin-bottom: 5px;">
              %18$s
              <input type="submit" name="filter_action" class="btn btn-info" value="Filtrer">
            </div>
          </div>
          <table class="table table-striped table-bordered table-hover table-sm">
            <thead>
              <tr>
                <td id="cb" class="manage-column column-cb check-column"><input id="cb-select-all-1" type="checkbox"></td>
                <th scope="col" id="anneeScolaireId" class="manage-column">Année Scolaire</th>
                <th scope="col" id="classeId" class="manage-column">Classe</th>
                <th scope="col" id="enseignantId" class="manage-column">Enseignant</th>
              </tr>
            </thead>
            <tbody id="the-list">%11$s</tbody>
            <tfoot>
              <tr>
                <td class="manage-column column-cb check-column"><input id="cb-select-all-2" type="checkbox"></td>
                <th scope="col" class="manage-column">Année Scolaire</th>
                <th scope="col" class="manage-column">Classe</th>
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
          <h4 class="card-title">%12$s</h4>
          <form action="#" method="post" id="post-add" class="md-form">
            <div class="form-group">
              <label for="anneeScolaireId">Année Scolaire</label>%13$s
            </div>
            <div class="form-group">
              <label for="classeId">Classe</label>%14$s
            </div>
            <div class="form-group">
              <label for="enseignantId">Enseignant</label>%15$s
            </div>
            <div class="form-row">
              <input type="hidden" name="id" value="%16$s" class="btn btn-info"/>
              <input type="hidden" name="type" value="ProfsPrincipaux"/>
              <input type="submit" name="postAction" value="%12$s" class="btn btn-info"/>
              <a href="%17$s" class="btn btn-outline-default">Annuler</a>
            </div>
          </form>
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Année complète</h4>
          <form action="#" method="post" id="post-add" class="md-form">
            %19$s
            <div class="form-group">
              <textarea class="form-control" name="fileContent" rows="3"></textarea>
            </div>
            <div class="form-row">
              <input type="hidden" name="type" value="ProfsPrincipaux"/>
              <input type="submit" name="postAction" value="Upload" class="btn btn-info"/>
              <a href="%17$s" class="btn btn-outline-default">Annuler</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  var defaultTab = '#%10$s';
</script>
