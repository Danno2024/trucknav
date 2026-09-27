<x-mail::message>
# Welcome to TruckRoute, {{ $user->name }}!

Thanks for joining — your account is ready. Here's what you can do:

- **Plan routes** with your truck, bus or coach dimensions
- **Report hazards** like low bridges and weight limits
- **Save routes** and join the community forums

<x-mail::button :url="route('planner')">
Plan Your First Route
</x-mail::button>

Drive safe,<br>
{{ config('app.name') }}
</x-mail::message>
