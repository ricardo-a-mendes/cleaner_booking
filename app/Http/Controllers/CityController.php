<?php

namespace App\Http\Controllers;

use App\City;
use App\Http\Requests\CityRequest;
use Session;

class CityController extends Controller
{
    /**
     * @var City
     */
    private $cityModel;

    public function __construct(City $city)
    {
        $this->cityModel = $city;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $cities = $this->cityModel->paginate(25);

        return view('city.index', compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('city.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\CityRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CityRequest $request)
    {
        $requestData = $request->all();

        $this->cityModel->create($requestData);

        Session::flash('success', 'City added!');

        return redirect()->route('city.index');
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
        $city = $this->cityModel->findOrFail($id);

        return view('city.show', compact('city'));
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
        $city = $this->cityModel->findOrFail($id);

        return view('city.edit', compact('city'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \App\Http\Requests\CityRequest $request
     * @param City $city
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, CityRequest $request, City $city)
    {
        $requestData = $request->all();

        $cityDao = $city->findOrFail($id);
        $cityDao->update($requestData);

        Session::flash('success', 'City updated!');

        return redirect()->route('city.index');
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
        $city = $this->cityModel->findOrFail($id);
        if ($city->cleaners()->count() === 0) {
            $cityName = $city->name;
            $city->delete();

            Session::flash('success', trans('city.deleted', compact('cityName')));
        } else {
            Session::flash('info', trans_choice('city.cascade', $city->cleaners()->count()));
        }
        return redirect()->route('city.index');
    }
}
