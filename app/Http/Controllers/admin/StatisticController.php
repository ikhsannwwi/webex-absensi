<?php

namespace App\Http\Controllers\admin;

use DataTables;
use App\Models\Statistic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Stevebauman\Location\Facades\Location;

class StatisticController extends Controller
{
    private static $module = "statistic";

    public function index(){
        //Check permission
        if (!isAllowed(static::$module, "view")) {
            abort(403);
        }

        return view('administrator.statistic.index');
    }

    public function getData(Request $request)
    {
        $data = Statistic::query();
        
        $data->get();
        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $btn = "";
                if (isAllowed(static::$module, "detail")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-secondary btn-sm me-3" data-bs-toggle="modal" data-bs-target="#Detail">
                    Detail
                </a>';
                endif;
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getDetail($id){

        $data = Statistic::find($id);
        if (!$data) {
            return abort(404);
        }

        $getLocation = Location::get($data->ip_address);

        if ($getLocation) {
            $location = $getLocation->cityName . '-' . $getLocation->countryName;
        } else {
            $location = '-';
        }
        
        return response()->json([
            'data' => $data,
            'location' => $location,
        ]);
    }
}
