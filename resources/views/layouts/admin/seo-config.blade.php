<h4>SEO</h4>
<div class="form-group margin-b-5 margin-t-5{{ $errors->has('keywords') ? ' has-error' : '' }}">
    <label for="keywords">{{__a('keywords')}}</label>
    <textarea class="form-control" name="keywords" id="keywords"
              placeholder="Keywords">{{ old('keywords', $record->keywords) }}</textarea>

    @if ($errors->has('keywords'))
        <span class="help-block">
                    <strong>{{ $errors->first('keywords') }}</strong>
                </span>
    @endif
</div>
<div class="form-group margin-b-5 margin-t-5{{ $errors->has('meta_description') ? ' has-error' : '' }}">
    <label for="meta_description">{{__a('meta_description')}}</label>
    <textarea class="form-control" name="meta_description" id="meta_description"
              placeholder="Meta Description" rows="6">{{ old('meta_description', $record->meta_description) }}</textarea>

    @if ($errors->has('meta_description'))
        <span class="help-block">
                    <strong>{{ $errors->first('meta_description') }}</strong>
                </span>
    @endif
</div>
