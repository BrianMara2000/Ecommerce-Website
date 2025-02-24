<?php

namespace App\Http\Controllers\Api;

use Exception;
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

        $customerFields = ['created_at', 'status', 'phone', 'user_id'];
        $userFields = ['email', 'name'];

        // Use eager loading to prevent multiple queries
        $query = Customer::with(['user:id,email,name'])
            ->select('customers.*');

        // Sort conditionally based on the column
        if (in_array($sortField, $customerFields)) {
            $query->orderBy("customers.$sortField", $sortDirection);
        } elseif (in_array($sortField, $userFields)) {
            $query->join('users', 'customers.user_id', '=', 'users.id')
                ->orderBy("users.$sortField", $sortDirection);
        } else {
            $query->orderBy("customers.created_at", $sortDirection);
        }

        // Optimized search query
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('customers.user_id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($query) use ($search) {
                        $query->where('email', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$search}%");
                    });
            });
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
        DB::beginTransaction();
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

            DB::commit();

            return response()->json([
                'data' => new CustomerResource($customer),
                'message' => 'Profile was updated successfully.',
            ]);
        } catch (QueryException $e) {
            // Handle any database errors
            DB::rollBack();
            return response()->json([
                'message' => 'Database error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        } catch (Exception $e) { // Handle unexpected errors
            DB::rollBack();
            return response()->json([
                'message' => 'An unexpected error occurred.',
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
