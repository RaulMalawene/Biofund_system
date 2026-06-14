@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === config('app.name'))
<img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('images/logotipoBiofund.jpeg'))) }}" class="logo" alt="BIOFUND">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
