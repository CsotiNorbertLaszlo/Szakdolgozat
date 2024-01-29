<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function buyStocks(Request $request)
    {

        $request->validate([
            'stock_id' => 'required|exists:stocks,id',
            'quantity' => 'required|integer|min:1',
        ]);


        $stock = Stock::findOrFail($request->stock_id);
        $totalCost = $stock->price * $request->quantity;


        if (auth()->user()->balance < $totalCost) {
            return response()->json(['error' => 'Insufficient balance'], 422);
        }
        auth()->user()->balance -= $totalCost;
        auth()->user()->save();

        Transaction::create([
            'user_id' => auth()->user()->id,
            'stock_id' => $stock->id,
            'transaction_type' => 'buy',
            'amount' => $request->quantity,
            'price' => $stock->price,
            'commission' => 0,
            'transaction_date' => now(),
        ]);

        return response()->json(['message' => 'Stocks bought successfully'], 200);
    }
    private function getStockPrice($stockId)
    {
        $stockPrice = 100;
        return $stockPrice;
    }
}
