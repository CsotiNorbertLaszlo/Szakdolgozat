<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class BalanceController extends Controller
{
    public function addBalance(Request $request)
    {
        $user = Auth::user(); // Aktuális felhasználó lekérése


        if ($user) {

            $amountToAdd = $request->input('amount');

            if ($amountToAdd > 0) {

                $user->balance += $amountToAdd;
                $user->save();

                return redirect()->back()->with('success', 'Balance added successfully!');
            } else {
                return redirect()->back()->with('error', 'Invalid amount!');
            }
        } else {
            return redirect()->back()->with('error', 'User not authenticated!');
        }
    }
}
