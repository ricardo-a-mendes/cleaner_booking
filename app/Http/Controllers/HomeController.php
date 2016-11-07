<?php

namespace App\Http\Controllers;

use App\Booking;
use App\City;
use App\Cleaner;
use App\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @param \App\Booking
     * @param \App\City
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Booking $booking, City $cityModel)
    {
        $cities = $cityModel->pluck('name', 'id');

        return view('booking.create', compact('booking', 'cities'));
    }
}
