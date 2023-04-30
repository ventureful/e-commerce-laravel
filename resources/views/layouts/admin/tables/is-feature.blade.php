<?php
$featureLink = route($resourceRoutesAlias . '.feature', $record->id);
?>
<td class="text-center">
    @if ($record->is_feature)
        <a href="{{$featureLink}}" class="label label-info">{{__a('is_on')}}</a>
    @else
        <a href="{{$featureLink}}" class="label label-warning">{{__a('is_off')}}</a>
    @endif
</td>
