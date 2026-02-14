<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DonorController extends Controller
{
    public function index()
    {
        // For public donors listing, we'll use sample data for now
        // In production, this would fetch from database
        return Inertia::render('Donors/Index', [
            'donors' => [], // This will be populated by the Vue component
        ]);
    }
}
