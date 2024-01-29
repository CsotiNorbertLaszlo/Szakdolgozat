<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model{
    protected $primaryKey = 'stock_id';
    use HasFactory;

    protected $table = 'stocks';

    protected $fillable = [
        'ticker_symbol',
        'company_name',
        'sector',
        'exchange',
        'market_cap',
        'dividend_yield',
        'earnings_per_share',
        'pe_ratio',
        'week_52_high',
        'week_52_low',
        'volume',
        'company_description',
        'website',
        'dividend_amount',
        'adjusted_close'
    ];
}
