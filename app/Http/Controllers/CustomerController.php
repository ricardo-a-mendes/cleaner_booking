<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Http\Requests\CustomerRequest;
use Session;

class CustomerController extends Controller
{
    /**
     * @var Customer
     */
    private $customerModel;

    public function __construct(Customer $customer)
    {
        $this->customerModel = $customer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $customer = $this->customerModel->paginate(25);

        return view('customer.index', compact('customer'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('customer.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\CustomerRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CustomerRequest $request)
    {
        
        $requestData = $request->all();
        
        $this->customerModel->create($requestData);

        Session::flash('success', trans('customer.created'));

        return redirect()->route('customer.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $customer = $this->customerModel->findOrFail($id);

        return view('customer.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $customer = $this->customerModel->findOrFail($id);

        return view('customer.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \App\Http\Requests\CustomerRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, CustomerRequest $request)
    {
        
        $requestData = $request->all();
        
        $customer = $this->customerModel->findOrFail($id);
        $customer->update($requestData);

        Session::flash('success', trans('customer.updated'));

        return redirect()->route('customer.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $customer = $this->customerModel->findOrFail($id);
        $fullName = $customer->first_name . ' ' . $customer->last_name;
        $customer->delete($id);

        Session::flash('success', trans('customer.deleted', ['customerName' => $fullName]));

        return redirect()->route('customer.index');
    }
}
