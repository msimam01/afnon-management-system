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
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
    /**
     * Get verification summary data for the dashboard cards
     */
    public function getVerificationSummary(Request $request)
    {
        $filter = $request->get('filter');
        $seasonName = $request->get('season');
        $status = $request->get('status');
        $type = $request->get('type');

        // Base queries
        $collectionQuery = CollectionVerification::query();
        $returnQuery = ReturnVerification::query();

        // Apply filters
        if ($filter) {
            $collectionQuery->whereHas('application.farmer', function($q) use ($filter) {
                $q->where('name', 'like', '%' . $filter . '%')
                  ->orWhere('phone', 'like', '%' . $filter . '%')
                  ->orWhere('bvn', 'like', '%' . $filter . '%');
            });

            $returnQuery->whereHas('application.farmer', function($q) use ($filter) {
                $q->where('name', 'like', '%' . $filter . '%')
                  ->orWhere('phone', 'like', '%' . $filter . '%')
                  ->orWhere('bvn', 'like', '%' . $filter . '%');
            });
        }

        if ($seasonName) {
            $collectionQuery->whereHas('application.season', function($q) use ($seasonName) {
                $q->where('name', $seasonName);
            });
            $returnQuery->whereHas('application.season', function($q) use ($seasonName) {
                $q->where('name', $seasonName);
            });
        }

        if ($status) {
            $collectionQuery->where('status', $status);
            $returnQuery->where('status', $status);
        }

        // Get counts
        $approvedCollections = (clone $collectionQuery)->where('status', 'approved')->count();
        $pendingCollections = (clone $collectionQuery)->where('status', 'pending')->count();
        $rejectedCollections = (clone $collectionQuery)->where('status', 'rejected')->count();
        $totalCollections = $collectionQuery->count();

        $approvedReturns = (clone $returnQuery)->where('status', 'approved')->count();
        $pendingReturns = (clone $returnQuery)->where('status', 'pending')->count();
        $rejectedReturns = (clone $returnQuery)->where('status', 'rejected')->count();
        $totalReturns = $returnQuery->count();

        // Combine counts
        return response()->json([
            'approved' => $approvedCollections + $approvedReturns,
            'pending' => $pendingCollections + $pendingReturns,
            'rejected' => $rejectedCollections + $rejectedReturns,
            'collections' => $totalCollections,
            'returns' => $totalReturns,
        ]);
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
            'remarks' => 'nullable|string|max:1000',
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
                $verification->verification_notes = $validated['remarks'] ?? null;
                
                // Only set approved_by if the status is being changed to approved
                if ($validated['status'] === 'approved') {
                    $verification->approved_by = auth()->id();
                }
                
                $verification->save();
                
                // Clear any cached data related to this verification
                if (class_exists(PerformanceOptimizationService::class)) {
                    PerformanceOptimizationService::clearCaches();
                }
            });

            $message = $validated['status'] === 'approved' 
                ? 'Verification approved successfully.' 
                : 'Verification rejected successfully.';

            return response()->json([
                'message' => $message, 
                'success' => true,
                'verification' => [
                    'status' => $validated['status'],
                    'approved_by' => $validated['status'] === 'approved' ? auth()->user()->name : null,
                    'verified_on' => now()->format('d M Y, H:i A'),
                    'remarks' => $validated['remarks'] ?? null
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update verification: ' . $e->getMessage(), 
                'success' => false
            ], 422);
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
            'remarks' => 'nullable|string|max:1000',
        ]);

        $type = $request->get('type');
        $approvedCount = 0;
        $skippedCount = 0;
        $failedIds = [];
        $approverId = auth()->id();

        try {
            DB::beginTransaction();
            
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
                try {
                    if ($verification->status === 'approved') {
                        $skippedCount++;
                        continue;
                    }

                    if ($verification->status === 'pending') {
                        $verification->update([
                            'status' => 'approved',
                            'approved_by' => $approverId,
                            'verification_notes' => $validated['remarks'] ?? null
                        ]);
                        $approvedCount++;
                    } else {
                        $skippedCount++;
                    }
                } catch (\Exception $e) {
                    $failedIds[] = [
                        'id' => $verification->id,
                        'error' => $e->getMessage()
                    ];
                    continue;
                }
            }
            
            DB::commit();

            // Clear performance caches after bulk operations
            if (class_exists(PerformanceOptimizationService::class)) {
                PerformanceOptimizationService::clearCaches();
            }

            $response = [
                'message' => "Bulk approval completed: {$approvedCount} verifications approved",
                'success' => true,
                'summary' => [
                    'total_processed' => count($validated['ids']),
                    'approved' => $approvedCount,
                    'skipped' => $skippedCount,
                    'failed' => count($failedIds),
                ]
            ];
            
            if ($skippedCount > 0) {
                $response['message'] .= ", {$skippedCount} skipped (already approved or not pending)";
            }
            
            if (!empty($failedIds)) {
                $response['message'] .= ", " . count($failedIds) . " failed";
                $response['failed_items'] = $failedIds;
            }
            
            $response['message'] .= ".";
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to process bulk approval: ' . $e->getMessage(), 
                'success' => false
            ], 422);
        }
    }

    /**
     * Download a PDF version of the verification record.
     *
     * @param  string  $type
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * Export verifications in Excel or CSV format
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export(Request $request)
    {
        $filter = $request->get('filter');
        $seasonName = $request->get('season');
        $status = $request->get('status');
        $type = $request->get('type');
        $format = $request->get('format', 'excel');

        // Base query for collection verifications with necessary relationships
        $collectionQuery = CollectionVerification::with([
            'application.farmer',
            'application.season',
            'application.commodity_allocations.commodity',
            'agent.user',
            'center',
            'application.commodity_allocations' => function($query) {
                $query->with('commodity');
            }
        ]);

        // Base query for return verifications with necessary relationships
        $returnQuery = ReturnVerification::with([
            'application.farmer',
            'application.season',
            'application.commodity_allocations.commodity',
            'agent.user',
            'center',
            'application.commodity_allocations' => function($query) {
                $query->with('commodity');
            }
        ]);

        // Apply type filter if specified
        if ($type === 'collection') {
            $collectionQuery->whereRaw('1=1'); // Ensure we get all collection verifications
            $returnQuery->whereRaw('1=0'); // Exclude return verifications
        } elseif ($type === 'return') {
            $collectionQuery->whereRaw('1=0'); // Exclude collection verifications
            $returnQuery->whereRaw('1=1'); // Ensure we get all return verifications
        }

        // Apply common filters to both queries
        foreach ([$collectionQuery, $returnQuery] as $query) {
            // Filter by search term
            if ($filter) {
                $query->whereHas('application.farmer', function($q) use ($filter) {
                    $q->where('full_name', 'like', "%{$filter}%")
                      ->orWhere('registration_number', 'like', "%{$filter}%")
                      ->orWhere('phone', 'like', "%{$filter}%");
                })->orWhereHas('application', function($q) use ($filter) {
                    $q->where('reference_number', 'like', "%{$filter}%");
                });
            }

            // Filter by season
            if ($seasonName) {
                $query->whereHas('application.season', function($q) use ($seasonName) {
                    $q->where('name', $seasonName);
                });
            }

            // Filter by status
            if ($status) {
                $query->where('status', $status);
            }
        }

        // Get the data
        $collectionVerifications = $collectionQuery->get();
        $returnVerifications = $returnQuery->get();

        // Combine and transform the data
        $verifications = collect()
            ->merge($collectionVerifications->map(function($item) {
                return [
                    'type' => 'Collection',
                    'farmer_name' => $item->application?->farmer?->full_name ?? 'N/A',
                    'phone' => $item->application?->farmer?->phone ?? 'N/A',
                    'bvn' => $item->application?->farmer?->bvn ?? 'N/A',
                    'season' => $item->application?->season?->name ?? 'N/A',
                    'commodity' => $item->application->commodity_allocations->pluck('commodity_name')
                        ->filter()
                        ->unique()
                        ->implode(', ') ?: 'N/A',
                    'allocated_qty' => $item->application?->commodity_allocations?->sum('allocated_quantity') ?? 0,
                    'collected_qty' => $item->collected_quantity ?? 0,
                    'returned_qty' => 0,
                    'status' => ucfirst($item->status),
                    'agent' => $item->agent?->user?->name ?? 'N/A',
                    'verification_date' => $item->created_at?->format('Y-m-d H:i:s') ?? 'N/A',
                    'center' => $item->application?->center?->name ?? 'N/A'
                ];
            }))
            ->merge($returnVerifications->map(function($item) {
                return [
                    'type' => 'Return',
                    'farmer_name' => $item->application?->farmer?->full_name ?? 'N/A',
                    'phone' => $item->application?->farmer?->phone ?? 'N/A',
                    'bvn' => $item->application?->farmer?->bvn ?? 'N/A',
                    'season' => $item->application?->season?->name ?? 'N/A',
                    'commodity' => $item->application->commodity_allocations->pluck('commodity_name')
                        ->filter()
                        ->unique()
                        ->implode(', ') ?: 'N/A',
                    'allocated_qty' => $item->application?->commodity_allocations?->sum('allocated_quantity') ?? 0,
                    'collected_qty' => 0,
                    'returned_qty' => $item->returned_quantity ?? 0,
                    'status' => ucfirst($item->status),
                    'agent' => $item->agent?->user?->name ?? 'N/A',
                    'verification_date' => $item->created_at?->format('Y-m-d H:i:s') ?? 'N/A',
                    'center' => $item->application?->center?->name ?? 'N/A'
                ];
            }));

        $headers = [
            'Farmer Name',
            'Phone',
            'BVN',
            'Season',
            'Commodity',
            'Allocated Qty (Bags)',
            'Collected Qty (Bags)',
            'Returned Qty (Bags)',
            'Status',
            'Agent',
            'Verification Type',
            'Verification Date',
            'Center'
        ];

        $data = $verifications->map(function($item) {
            return [
                $item['farmer_name'],
                $item['phone'],
                $item['bvn'],
                $item['season'],
                $item['commodity'],
                $item['allocated_qty'],
                $item['collected_qty'],
                $item['returned_qty'],
                $item['status'],
                $item['agent'],
                $item['type'],
                $item['verification_date'],
                $item['center']
            ];
        })->toArray();

        array_unshift($data, $headers);

        $filename = 'verifications_' . now()->format('Y-m-d_His') . '.' . $format;

        if ($format === 'csv') {
            return $this->exportToCsv($data, $filename);
        }

        return $this->exportToExcel($data, $filename);
    }

    /**
     * Export data to CSV format
     *
     * @param  array  $data
     * @param  string  $filename
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    protected function exportToCsv($data, $filename)
    {
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for proper encoding in Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Export data to Excel format (XLSX) using Laravel Excel
     *
     * @param  array  $data
     * @param  string  $filename
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    protected function exportToExcel($data, $filename)
    {
        $tempPath = storage_path('app/temp/' . $filename);
        
        // Ensure the directory exists
        if (!file_exists(dirname($tempPath))) {
            mkdir(dirname($tempPath), 0755, true);
        }

        $file = fopen($tempPath, 'w');
        
        // Add UTF-8 BOM for proper encoding in Excel
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
        
        foreach ($data as $row) {
            fputcsv($file, $row);
        }
        
        fclose($file);

        // Return the file as a download response
        return response()->download($tempPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function downloadPDF($type, $id)
    {
        try {
            // Determine the model based on the type
            $model = ($type === 'collection') ? CollectionVerification::class : ReturnVerification::class;
            
            // Eager load all necessary relationships
            $verification = $model::with([
                'application.farmer',
                'application.season',
                'application.commodity_allocations.commodity',
                'center',
                'approvedBy'
            ])->findOrFail($id);

            // Safely access properties with null checks
            $farmer = $verification->application->farmer ?? (object)['full_name' => 'N/A', 'registration_number' => 'N/A'];
            $season = $verification->application->season ?? (object)['name' => 'N/A', 'loan_type' => 'N/A'];
            $center = $verification->center ?? (object)['name' => 'N/A'];
            
            // Process commodities with null checks
            $commodities = collect([]);
            if (isset($verification->application->commodity_allocations)) {
                $commodities = $verification->application->commodity_allocations->map(function($allocation) use ($verification, $type) {
                    $commodity = $allocation->commodity_name ?? (object)['name' => 'Unknown', 'unit' => 'bags'];
                    $quantityCollected = $type === 'collection' ? ($verification->collected_quantity ?? 0) : 0;
                    $quantityReturned = $type === 'return' ? ($verification->returned_quantity ?? 0) : 0;
                    
                    return [
                        'name' => $commodity,
                        'allocated' => $allocation->allocated_quantity ?? 0,
                        'actual' => $type === 'collection' ? $quantityCollected : $quantityReturned,
                        'unit' => $commodity->unit ?? 'bags',
                        'difference' => $type === 'collection'
                            ? ($allocation->allocated_quantity ?? 0) - $quantityCollected
                            : $quantityReturned - ($allocation->allocated_quantity ?? 0)
                    ];
                });
            }

            // Prepare the data for the view
            $data = [
                'verification' => $verification,
                'type' => $type,
                'verificationDate' => $verification->created_at 
                    ? Carbon::parse($verification->created_at)->format('d M Y, h:i A')
                    : 'N/A',
                'currentDate' => Carbon::now()->format('d M Y, h:i A'),
                'farmer' => $farmer,
                'season' => $season,
                'center' => $center,
                'approvedBy' => $verification->approvedBy->name ?? 'N/A',
                'verificationNotes' => $verification->verification_notes ?? 'No remarks provided.',
                'status' => $verification->status ? ucfirst($verification->status) : 'Pending',
                'commodities' => $commodities
            ];

            // Generate the PDF
            $pdf = Pdf::loadView('admin.verifications.pdf.verification', $data);
            
            // Create the directory if it doesn't exist
            $directory = storage_path('app/public/verifications');
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }
            
            // Save the PDF temporarily
            $filename = 'verification_' . $type . '_' . $id . '_' . time() . '.pdf';
            $filePath = $directory . '/' . $filename;
            $pdf->save($filePath);
            
            // Return the file for download
            return response()->download($filePath, $filename, [
                'Content-Type' => 'application/pdf',
            ])->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to generate PDF: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
