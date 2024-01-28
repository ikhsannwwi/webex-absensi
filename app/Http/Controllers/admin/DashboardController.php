<?php

namespace App\Http\Controllers\admin;

use App\Models\Statistic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(){
        $Statistic = Statistic::orderBy('visit_time', 'desc')->get();
        $monthlyStats = $this->statisticMonthly();

        $chartDataMonthly = $monthlyStats['chartData'];
        $chartLabelsMonthly = $monthlyStats['chartLabels'];

        return view('administrator.dashboard.index', compact(
            'Statistic',

            'chartDataMonthly',
            'chartLabelsMonthly',
        ));
    }
    
    public function fetchData(){
        $Statistic = Statistic::orderBy('visit_time', 'desc')->get();
        $monthlyStats = $this->statisticMonthly();

        $chartDataMonthly = $monthlyStats['chartData'];
        $chartLabelsMonthly = $monthlyStats['chartLabels'];

        return view('administrator.dashboard.fetchData.index', compact(
            'Statistic',

            'chartDataMonthly',
            'chartLabelsMonthly',
        ))->render();
    }

    protected function statisticMonthly() {
        // Get data for the past 12 months of the current year
        $monthlyStatistics = Statistic::selectRaw('TO_CHAR(visit_time, \'YYYY-MM\') as month, COUNT(*) as count')
            ->whereYear('visit_time', now()->year)
            ->groupByRaw('month')
            ->orderBy('month', 'desc')
            ->take(12) // Display data for the past 12 months
            ->get();
    
        // Create an array with the labels for the past 12 months
        $chartLabelsMonthly = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $chartLabelsMonthly[] = $date->format('M') . ' (0)';
        }
    
        // Initialize data array with zeros
        $chartDataMonthly = array_fill(0, 12, 0);
    
        // Fill in data for existing months
        foreach ($monthlyStatistics as $month) {
            $index = array_search(date('M', strtotime($month->month)) . ' (0)', $chartLabelsMonthly);
            if ($index !== false) {
                $chartDataMonthly[$index] = $month->count;
                $chartLabelsMonthly[$index] = date('M', strtotime($month->month)) . ' (' . $month->count . ')';
            }
        }
    
        return [
            'chartData'   => $chartDataMonthly,
            'chartLabels' => $chartLabelsMonthly,
        ];
    }
    
}
