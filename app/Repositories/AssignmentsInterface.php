<?php

namespace App\Repositories;

interface AssignmentsInterface
{
    public function getTickets();

    public function createTicket($request);

    public function getTicket($id);

    public function assingSpecialist($request, $id);

    public function monitoringTicket($id);

    public function endupTicket($id);

    public function reactivate($request, $id);

    public function finished($request, $id);

    public function count();
}
