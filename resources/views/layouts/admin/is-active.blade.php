<?php
if (!$record->id){
    $record->is_active = IS_ACTIVE;
}
?>

<div class="form-group margin-b-5 margin-t-5">
    <div>
        <label>{{__a('is_active')}}</label>
    </div>
    <label for="is_active-1" class="mr-2">
        <input type="radio" class="square-blue" name="is_active" id="is_active-1"
               value="1" {{ old('is_active', $record->is_active) == 1 ? 'checked' : '' }}>
        {{__a('is_active_on')}}
    </label>
    <label for="is_active-0">
        <input type="radio" class="square-blue" name="is_active" id="is_active-0"
               value="0" {{ old('is_active', $record->is_active) == 0 ? 'checked' : '' }}>
        {{__a('is_active_off')}}
    </label>
</div>
