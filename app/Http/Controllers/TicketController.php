<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TicketRepository;

class TicketController extends Controller
{
    public function __construct(private TicketRepository $ticketRepository)
    { 

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $tickets = $this->ticketRepository->getTickets();

            return response()->json([
                'success' => true,
                'data' => $tickets
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve tickets: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'requester_id' => 'required|exists:users,id',
                'title' => 'required|string|max:100',
                'description' => 'required|string|max:500',
                'status' => 'required|string|in:open,in_progress,resolved',
                'priority' => 'required|string|in:low,medium,high',
            ]);

            $ticket = $this->ticketRepository->createTicket($data);

            return response()->json([
                'success' => true,
                'message' => 'Ticket created successfully',
                'data' => $ticket
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create ticket: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $ticket = $this->ticketRepository->getTicketById($id);

            if (!$ticket) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ticket not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $ticket
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve ticket: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $data = $request->validate([
                'title' => 'sometimes|string|max:100',
                'description' => 'sometimes|string|max:500',
                'status' => 'sometimes|string|in:open,in_progress,resolved',
                'priority' => 'sometimes|string|in:low,medium,high',
            ]);

            $ticket = $this->ticketRepository->updateTicket($id, $data);

            return response()->json([
                'success' => true,
                'message' => 'Ticket updated successfully',
                'data' => $ticket
            ]);            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update ticket: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->ticketRepository->archiveTicket($id);

            return response()->json([
                'success' => true,
                'message' => 'Ticket archived successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to archive ticket: ' . $e->getMessage()
            ], 500);
        }
    }
}
