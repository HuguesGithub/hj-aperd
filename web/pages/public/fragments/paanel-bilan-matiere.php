<div class="tab-pane fade'.($isFirstButton?' show active':'').'" id="v-pills-'.$matiereId.'" role="tabpanel" aria-labelledby="v-pills-'.$matiereId.'-tab" data-bilan-matiere-id="'.$bilanMatiereId.'">
  <div class="form-row">
    <input type="hidden" name="matiereIds[]" value="'.$matiereId.'"/>
    <div class="form-group col-md-6">
      <label for="enseignant-'.$matiereId.'">Enseignant</label>'.$EnseignantBean->getSelect($args)
    </div>
    <div class="form-group col-md-4">
      <label for="statut-'.$matiereId.'">Statut</label>'.$this->getBalise(self::TAG_SELECT, $optionsSelectStatus, $args)
    </div>
    <div class="form-group col-md-2">
      <label for="moyenne-'.$matiereId.'">Moyenne</label>'.$this->getBalise(self::TAG_INPUT, '', $args)
    </div>
    <div class="form-group col-md-12">
      $this->getBalise(self::TAG_TEXTAREA, $observations, $attributes);
      <label class="'.($observations!=''?'active':'').'" for="observation-'.$matiereId.'">Observations</label>
    </div>
  </div>
</div>
