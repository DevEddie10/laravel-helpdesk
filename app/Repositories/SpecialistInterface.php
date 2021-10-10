<?php

namespace App\Repositories;

interface SpecialistInterface {

    public function getTickets();

    public function getComments($id);

    public function createComment($request);

    public function saveTicket($request, $id);

    public function reassigned($request, $id);
}