      <div class="card border-danger">
        <form action="#" method="post" id="post-delete">
          <div class="card-header bg-danger text-white"><strong>Suppression</strong></div>
          <div class="card-body text-danger">
            <div class="form-group">%1$s</div>
          </div>
          <div class="card-footer">
            <div class="btn-group btn-group-toggle">
              <input type="submit" name="submit" value="Oui" class="btn btn-danger btn-sm"/>
              <input type="hidden" name="id" value="%2$s"/>
              <input type="hidden" name="postAction" value="Suppression"/>
              <a href="%3$s" class="btn btn-outline-dark btn-sm">Non</a>
            </div>
          </div>
        </form>
      </div>
