<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Add this line at the top

class StockController extends Controller
{
    public function fetchWeeklyAdjustedData()
    {
        $client = new Client([
            'base_uri' => 'https://www.alphavantage.co',
        ]);

        try {
            $response = $client->request('GET', '/query', [
                'query' => [
                    'function' => 'TIME_SERIES_WEEKLY_ADJUSTED',
                    'symbol' => 'IBM',
                    'apikey' => 'I19CXRAH86I0V8HW', // Replace with your actual API key
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            Log::info('Alpha Vantage API Response:', $data); // Add this line to log the data

            if (isset($data['Weekly Adjusted Time Series'])) {
                foreach ($data['Weekly Adjusted Time Series'] as $date => $weeklyData) {
                    Stock::updateOrCreate(
                        ['ticker_symbol' => 'IBM', 'date' => $date], // Provide ticker_symbol and date here
                        [
                            'company_name' => 'IBM',
                            'open' => $weeklyData['1. open'],
                            'high' => $weeklyData['2. high'],
                            'low' => $weeklyData['3. low'],
                            'close' => $weeklyData['4. close'],
                            'adjusted_close' => $weeklyData['5. adjusted close'],
                            'volume' => $weeklyData['6. volume'],
                            'dividend amount' => $weeklyData['7. dividend amount'],
                        ]
                    );
                }
                return response()->json(['message' => 'Weekly adjusted data fetched and saved successfully']);
            } else {
                return response()->json(['error' => 'No weekly adjusted data found.']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch or save stock data: ' . $e->getMessage()], 500);
        }
    }
    public function index()
    {
        $stocks = Stock::all();
        return view('dashboard', ['stocks' => $stocks]);
    }
    public function getStocks(): \Illuminate\Http\JsonResponse
    {
        $stocks = Stock::select('company_name')->get();
        return response()->json($stocks);
    }

}
