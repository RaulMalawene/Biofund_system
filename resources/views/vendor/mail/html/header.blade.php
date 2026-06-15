@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === config('app.name'))
<img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logotipo.png'))) }}" class="logo" alt="BIOFUND">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
