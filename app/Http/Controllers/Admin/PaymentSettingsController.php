<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PaymentSettingsController extends Controller
{
    private const KEYS = [
        'payment_razorpay_enabled' => true,
        'payment_upi_enabled' => true,
        'payment_phonepe_enabled' => false,
        'payment_stripe_enabled' => false,
    ];

    public function index(): Response
    {
        $flags = [];
        foreach (self::KEYS as $key => $default) {
            $flags[$key] = (bool) Setting::getValue($key, $default);
        }

        return Inertia::render('Admin/Payments/Index', [
            'paymentFlags' => $flags,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'payment_razorpay_enabled' => 'required|boolean',
            'payment_upi_enabled' => 'required|boolean',
            'payment_phonepe_enabled' => 'required|boolean',
            'payment_stripe_enabled' => 'required|boolean',
        ]);

        foreach ($validated as $key => $val) {
            Setting::setValue($key, $val ? '1' : '0', 'boolean', 'payments', false);
        }

        return back()->with('success', 'Payment methods updated. Public donate page uses these flags.');
    }
}
