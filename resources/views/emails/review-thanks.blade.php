<x-mail::message>
# Thanks for your feedback!

@if($review->rating)
You rated us **{{ $review->rating }} out of 5** — thank you!
@endif

Your feedback helps us improve {{ config('app.name') }} for drivers across Australia.

Thanks,<br>
The {{ config('app.name') }} Team
</x-mail::message>
