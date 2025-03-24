<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $ticketQuery = Ticket::query();

        // Filter tickets based on user role
        if ($user->hasRole('customer')) {
            $ticketQuery->where('created_by', $user->id);
        } elseif ($user->hasRole('agent')) {
            $ticketQuery->where(function ($query) use ($user) {
                $query->where('assigned_to', $user->id)
                    ->orWhere('created_by', $user->id);
            });
        }

        // Calculate statistics
        $totalTickets = $ticketQuery->count();
        $openTickets = (clone $ticketQuery)->where('status', 'open')->count();
        $resolvedToday = (clone $ticketQuery)
            ->where('status', 'closed')
            ->whereDate('updated_at', Carbon::today())
            ->count();

        // Calculate average response time (in hours)
        $avgResponseTime = round($ticketQuery->avg('response_time') ?? 0);

        // Get recent tickets
        $recentTickets = (clone $ticketQuery)
            ->with(['creator:id,name', 'assignedAgent:id,name'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($ticket) {
                return [
                    'id' => $ticket->id,
                    'title' => $ticket->title,
                    'status' => $ticket->status,
                    'customer' => $ticket->creator,
                    'agent' => $ticket->assignedAgent,
                    'created_at' => $ticket->created_at,
                ];
            });

        return Inertia::render('Dashboard', [
            'stats' => [
                'totalTickets' => $totalTickets,
                'openTickets' => $openTickets,
                'resolvedToday' => $resolvedToday,
                'avgResponseTime' => $avgResponseTime . 'h',
                'recentTickets' => $recentTickets,
            ],
        ]);
    }
} 