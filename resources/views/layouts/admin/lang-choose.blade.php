<?php
if (!$record->id){
    $record->lang = DEFAULT_LANG;
}
?>

<div class="form-group margin-b-5 margin-t-5">
    <div>
        <label>{{__a('lang')}}</label>
    </div>
    <label for="lang-1" class="mr-2">
        <input type="radio" class="square-blue" name="lang" id="lang-1"
               value="vi" {{ old('lang', $record->lang) == DEFAULT_LANG ? 'checked' : '' }}>
        VI
    </label>
    <label for="lang-0">
        <input type="radio" class="square-blue" name="lang" id="lang-0"
               value="en" {{ old('lang', $record->lang) != DEFAULT_LANG ? 'checked' : '' }}>
        EN
    </label>
</div>
