<?php
$activeLink = route($resourceRoutesAlias . '.active', $record->id);
?>
<td class="text-center">
    @if ($record->is_active)
        <a href="{{$activeLink}}" class="label label-info">{{__a('is_on')}}</a>
    @else
        <a href="{{$activeLink}}" class="label label-warning">{{__a('is_off')}}</a>
    @endif
</td>
