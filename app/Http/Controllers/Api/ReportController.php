<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Order;
use App\Enums\OrderStatus;
use App\Models\Api\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function getReports(Request $request)
    {
        try {
            $period = $request->query('period', 'monthly'); // Default to 'monthly'
            $startDate = $request->query('startDate') ? Carbon::parse($request->query('startDate')) : Carbon::now()->subDays(30);
            $endDate = $request->query('endDate') ? Carbon::parse($request->query('endDate'))->endOfDay() : Carbon::now()->endOfDay();

            $orderQuery = Order::where('status', OrderStatus::Paid->value)
                ->whereBetween('updated_at', [$startDate, $endDate]);

            $customerQuery = Customer::whereBetween('created_at', [$startDate, $endDate]);
            $allDates = [];

            switch ($period) {
                case 'daily':
                    for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                        $allDates[$date->toDateString()] = [
                            'date' => $date->toDateString(),
                            'total_orders' => 0,
                            'total_customers' => 0
                        ];
                    }

                    $orders = $orderQuery
                        ->selectRaw('DATE(updated_at) as date, COUNT(id) as total_orders')
                        ->groupBy('date')
                        ->orderBy('date', 'asc')
                        ->get();

                    foreach ($orders as $row) {
                        $allDates[$row->date]['total_orders'] = $row->total_orders;
                    }

                    $customers = $customerQuery
                        ->selectRaw('DATE(created_at) as date, COUNT(user_id) as total_customers')
                        ->groupBy('date')
                        ->orderBy('date', 'asc')
                        ->get();

                    foreach ($customers as $row) {
                        $allDates[$row->date]['total_customers'] = $row->total_customers;
                    }
                    break;

                case 'weekly':
                    $weeksInMonth = [];
                    for ($week = 1; $week <= 5; $week++) {
                        $weeksInMonth["Week $week"] = [
                            'week' => "Week $week",
                            'total_orders' => 0,
                            'total_customers' => 0,
                        ];
                    }

                    $orders = $orderQuery
                        ->selectRaw('YEAR(updated_at) AS year, MONTH(updated_at) AS month, 
                                CEIL(DAY(updated_at) / 7) AS week_of_month, COUNT(id) as total_orders')
                        ->groupBy('year', 'month', 'week_of_month')
                        ->orderBy('year')
                        ->orderBy('month')
                        ->orderBy('week_of_month')
                        ->get();

                    foreach ($orders as $row) {
                        $weekLabel = "Week " . $row->week_of_month;
                        if (isset($weeksInMonth[$weekLabel])) {
                            $weeksInMonth[$weekLabel]['total_orders'] = $row->total_orders;
                        }
                    }

                    $customers = $customerQuery
                        ->selectRaw('YEAR(created_at) AS year, MONTH(created_at) AS month, 
                                CEIL(DAY(created_at) / 7) AS week_of_month, COUNT(user_id) as total_customers')
                        ->groupBy('year', 'month', 'week_of_month')
                        ->orderBy('year')
                        ->orderBy('month')
                        ->orderBy('week_of_month')
                        ->get();

                    foreach ($customers as $row) {
                        $weekLabel = "Week " . $row->week_of_month;
                        if (isset($weeksInMonth[$weekLabel])) {
                            $weeksInMonth[$weekLabel]['total_customers'] = $row->total_customers;
                        }
                    }

                    $allDates = array_values($weeksInMonth);
                    break;

                case 'monthly':
                default:
                    for ($date = $startDate->copy(); $date->lte($endDate); $date->addMonth()) {
                        $monthKey = $date->format('Y-m');
                        $allDates[$monthKey] = [
                            'month' => $monthKey,
                            'total_orders' => 0,
                            'total_customers' => 0
                        ];
                    }

                    $orders = $orderQuery
                        ->selectRaw('DATE_FORMAT(updated_at, "%Y-%m") as month, COUNT(id) as total_orders')
                        ->groupBy('month')
                        ->orderBy('month', 'asc')
                        ->get();

                    foreach ($orders as $row) {
                        $allDates[$row->month]['total_orders'] = $row->total_orders;
                    }

                    $customers = $customerQuery
                        ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(user_id) as total_customers')
                        ->groupBy('month')
                        ->orderBy('month', 'asc')
                        ->get();

                    foreach ($customers as $row) {
                        $allDates[$row->month]['total_customers'] = $row->total_customers;
                    }

                    break;
            }

            return response()->json(array_values($allDates));
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve reports',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
