<?php

namespace App\Http\Controllers;

use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;

class ListMonthsController extends Controller
{
    public function index()
    {
        $year = date('Y');
        $startOfYear = Carbon::parse($year . '-01-01');
        $endOfYear = Carbon::parse($year . '-12-31');

        return CarbonPeriod::create($startOfYear, '1 month', $endOfYear);
    }
}
