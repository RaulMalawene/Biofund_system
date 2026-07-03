@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === config('app.name'))
<img src="{{ asset('images/logotipo.png') }}" class="logo" alt="BIOFUND">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
