<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\GatewayCheck;
use App\Models\Ticket;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard.index', [
            'eventCount' => Event::where('is_active', true)->count(),
            'ticketCount' => Ticket::count(),
            'paidCount' => Ticket::whereIn('status', ['paid', 'used'])->count(),
            'checkInCount' => Ticket::whereNotNull('checked_in_at')->count(),
            'revenue' => (float) Ticket::whereIn('status', ['paid', 'used'])->sum('amount'),
            'latestTickets' => Ticket::with('event')->latest()->limit(6)->get(),
            'latestGatewayCheck' => GatewayCheck::latest('checked_at')->first(),
        ]);
    }
}
