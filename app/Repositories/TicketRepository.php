<?php

namespace App\Repositories;

use App\Models\Ticket;

class TicketRepository
{
    public function getTickets()
    {
        return Ticket::all();
    }

    public function getTicketById($id)
    {
        return Ticket::find($id);
    }

    public function createTicket($data)
    {
        return Ticket::create($data);
    }

    public function updateTicket($id, $data)
    {
        $ticket = Ticket::findOrFail($id);

        $ticket->update($data);

        return $ticket;
    }

    public function archiveTicket($id)
    {
        $ticket = Ticket::findOrFail($id);

        $ticket->delete();
    }
}