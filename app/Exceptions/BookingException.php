<?php

namespace App\Exceptions;

use Exception;

class BookingException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     */
    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'error' => $this->getMessage(),
                'code' => 'booking_error',
            ], 422);
        }
        
        return back()->withErrors(['booking' => $this->getMessage()]);
    }
    
    /**
     * Report the exception.
     */
    public function report()
    {
        Log::error('Booking exception occurred', [
            'message' => $this->getMessage(),
            'file' => $this->getFile(),
            'line' => $this->getLine(),
        ]);
    }
}
