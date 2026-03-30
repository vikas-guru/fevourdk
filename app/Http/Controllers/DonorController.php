<?php

namespace App\Http\Controllers;

use App\Support\Seo;
use Inertia\Inertia;

class DonorController extends Controller
{
    public function index()
    {
        // For public donors listing, we'll use sample data for now
        // In production, this would fetch from database
        return Inertia::render('Donors/Index', [
            'donors' => [],
            'seo' => Seo::page(
                'Donor community',
                'Supporters and donors on FEVOURD-K — transparent giving and impact across Karnataka.',
                '/donors',
            ),
        ]);
    }
}
