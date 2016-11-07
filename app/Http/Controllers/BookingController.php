<?php

namespace App\Http\Controllers;

use App\Booking;
use App\City;
use App\Http\Requests\BookingCreateRequest;
use App\Http\Requests\BookingRequest;
use App\Http\Requests\BookingUpdateRequest;
use Carbon\Carbon;
use Session;

class BookingController extends Controller
{
    /**
     * @var Booking
     */
    private $bookingModel;

    public function __construct(Booking $booking)
    {
        $this->bookingModel = $booking;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $booking = $this->bookingModel->paginate(25);

        return view('booking.index', compact('booking'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return redirect()->route('home');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\BookingCreateRequest $request
     * @param Customer $customer
     * @param Cleaner $cleaner
     * @param City $city
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(BookingCreateRequest $request, Customer $customer, Cleaner $cleaner, City $city)
    {
        $bookDate = Carbon::parse($request->get('book_date'));

        $currentCity = $city->findOrFail($request->get('city'));

        $daoCustomer = $customer->where('phone_number', '=', $request->get('phone_number'));
        if ($daoCustomer->count() > 0)
            $currentCustomer = $daoCustomer->first();
        else
            $currentCustomer = Customer::create([
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'phone_number' => $request->get('phone_number')
            ]);

        $daoCleaner = $cleaner->join('city_cleaner', 'cleaners.id', '=', 'city_cleaner.cleaner_id')
            ->join('cities', 'city_cleaner.city_id', '=', 'cities.id')
            ->where('cities.id', '=', $currentCity->id)
            ->select('cleaners.*')
            ->first();

        if (is_null($daoCleaner))
            Session::flash('error', 'No Cleaner Found!');
        else
        {
            $booking = Booking::create([
                'date' => $bookDate->format('Y-m-d H:i:s'),
                'customer_id' => $currentCustomer->id,
                'cleaner_id' => $daoCleaner->id
            ]);
            Session::flash('success', "Booked to {$bookDate->format('Y-m-d H:i:s')} !");
        }

        redirect('home');
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
        $booking = $this->bookingModel->findOrFail($id);

        return view('booking.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @param  \App\City $cityModel
     *
     * @return \Illuminate\View\View
     */
    public function edit($id, City $cityModel)
    {
        $booking = $this->bookingModel->findOrFail($id);
        $cities = $cityModel->pluck('name', 'id');

        return view('booking.edit', compact('booking', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param BookingUpdateRequest $request
     * @param Carbon $carbon
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, BookingUpdateRequest $request, Carbon $carbon)
    {
        $booking = $this->bookingModel->findOrFail($id);
        //dd($request->get('book_date'));
        $date = $carbon->parse($request->get('book_date'))->format('Y-m-d H:i:s');
        $booking->update(compact('date'));

        Session::flash('success', 'Booking updated!');

        return redirect('booking');
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
        $this->bookingModel->destroy($id);

        Session::flash('flash_message', 'Booking deleted!');

        return redirect('booking');
    }
}
