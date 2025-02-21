<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Ticket;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $totalTickets = Ticket::count();
        $openTickets = Ticket::where('status', 'En attente')->orWhere('status', 'En cours')->count();
        $totalUsers = User::count();
        $totalAgents = User::where('role', 'agent')->count();

        $categories = Category::paginate(5);
        $tickets = Ticket::with(['user', 'agent'])->latest()->paginate(5);

        return view('admin.dashboard', compact(
            'totalTickets',
            'openTickets',
            'totalUsers',
            'totalAgents',
            'categories',
            'tickets'
        ));
    }


    public function addCategory()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, )
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        //
    }
}
