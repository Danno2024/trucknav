<x-mail::message>
# Route saved!

Your route **{{ $route->name }}** has been saved to your account.

**From:** {{ $route->origin_address }}
**To:** {{ $route->destination_address }}
@if($route->total_distance_km)
**Distance:** {{ number_format($route->total_distance_km, 1) }} km
@endif

<x-mail::button :url="url('/planner?route=' . $route->id)">
View Route
</x-mail::button>

Thanks for using {{ config('app.name') }} — drive safe!
</x-mail::message>
