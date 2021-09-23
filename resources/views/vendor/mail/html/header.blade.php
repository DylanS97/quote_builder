<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Star Quotes')
<x-application-logo class="logo fill-current" />
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
