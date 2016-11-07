<?php

namespace App\Http\Controllers;

use App\City;
use App\Cleaner;
use App\Http\Requests\CleanerRequest;
use Session;

class CleanerController extends Controller
{

    /**
     * @var Cleaner
     */
    private $cleanerModel;

    public function __construct(Cleaner $cleaner)
    {
        $this->cleanerModel = $cleaner;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $cleaner = $this->cleanerModel->paginate(25);

        return view('cleaner.index', compact('cleaner'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \App\City $cityModel
     *
     * @return \Illuminate\View\View
     */
    public function create(City $cityModel)
    {
        $cities = $cityModel->all();
        return view('cleaner.create', compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\CleanerRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CleanerRequest $request)
    {
        $requestData = $request->all();
        $cities = [];

        $cleaner = $this->cleanerModel->create($requestData);

        if ($request->has('cities')) {
            $cities = $request->get('cities');
        }
        $cleaner->cities()->sync($cities);

        Session::flash('success', trans('cleaner.created'));

        return redirect()->route('cleaner.index');
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
        $cleaner = $this->cleanerModel->findOrFail($id);
        $cities = $cleaner->cities()->get();
        return view('cleaner.show', compact('cleaner', 'cities'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @param \App\City $cityModel
     *
     * @return \Illuminate\View\View
     */
    public function edit($id, City $cityModel)
    {
        $cleaner = $this->cleanerModel->findOrFail($id);
        $cities = $cityModel->all();
        return view('cleaner.edit', compact('cleaner', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \App\Http\Requests\CleanerRequest
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, CleanerRequest $request)
    {
        
        $requestData = $request->all();
        
        $cleaner = $this->cleanerModel->findOrFail($id);
        $cleaner->update($requestData);

        $cities = [];
        if ($request->has('cities')) {
            $cities = $request->get('cities');
        }
        $cleaner->cities()->sync($cities);

        Session::flash('success', trans('cleaner.updated'));

        return redirect()->route('cleaner.index');
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
        $cleaner = $this->cleanerModel->findOrFail($id);
        $fullName = $cleaner->first_name . ' ' . $cleaner->last_name;
        $cleaner->delete($id);

        Session::flash('success', trans('cleaner.deleted', ['cleanerName' => $fullName]));

        return redirect()->route('cleaner.index');
    }
}
