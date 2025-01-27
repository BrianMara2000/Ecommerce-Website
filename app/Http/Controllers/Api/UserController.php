<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\User;
use App\Models\Customer;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use App\Http\Resources\UserListResource;

class UserController extends Controller
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

        $query = User::query()->join('customers', 'users.id', '=', 'customers.user_id')
            ->select('users.*', 'customers.phone');
        $query->orderBy($sortField, $sortDirection);
        if ($search) {
            $query->where('users.id', 'like', "%{$search}%")
                ->orWhere('users.name', 'like', "%{$search}%")
                ->orWhere('users.email', 'like', "%{$search}%")
                ->orWhere('customers.phone', 'like', "%{$search}%");
        }
        return UserListResource::collection($query->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $data = $request->validated();

        // Add additional fields to the data
        $data['is_admin'] = true;
        $data['email_verified_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;
        $data['password'] = Hash::make($data['password']);

        try {
            // Create the user
            $user = User::create($data);

            // Create the associated customer
            $names = explode(" ", $user->name);
            $customer = new Customer();
            $customer->user_id = $user->id;
            $customer->first_name = $names[0];
            $customer->last_name = $names[1] ?? ''; // Handle missing last name
            $customer->phone = $data['phone'] ?? null;
            $customer->save();

            // Return success response
            return response()->json([
                'data' => new UserResource($user->load('customer')),
                'message' => 'New admin created successfully',
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
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        $data = $request->validated();

        if (!empty($data['password'])) {

            $data['password'] = Hash::make($data['password']);
        }

        $data['updated_by'] = $request->user()->id;

        try {

            $user->update($data);

            $names = explode(" ", $user->name);
            $first_name = $names[0];
            $last_name = $names[1] ?? '';

            $user->customer()->update([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'phone' => $data['phone'] ?? $user->customer->phone,
            ]);

            return response()->json([
                'data' =>  new UserResource($user),
                'message' => 'User updated successfully'
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
    public function destroy(User $user, Customer $customer)
    {
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
