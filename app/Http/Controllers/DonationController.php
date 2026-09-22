<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonationController extends Controller
{
    public function index()
    {
        return view('donate');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1|max:10000',
        ]);

        $amount = number_format((float) $validated['amount'], 2, '.', '');
        $paypalBusiness = config('services.paypal.business_email', '');

        if (!$paypalBusiness) {
            return back()->withErrors(['amount' => 'Donations are not configured yet.']);
        }

        $donation = Donation::create([
            'user_id' => Auth::id(),
            'amount' => $amount,
            'currency' => 'AUD',
            'status' => 'pending',
        ]);

        $paypalUrl = 'https://www.paypal.com/donate';
        $params = http_build_query([
            'business' => $paypalBusiness,
            'amount' => $amount,
            'currency_code' => 'AUD',
            'item_name' => 'TruckRoute Donation',
            'custom' => $donation->id,
            'no_note' => 1,
            'bn' => 'PP-donateBF:btn_donate_LG.gif:NonHosted',
        ]);

        return redirect("{$paypalUrl}?{$params}");
    }

    public function paypalReturn(Request $request)
    {
        $donationId = $request->query('custom');
        if ($donationId) {
            Donation::where('id', $donationId)
                ->where('user_id', Auth::id())
                ->update(['status' => 'completed']);
        }

        return redirect()->route('donate')->with('success', 'Thank you for your generous donation!');
    }

    public function paypalCancel()
    {
        return redirect()->route('donate')->with('error', 'Donation was cancelled.');
    }
}
