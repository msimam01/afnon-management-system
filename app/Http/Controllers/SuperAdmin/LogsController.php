<?php

namespace App\Http\Controllers\SuperAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class LogsController extends Controller
{
    
    public function index(Request $request) {
        $query = Activity::latest();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('causer_type', 'like', "%{$search}%")
                  ->orWhere('subject_type', 'like', "%{$search}%");
            });
        }

        // Pagination
        $activities = $query->paginate(15); // Adjust the number as needed

        return view('super-admin.logs.index', compact('activities'));
    }
}