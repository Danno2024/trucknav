<x-mail::message>
# Thank you for your donation!

We've received your generous donation of **${{ number_format($donation->amount, 2) }} {{ $donation->currency }}**.

Your support keeps {{ config('app.name') }} free for every truck, bus and coach driver in Australia.

Thanks,<br>
The {{ config('app.name') }} Team
</x-mail::message>
