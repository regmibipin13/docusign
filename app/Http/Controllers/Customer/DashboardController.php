<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the customer dashboard.
     */
    public function index()
    {
        $user = auth()->user();

        // Get actual statistics
        $totalDocuments = $user->documents()->count();
        $totalSignatures = $user->signatures()->count();
        $signedDocuments = $user->documents()
            ->whereHas('signedDocuments')
            ->count();
        $unsignedDocuments = $totalDocuments - $signedDocuments;

        // Get recent documents
        $recentDocuments = $user->documents()
            ->with('signedDocuments')
            ->latest()
            ->take(5)
            ->get();

        // Get recent signatures
        $recentSignatures = $user->signatures()
            ->latest()
            ->take(5)
            ->get();

        return view('customer.dashboard', compact(
            'user',
            'totalDocuments',
            'totalSignatures',
            'signedDocuments',
            'unsignedDocuments',
            'recentDocuments',
            'recentSignatures'
        ));
    }
}
