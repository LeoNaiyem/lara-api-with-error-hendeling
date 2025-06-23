<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CustomerResource::collection(Customer::orderBy('id', 'desc')->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('customers', 'public');
            $data['photo'] = $path;
        }
        $customer = Customer::create($data);
        return new CustomerResource($customer);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return new CustomerResource($customer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerRequest $request, Customer $customer)
    {
        $data = $request->validated();
        // if ($request->hasFile('photo')) {
        //     if ($customer->photo && \Storage::disk('public')->exists($customer->photo)) {
        //         \Storage::disk('public')->delete($customer->photo);
        //     }
        //     $path = $request->file('photo')->store('customer', 'public');
        //     $data['photo'] = $path;
        // }
        if ($request->hasFile('photo')) {
            optional($customer->photo && \Storage::disk('public')->exists($customer->photo))
                ? \Storage::disk('public')->delete($customer->photo) : null;

            $data['photo'] = $request->file('photo')->store('customer', 'public');
        }

        $customer->update($data);
        return new CustomerResource($customer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return response()->json(['message' => 'Successfully Deleted'], 200);
    }
}
