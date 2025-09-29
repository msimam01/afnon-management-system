<?php

namespace App\Http\Controllers\Admin;

use App\Models\Season;
use Illuminate\Http\Request;
use App\Models\ReturnVerification;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CollectionVerification;
use App\Services\PerformanceOptimizationService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Support\Str;

class AdminVerificationController extends Controller
{
    public function index()
    {
        $seasons = Season::orderBy('created_at', 'desc')->get();
        return view('admin.verifications.index', compact('seasons'));
    }

    /**
     * Get paginated verification data for the API.
     */
    public function getVerifications(Request $request)
    {
        $perPage = 10;
        $filter = $request->get('filter');
        $seasonName = $request->get('season');
        $status = $request->get('status');
        $type = $request->get('type');
        $page = $request->get('page', 1);

        $query = null;
        if ($type === 'collection') {
            $query = CollectionVerification::with([
                'application.farmer',
                'application.season',
                'application.commodity_allocations',
                'application.monetaryReturn',
                'center'
            ]);
        } elseif ($type === 'return') {
            $query = ReturnVerification::with([
                'application.farmer',
                'application.season',
                'application.commodity_allocations',
                'application.monetaryReturn',
                'center'
            ]);
        } else {
            // Handle both types if no specific type is selected.
            $collectionQuery = CollectionVerification::with([
                'application.farmer',
                'application.season',
                'application.commodity_allocations',
                'application.monetaryReturn',
                'center'
            ]);

            $returnQuery = ReturnVerification::with([
                'application.farmer',
                'application.season',
                'application.commodity_allocations',
                'application.monetaryReturn',
                'center'
            ]);

            // Apply filters to both queries before the union
            $collectionQuery->when($filter, function ($q) use ($filter) {
                $q->whereHas('application.farmer', function ($query) use ($filter) {
                    $query->where('full_name', 'like', '%' . $filter . '%')
                        ->orWhere('registration_number', 'like', '%' . $filter . '%')
                        ->orWhere('phone', 'like', '%' . $filter . '%');
                })->orWhereHas('application', function ($query) use ($filter) {
                    $query->where('reference_number', 'like', '%' . $filter . '%');
                });
            })->when($seasonName, function ($q) use ($seasonName) {
                $q->whereHas('application.season', function ($query) use ($seasonName) {
                    $query->where('name', 'like', '%' . $seasonName . '%');
                });
            })->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            });

            $returnQuery->when($filter, function ($q) use ($filter) {
                $q->whereHas('application.farmer', function ($query) use ($filter) {
                    $query->where('full_name', 'like', '%' . $filter . '%')
                        ->orWhere('registration_number', 'like', '%' . $filter . '%')
                        ->orWhere('phone', 'like', '%' . $filter . '%');
                })->orWhereHas('application', function ($query) use ($filter) {
                    $query->where('reference_number', 'like', '%' . $filter . '%');
                });
            })->when($seasonName, function ($q) use ($seasonName) {
                $q->whereHas('application.season', function ($query) use ($seasonName) {
                    $query->where('name', 'like', '%' . $seasonName . '%');
                });
            })->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            });

            // Transform media paths to public URLs
            $collectionData = $collectionQuery->get()->transform(function ($item) {
    $item->type = 'collection';
    $imagePaths = [];
    foreach ([$item->id_card_photo, $item->commodity_photo] as $path) {
        if ($path) {
            // Use the path directly
            $imagePaths[] = asset('storage/' . $path);
        }
    }
    $item->image_paths = $imagePaths;
    return $item;
});

$returnData = $returnQuery->get()->transform(function ($item) {
    $item->type = 'return';
    $imagePaths = [];
    foreach ([$item->id_card_photo, $item->returned_commodity_photo] as $path) {
        if ($path) {
            $imagePaths[] = asset('storage/' . $path);
        }
    }
    $item->image_paths = $imagePaths;
    return $item;
});

            $allData = $collectionData->merge($returnData);
            $total = $allData->count();
            $paginatedData = $allData->forPage($page, $perPage);
            $pagedData = new \Illuminate\Pagination\LengthAwarePaginator($paginatedData, $total, $perPage, $page, [
                'path' => $request->url(),
                'query' => $request->query(),
            ]);

            return response()->json($pagedData);
        }

        // Apply filters conditionally for single type
        $query->when($filter, function ($q) use ($filter) {
            $q->whereHas('application.farmer', function ($query) use ($filter) {
                $query->where('full_name', 'like', '%' . $filter . '%')
                    ->orWhere('registration_number', 'like', '%' . $filter . '%')
                    ->orWhere('phone', 'like', '%' . $filter . '%');
            })->orWhereHas('application', function ($query) use ($filter) {
                $query->where('reference_number', 'like', '%' . $filter . '%');
            });
        });

        $query->when($seasonName, function ($q) use ($seasonName) {
            $q->whereHas('application.season', function ($query) use ($seasonName) {
                $query->where('name', 'like', '%' . $seasonName . '%');
            });
        });

        $query->when($status, function ($q) use ($status) {
            $q->where('status', $status);
        });

        // Paginate the results directly from the database query.
        $pagedData = $query->paginate($perPage);

        // Normalize the type and image URLs for the single type query.
        $pagedData->getCollection()->transform(function ($item) use ($type) {
    $item->type = $type;
    $imagePaths = [];
    if ($type === 'collection') {
        foreach ([$item->id_card_photo, $item->commodity_photo] as $path) {
            if ($path) {
                $imagePaths[] = asset('storage/' . $path);
            }
        }
    } elseif ($type === 'return') {
        foreach ([$item->id_card_photo, $item->returned_commodity_photo] as $path) {
            if ($path) {
                $imagePaths[] = asset('storage/' . $path);
            }
        }
    }
    $item->image_paths = $imagePaths;
    return $item;
});

        return response()->json($pagedData);
    }

    /**
     * Update the status of a single verification.
     */
    public function verifySingle(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'type' => 'required|string|in:collection,return',
            'status' => 'required|string|in:approved,rejected',
        ]);

        $model = ($validated['type'] === 'collection') ? CollectionVerification::class : ReturnVerification::class;

        try {
            DB::transaction(function () use ($model, $validated) {
                $verification = $model::findOrFail($validated['id']);

                // Prevent duplicate approvals
                if ($verification->status === 'approved') {
                    throw new \Exception('This verification has already been approved.');
                }

                $verification->status = $validated['status'];
                $verification->save();
            });

            $message = $validated['status'] === 'approved' ? 'Verification approved successfully.' : 'Verification rejected successfully.';

            // Clear performance caches after status change
            PerformanceOptimizationService::clearCaches();

            return response()->json(['message' => $message, 'success' => true]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'success' => false], 422);
        }
    }

    /**
     * Bulk approve verifications.
     */
    public function bulkApprove(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ]);

        $type = $request->get('type');
        $approvedCount = 0;
        $skippedCount = 0;

        try {
            DB::transaction(function () use ($type, $validated, &$approvedCount, &$skippedCount) {
                // If type is specified, use that model
                if ($type === 'collection') {
                    $verifications = CollectionVerification::whereIn('id', $validated['ids'])->get();
                } elseif ($type === 'return') {
                    $verifications = ReturnVerification::whereIn('id', $validated['ids'])->get();
                } else {
                    // If type is not specified or empty, check both models
                    $collectionVerifications = CollectionVerification::whereIn('id', $validated['ids'])->get();
                    $returnVerifications = ReturnVerification::whereIn('id', $validated['ids'])->get();
                    $verifications = $collectionVerifications->concat($returnVerifications);
                }

                foreach ($verifications as $verification) {
                    if ($verification->status === 'approved') {
                        $skippedCount++;
                        continue;
                    }

                    if ($verification->status === 'pending') {
                        $verification->update(['status' => 'approved']);
                        $approvedCount++;
                    } else {
                        $skippedCount++;
                    }
                }
            });

            $message = "Bulk approval completed: {$approvedCount} verifications approved";
            if ($skippedCount > 0) {
                $message .= ", {$skippedCount} skipped (already approved or not pending)";
            }
            $message .= ".";

            // Clear performance caches after bulk operations
            PerformanceOptimizationService::clearCaches();

            return response()->json(['message' => $message, 'success' => true]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to approve verifications: ' . $e->getMessage(), 'success' => false], 422);
        }
    }
}
