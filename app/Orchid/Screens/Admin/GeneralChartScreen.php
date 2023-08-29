<?php

namespace App\Orchid\Screens\Admin;

use Orchid\Screen\Screen;

use App\Orchid\Layouts\Chart\LineChartLayout;
use App\Orchid\Layouts\Chart\BarChartLayout;

use App\Models\User;
use App\Models\ApiCredentialKey;

use App\Models\Article;
use App\Models\ArticleFaq;

use App\Models\ApiCallLog;

use Carbon\Carbon;

use Illuminate\Http\Request;

use Laravel\Cashier\Subscription;
use App\Models\PaypalSubscription;
use Stripe\Stripe;
use Carbon\CarbonPeriod;

class GeneralChartScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Request $request): iterable
    {

        if ($request->start) {
            $start = Carbon::now()->subDay($request->start);
        }else{
            $start = Carbon::now()->subDay(60);
        }

        $end = Carbon::now();
        

function gatherData($name, $filterOnEndsAt = null) {
    $endDate = now();
    $startDate = now()->subMonths(12);

    $paypalQuery = PaypalSubscription::orderBy('created_at')->whereBetween('created_at', [$startDate, $endDate]);
    $subscriptionsQuery = Subscription::orderBy('created_at')->whereBetween('created_at', [$startDate, $endDate]);

    if ($filterOnEndsAt !== null) {
        $paypalQuery->where('ends_at', $filterOnEndsAt ? '!=' : '=', null);
        $subscriptionsQuery->where('ends_at', $filterOnEndsAt ? '!=' : '=', null);
    }

    $paypal = $paypalQuery->get();
    $subscriptions = $subscriptionsQuery->get();

    $monthlyCountsPaypal = $paypal->groupBy(function ($subscription) {
        return $subscription->created_at->format('Y-m');
    })->map->count();

    $monthlyCountsSubscriptions = $subscriptions->groupBy(function ($subscription) {
        return $subscription->created_at->format('Y-m');
    })->map->count();

    $months = collect();
    $currentDate = $startDate->copy();
    while ($currentDate <= $endDate) {
        $months->push($currentDate->format('Y-m'));
        $currentDate->addMonth();
    }
    $months->push($endDate->format('Y-m')); 

    $mergedMonthlyCounts = $months->map(function ($month) use ($monthlyCountsPaypal, $monthlyCountsSubscriptions) {
        $countPaypal = $monthlyCountsPaypal->get($month, 0);
        $countSubscriptions = $monthlyCountsSubscriptions->get($month, 0);
        return $countPaypal + $countSubscriptions;
    });

    return [
        'name' => $name,
        'labels' => $months->toArray(),
        'values' => $mergedMonthlyCounts->toArray(),
    ];
}

try {
    $subscriptionData = gatherData('Subscription');
    $cancelSubscriptionData = gatherData('Cancel Subscription', true);
} catch (\Exception $e) {
    dd($e->getMessage());
}





// Set current month and year
$currentMonth = Carbon::now()->month;
$currentYear = Carbon::now()->year;

// Set dates
$endDate = Carbon::now();
$startDate = $endDate->copy()->subMonths(11)->startOfMonth();

// Generate date range
$dateRange = new \DatePeriod($startDate, new \DateInterval('P1M'), $endDate);

$labels = [];
$values = [];

Stripe::setApiKey(env('STRIPE_SECRET'));
$bearer_token = paypal_bearer_token();

// Calculate subscription counts
foreach ($dateRange as $date) {

    $endOfMonth = clone $date;
    $endOfMonth->modify('last day of this month');
    $dateLabel = $endOfMonth->format('Y-m');
    $labels[] = $dateLabel;

    $payPalSubscriptions = PaypalSubscription::where('created_at', '<=', $endOfMonth)
        ->where(function ($query) use ($endOfMonth) {
            $query->where('ends_at', '>', $endOfMonth)
                ->orWhereNull('ends_at');
        })
        ->get();

    $stripeSubscriptions = Subscription::where('created_at', '<=', $endOfMonth)
        ->where(function ($query) use ($endOfMonth) {
            $query->where('ends_at', '>', $endOfMonth)
                ->orWhereNull('ends_at');
        })
        ->get();

    $paypalValues = $payPalSubscriptions->groupBy('paypal_plan')->map->count()->toArray();
    $stripeValues = $stripeSubscriptions->groupBy('stripe_price')->map->count()->toArray();

    $monthlySumPaypal = array_sum(array_map(fn($count, $plan_id) => $count * paypal_subscription_plan_details($bearer_token, $plan_id)->billing_cycles[0]->pricing_scheme->fixed_price->value, $paypalValues, array_keys($paypalValues)));
    $monthlySumStripe = array_sum(array_map(fn($count, $price_id) => $count * \Stripe\Price::retrieve($price_id)->unit_amount / 100, $stripeValues, array_keys($stripeValues)));

    $values[] = $monthlySumPaypal + $monthlySumStripe;
}

$totalRevenue = array_sum($values);

$subscriptionRevenue = [
    'name' => 'Monthly Subscription Payment',
    'labels' => $labels,
    'values' => $values,
    'totalRevenue' => $totalRevenue,
];

$labels = [];
$values = [];

// Calculate subscription counts
foreach ($dateRange as $date) {
    $endOfMonth = clone $date;
    $endOfMonth->modify('last day of this month');
    $dateLabel = $endOfMonth->format('Y-m');
    $labels[] = $dateLabel;

    $payPalSubscriptions = PaypalSubscription::where('created_at', '<=', $endOfMonth)
        ->where(function ($query) use ($endOfMonth) {
            $query->where('ends_at', '>', $endOfMonth)
                ->orWhereNull('ends_at');
        })
        ->count();

    $stripeSubscriptions = Subscription::where('created_at', '<=', $endOfMonth)
        ->where(function ($query) use ($endOfMonth) {
            $query->where('ends_at', '>', $endOfMonth)
                ->orWhereNull('ends_at');
        })
        ->count();

    $totalCount = $payPalSubscriptions + $stripeSubscriptions;
    $values[] = $totalCount;
}

$totalCount = array_sum($values);

$currentSubscriptionData = [
    'name' => 'Monthly Subscription Counts',
    'labels' => $labels,
    'values' => $values,
    'totalCount' => $totalCount,
];



        return [
            'New Users' => [
                User::countByDays($start,$end,'created_at')->toChart('New Users'),
                ApiCredentialKey::countByDays($start,$end,'created_at')->toChart('New Key')
            ],
            'Articles' => [
                Article::countByDays($start,$end,'created_at')->toChart('Article'),
                ArticleFaq::countByDays($start,$end,'created_at')->toChart('Faq Articles')
            ],
            'API Call' => [
                ApiCallLog::where('request_uri','LIKE','%/species-list%')->countByDays($start,$end,'created_at')->toChart('Species List'),
                ApiCallLog::where('request_uri','LIKE','%/species/details%')->countByDays($start,$end,'created_at')->toChart('Species Details'),
                ApiCallLog::where('request_uri','LIKE','%/species-care-guide%')->countByDays($start,$end,'created_at')->toChart('Care Guides'),
                ApiCallLog::where('request_uri','LIKE','%/hardiness-map%')->countByDays($start,$end,'created_at')->toChart('Hardiness Map'),
                ApiCallLog::where('request_uri','LIKE','%/pest-disease-list%')->countByDays($start,$end,'created_at')->toChart('Disease List'),
                ApiCallLog::where('request_uri','LIKE','%/article-faq-list%')->countByDays($start,$end,'created_at')->toChart('Plant FAQ')
            ],
            'API Identify Call' => [
                ApiCallLog::where('request_uri','LIKE','%/api/identify/plant-species%')->countByDays($start,$end,'created_at')->toChart('Species Identify')
            ],
            'Subscriptions' => [
                $subscriptionData,
                $cancelSubscriptionData
            ],
            'Current Subscription' => [
                $currentSubscriptionData
            ],
            'Monthly Revenue' => [
                $subscriptionRevenue
            ]
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'General Chart';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            LineChartLayout::make('New Users', 'New Users Created'),
            LineChartLayout::make('Articles', 'Articles Created'),
            BarChartLayout::make('API Call', 'API Called'),
            BarChartLayout::make('API Identify Call', 'Identify API Called'),
            BarChartLayout::make('Subscriptions', 'Subscriptions'),
            BarChartLayout::make('Current Subscription', 'Current Subscription'),
            BarChartLayout::make('Monthly Revenue', 'Monthly Revenue')
        ];
    }
}