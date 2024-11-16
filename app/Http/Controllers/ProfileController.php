<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Country;
use App\Enums\AddressType;
use Illuminate\Http\Request;
use App\Models\CustomerAddress;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        /** @var \App\Models\Customer $customer */
        $customer = $user->customer;
        $shippingAddress = $customer->shippingAddress ?? new CustomerAddress(['type' => AddressType::Shipping]);
        $billingAddress = $customer->billingAddress ?? new CustomerAddress(['type' => AddressType::Billing]);
        $countries = Country::query()->orderBy('name')->get();
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'shippingAddress' => $shippingAddress,
            'billingAddress' => $billingAddress,
            'customer' => $customer,
            'countries' => $countries,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileRequest $request)
    {
        try {
            $customerData = $request->validated();

            $shippingData = $customerData['shipping'];
            $billingData = $customerData['billing'];

            $user = $request->user();
            $customer = $user->customer;

            // Update main customer data
            $customer->update($customerData);

            // Update or create shipping address
            if ($customer->shippingAddress) {
                $customer->shippingAddress->update($shippingData);
            } else {
                $shippingData['customer_id'] = $customer->user_id;
                $shippingData['type'] = AddressType::Shipping->value;
                CustomerAddress::create($shippingData);
            }

            // Update or create billing address
            if ($customer->billingAddress) {
                $customer->billingAddress->update($billingData);
            } else {
                $billingData['customer_id'] = $customer->user_id;
                $billingData['type'] = AddressType::Billing->value;
                CustomerAddress::create($billingData);
            }

            return redirect()->back()->with('success', 'Profile was updated successfully.');
        } catch (ValidationException $e) {
            // Return with errors and retain input
            return back()->withErrors($e->errors())->withInput();
        }
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
