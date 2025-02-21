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
            'categoryDistribution'
        ));

    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:low,medium,high',
        ]);

        Ticket::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'priority' => $validated['priority'],
            'user_id' => auth()->id(),
            'status' => 'open',
        ]);

        return redirect()->back()->with('success', 'Ticket ajouté avec succès.');
    }


    public function assignCategory(Request $request, $id)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticket->update($validated);

        return redirect()->back()->with('success', 'Catégorie assignée avec succès!');
    }

    public function assignAgent(Request $request, $id)
    {
        $validated = $request->validate([
            'agent_id' => 'required|exists:categories,id',
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticket->update($validated);

        return redirect()->back()->with('success', 'Agent assignée avec succès!');
    }

    public function changeStatus(Request $request, $id)
    {

        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed'
        ]);
        $ticket = Ticket::findOrFail($id);
        $ticket->update($validated);

        return back()->with('success', 'Ticket status updated successfully');
    }

    public function clientTickets(Request $request){
        $status = $request->query('status');
        $categoryId = $request->query('category');
        $agentId = $request->query('agent');

        $query = Ticket::with(['category', 'agent', 'user'])
            ->where('user_id', auth()->id()) // Restrict to authenticated user's tickets
            ->latest();

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
            'total' => Ticket::where('user_id', auth()->id())->count(),
            'pending' => Ticket::where('user_id', auth()->id())->where('status', 'en_attente')->count(),
            'in_progress' => Ticket::where('user_id', auth()->id())->where('status', 'en_cours')->count(),
            'resolved' => Ticket::where('user_id', auth()->id())->where('status', 'resolu')->count(),
        ];

        $recentActivity = Ticket::with(['user', 'agent'])
            ->where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        $priorityDistribution = Ticket::selectRaw('priority, COUNT(*) as count')
            ->where('user_id', auth()->id())
            ->groupBy('priority')
            ->get();

        $categoryDistribution = Ticket::selectRaw('categories.name, COUNT(*) as count')
            ->join('categories', 'tickets.category_id', '=', 'categories.id')
            ->where('tickets.user_id', auth()->id())
            ->groupBy('categories.name')
            ->get();

        return view('client.tickets', compact(
            'tickets',
            'categories',
            'agents',
            'statistics',
            'recentActivity',
            'priorityDistribution',
            'categoryDistribution'
        ));


        return view('client.tickets', compact('tickets'));

    }


    public function update(Request $request)
    {

        $ticket = Ticket::findOrFail($request->id);
        if ($ticket->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez modifier que vos propres tickets.');
        }

        if ($ticket->status !== 'open') {
            return redirect()->back()->with('error', 'Vous ne pouvez modifier un ticket que s\'il est encore ouvert.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:low,medium,high',
        ]);

        $ticket->update($validated);

        return redirect()->back()->with('success', 'Le ticket a été mis à jour avec succès.');
    }

    public function close(Request $request){
        $ticket = Ticket::findOrFail($request->ticket_id);
        if ($ticket->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez fermer que vos propres tickets.');
        }
       
        if ($ticket->status !== 'resolved') {
            return redirect()->back()->with('error', 'Vous ne pouvez fermer un ticket que s\'il est résolu.');
        }

        $ticket->update(['status' => 'closed' ]);

        return redirect()->back()->with('success', 'Le ticket a été fermé avec succès.');


    }

}
