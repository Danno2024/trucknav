<x-mail::message>
# Thanks for keeping drivers safe!

Your hazard report has been recorded:

**Location:** {{ $restriction->address }}
**Type:** {{ ucfirst(str_replace('_', ' ', $restriction->restriction_type)) }}
**Severity:** {{ ucfirst($restriction->severity) }}

Other drivers will now be warned about this hazard. Reports like yours are what make {{ config('app.name') }} work.

Thanks,<br>
The {{ config('app.name') }} Team
</x-mail::message>
