<?php

namespace App\Http\Controllers\Api;

use App\Models\Country;
use App\Enums\AddressType;
use App\Models\Api\Customer;
use App\Models\CustomerAddress;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\CustomerRequest;
use Illuminate\Database\QueryException;
use App\Http\Resources\CustomerResource;
use PhpParser\Node\Expr\AssignOp\Concat;
use App\Http\Resources\CustomerListResource;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('search', '');
        $perPage = request('per_page', 10);
        $sortField = request('sort_field', 'updated_at');
        $sortDirection = request('sort_direction', 'desc');

        // Determine if sortField belongs to customers or users
        $customerFields = ['updated_at', 'first_name', 'last_name', 'status', 'phone'];
        $userFields = ['email'];

        $query = Customer::query()
            ->join('users', 'customers.user_id', '=', 'users.id')
            ->select('customers.*', 'users.email'); // Select necessary columns

        // Adjust the sorting logic based on the table
        if (in_array($sortField, $customerFields)) {
            $query->orderBy("customers.$sortField", $sortDirection);
        } elseif (in_array($sortField, $userFields)) {
            $query->orderBy("users.$sortField", $sortDirection);
        } else {
            // Default sorting if the field doesn't match any known column
            $query->orderBy("customers.updated_at", $sortDirection);
        }

        // Add search filtering
        if ($search) {
            $query->where('customers.user_id', 'like', "%{$search}%")
                ->orWhere(DB::raw("CONCAT(customers.first_name, ' ', customers.last_name)"), 'like', "%{$search}%")
                ->orWhere('users.email', 'like', "%{$search}%");
        }

        return CustomerListResource::collection($query->paginate($perPage));
    }


    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        $countries = Country::all();
        return response()->json([
            'customer' => new CustomerResource($customer),
            'countries' => $countries,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerRequest $request, Customer $customer)
    {
        try {
            $customerData = $request->validated();

            $customerData['updated_by'] = $request->user()->id;
            $shippingData = $customerData['shipping'];
            $billingData = $customerData['billing'];

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

            return response()->json([
                'data' => new CustomerResource($customer),
                'message' => 'Profile was updated successfully.',
            ]);
        } catch (QueryException $e) {
            // Handle any database errors
            return response()->json([
                'message' => 'Database error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return response()->json(['message' => 'Customer deleted successfully']);
    }
}
