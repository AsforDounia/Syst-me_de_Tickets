<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request){
        $status = $request->query('status');
        $categoryId = $request->query('category');
        $agentId = $request->query('agent');

        $query = Ticket::with(['category', 'agent', 'user'])->latest();

        if ($status) {
            $query->where('status', $status);
        }
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        if ($agentId) {
            $query->where('agent_id', $agentId);
        }


        $tickets = $query->paginate(10);

        $categories = Category::all();
        $agents = User::where('role', 'agent')->get();

        $statistics = [
            'total' => Ticket::count(),
            'pending' => Ticket::where('status', 'en_attente')->count(),
            'in_progress' => Ticket::where('status', 'en_cours')->count(),
            'resolved' => Ticket::where('status', 'resolu')->count(),
        ];


        $recentActivity = Ticket::with(['user', 'agent'])->latest()->take(5)->get();


        $priorityDistribution = Ticket::selectRaw('priority, COUNT(*) as count')->groupBy('priority')->get();

        $categoryDistribution = Ticket::selectRaw('categories.name, COUNT(*) as count')->join('categories', 'tickets.category_id', '=', 'categories.id')->groupBy('categories.name')->get();


        return view('admin.tickets', compact(
            'tickets',
            'categories',
            'agents',
            'statistics',
            'recentActivity',
            'priorityDistribution',
            'categoryDistribution',
            // 'avgResponseTime'
        ));

    }

    public function create()
    {
        return view('tickets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:low,medium,high',
        ]);

        $ticket = Ticket::create([
            ...$validated,
            'created_by' => auth()->id(),
            'status' => 'open',
        ]);

        return redirect()->route('tickets.show', $ticket);
    }

    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);
        return view('tickets.show', compact('ticket'));
    }

    public function changeStatus(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed'
        ]);

        $ticket->update($validated);

        return back()->with('success', 'Ticket status updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
