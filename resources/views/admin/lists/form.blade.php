<div class="form-group @if($errors->has('name')) has-error @endif">
    <label class="control-label" for="nameField">Naam verzendlijst</label>
    <input type="text"
           name="name"
           value="{{ old('name', $list->name ?? '') }}"
           class="form-control"
           id="nameField">
    @if($errors->has('name'))
        <span class="help-block">{{ $errors->first('name') }}</span>
    @endif
</div>
<div class="form-group @if($errors->has('description')) has-error @endif">
    <label class="control-label" for="descriptionField">Beschrijving (optioneel)</label>
    <input type="text"
           name="description"
           value="{{ old('description', $list->description ?? '') }}"
           class="form-control"
           id="descriptionField">
    @if($errors->has('description'))
        <span class="help-block">{{ $errors->first('description') }}</span>
    @endif
</div>