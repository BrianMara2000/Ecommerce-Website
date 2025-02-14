<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Country;
use App\Models\Product;
use App\Models\Customer;
use App\Enums\AddressType;
use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function getStatistics()
    {
        try {
            $customers = Customer::count();
            $products = Product::count();

            $orderStats = Order::where('status', OrderStatus::Paid->value)
                ->selectRaw('COUNT(*) as total_orders, SUM(total_price) as total_revenue')
                ->first();

            return response()->json([
                'customers' => $customers,
                'products' => $products,
                'orders' => $orderStats->total_orders ?? 0,
                'revenue' => $orderStats->total_revenue ?? 0.00,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve statistics',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getRevenues(Request $request)
    {
        try {
            $period = $request->query('period', 'monthly'); // Default to 'monthly'
            $startDate = $request->query('startDate') ? Carbon::parse($request->query('startDate')) : Carbon::now()->subDays(30);
            $endDate = $request->query('endDate') ? Carbon::parse($request->query('endDate')) : Carbon::now();

            $query = Order::where('status', OrderStatus::Paid->value)
                ->whereBetween('updated_at', [$startDate, $endDate]);

            $allDates = [];

            switch ($period) {
                case 'daily':
                    for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                        $allDates[$date->toDateString()] = [
                            'date' => $date->toDateString(),
                            'total_revenue' => 0,
                            'order_ids' => '',
                        ];
                    }

                    $revenue = $query
                        ->selectRaw('DATE(updated_at) as date, SUM(total_price) as total_revenue, GROUP_CONCAT(id) as order_ids')
                        ->groupBy('date')
                        ->orderBy('date', 'asc')
                        ->get();

                    foreach ($revenue as $row) {
                        $allDates[$row->date] = [
                            'date' => $row->date,
                            'total_revenue' => $row->total_revenue,
                            'order_ids' => $row->order_ids,
                        ];
                    }
                    break;

                case 'weekly':
                    $startDate->startOfMonth(); // Align to start of the month
                    $weeksInMonth = [];

                    // Generate week labels dynamically
                    for ($week = 1; $week <= 5; $week++) {
                        $weeksInMonth["Week $week"] = [
                            'week' => "Week $week",
                            'total_revenue' => 0,
                        ];
                    }

                    // Fetch revenue data grouped by week of the month
                    $revenue = $query
                        ->selectRaw('YEAR(updated_at) AS year, MONTH(updated_at) AS month, 
                                CASE
                                    WHEN DAY(updated_at) BETWEEN 1 AND 7 THEN 1
                                    WHEN DAY(updated_at) BETWEEN 8 AND 14 THEN 2
                                    WHEN DAY(updated_at) BETWEEN 15 AND 21 THEN 3
                                    WHEN DAY(updated_at) BETWEEN 22 AND 28 THEN 4
                                    ELSE 5
                                END AS week_of_month,
                                sum(total_price) as total_revenue,
                                GROUP_CONCAT(id) as order_ids')
                        ->whereBetween('updated_at', [$startDate, $endDate->addDay(1)])
                        ->groupBy('year', 'month', 'week_of_month')
                        ->orderBy('year')
                        ->orderBy('month')
                        ->orderBy('week_of_month')
                        ->get();

                    // Map revenue data to weeks
                    foreach ($revenue as $row) {
                        $weekLabel = "Week " . $row->week_of_month;
                        if (isset($weeksInMonth[$weekLabel])) {
                            $weeksInMonth[$weekLabel]['total_revenue'] = (float) $row->total_revenue;
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
                            'total_revenue' => 0,
                        ];
                    }

                    $revenue = $query
                        ->selectRaw('DATE_FORMAT(updated_at, "%Y-%m") as month, SUM(total_price) as total_revenue')
                        ->groupBy('month')
                        ->orderBy('month', 'asc')
                        ->get();

                    foreach ($revenue as $row) {
                        $allDates[$row->month] = [
                            'month' => $row->month,
                            'total_revenue' => $row->total_revenue,
                        ];
                    }
                    break;
            }

            return response()->json(array_values($allDates));
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve revenue',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function getCountryCustomer()
    {
        try {
            // This query joins the countries table with customer_addresses (and customers) using a left join.
            // It calculates the number of customers (with billing addresses) per country.
            $countriesWithCustomerCount = Country::leftJoin('customer_addresses', 'countries.code', '=', 'customer_addresses.country_code')
                ->leftJoin('customers', 'customer_addresses.customer_id', '=', 'customers.user_id')
                ->leftJoin('orders', function ($join) {
                    $join->on('customers.user_id', '=', 'orders.created_by')
                        ->where('orders.status', OrderStatus::Paid->value);
                })
                ->where(function ($query) {
                    // This condition applies only if there is a matching billing address.
                    $query->where('customer_addresses.type', AddressType::Billing->value)
                        ->orWhereNull('customer_addresses.type');
                })
                ->select(
                    'countries.*',
                    DB::raw('COUNT(orders.id) as total_orders_by_country')
                )
                ->groupBy('countries.code')
                ->orderBy('countries.code', 'asc')
                ->get();

            return response()->json($countriesWithCustomerCount);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve customer count by country',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getLatestCustomers()
    {
        try {
            $customers = Customer::query()->join('users', 'customers.user_id', '=', 'users.id')
                ->select(
                    'customers.user_id as id',
                    'customers.first_name',
                    'customers.last_name',
                    'users.email'
                )
                ->orderBy('customers.created_at', 'desc')
                ->limit(5)
                ->get();

            return response()->json($customers);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve customers',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getLatestOrders()
    {
        try {
            $orders = Order::with(['user', 'items'])
                ->select(
                    'orders.id',
                    'orders.created_by',
                    'orders.total_price',
                    'orders.created_at',
                    DB::raw('SUM(order_items.quantity) as total_quantity'),
                )
                ->join('order_items', 'orders.id', '=', 'order_items.order_id')
                ->groupBy('orders.id', 'orders.created_by', 'orders.total_price')
                ->orderBy('orders.created_at', 'desc')
                ->limit(10)
                ->get();

            $orders->transform(function ($order) {
                return [
                    'id' => $order->id,
                    'name' => $order->user->name,
                    'total_price' => $order->total_price,
                    'total_quantity' => $order->total_quantity,
                    'order_date' => $order->created_at->diffForHumans(),
                ];
            });

            return response()->json($orders);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve orders',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
