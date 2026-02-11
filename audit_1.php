<?php

$br = App\Models\BookingRequest::whereNotNull('proposed_dates')->latest()->first();
if ($br) {
    echo 'BookingRequest #' . $br->id . '\n';
    echo 'proposed_dates: ' . json_encode($br->proposed_dates, JSON_PRETTY_PRINT);
} else {
    echo 'Aucun booking avec proposed_dates';
}
