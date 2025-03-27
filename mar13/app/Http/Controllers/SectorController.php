<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SectorController extends Controller
{
    /**
     * Display a listing of the sectors.
     */
    public function index()
    {
        try {
            $sectors = Sector::with('school')
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);
            
            $icon = 'bi bi-diagram-3';
            $button = [
                'text' => 'Add New Sector',
                'route' => route('admin.sectors.create')
            ];

            return view('admin.sectors.index', compact('sectors', 'icon', 'button'));
        } catch (\Exception $e) {
            Log::error('Error in sector index: ' . $e->getMessage());
            return back()->with('error', 'Unable to load sectors. Please try again.');
        }
    }

    /**
     * Show the form for creating a new sector.
     */
    public function create()
    {
        try {
            $schools = School::where('is_active', true)->get();
            $icon = 'bi bi-diagram-3';
            $button = [
                'text' => 'Back to Sectors',
                'route' => route('admin.sectors.index')
            ];

            return view('admin.sectors.create', compact('schools', 'icon', 'button'));
        } catch (\Exception $e) {
            Log::error('Error in sector create: ' . $e->getMessage());
            return back()->with('error', 'Unable to load create form. Please try again.');
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'school_id' => 'required|exists:schools,id'
            ]);
    
            $sector = Sector::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'school_id' => $validated['school_id']
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Sector added successfully'
            ]);
    
        } catch (\Exception $e) {
            Log::error('Sector creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add sector'
            ], 500);
        }
    }
    


    /**
     * Display the specified sector.
     */
    public function show(Sector $sector)
    {
        try {
            $sector->load(['school', 'courses']);
            $icon = 'bi bi-diagram-3';
            $button = [
                'text' => 'Back to Sectors',
                'route' => route('admin.sectors.index')
            ];

            return view('admin.sectors.show', compact('sector', 'icon', 'button'));
        } catch (\Exception $e) {
            Log::error('Error in sector show: ' . $e->getMessage());
            return back()->with('error', 'Unable to load sector details. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified sector.
     */
    public function edit(Sector $sector)
    {
        try {
            $schools = School::where('is_active', true)->get();
            $icon = 'bi bi-diagram-3';
            $button = [
                'text' => 'Back to Sector',
                'route' => route('admin.sectors.show', $sector)
            ];

            return view('admin.sectors.edit', compact('sector', 'schools', 'icon', 'button'));
        } catch (\Exception $e) {
            Log::error('Error in sector edit: ' . $e->getMessage());
            return back()->with('error', 'Unable to load edit form. Please try again.');
        }
    }

    /**
     * Update the specified sector in storage.
     */
    public function update(Request $request, Sector $sector)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'school_id' => 'required|exists:schools,id'
        ]);

        try {
            DB::beginTransaction();

            $sector->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'school_id' => $validated['school_id']
            ]);

            DB::commit();

            return redirect()
                ->route('admin.sectors.show', $sector)
                ->with('success', 'Sector updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in sector update: ' . $e->getMessage());
            return back()
                ->with('error', 'Failed to update sector. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified sector from storage.
     */
    public function destroy($id)
    {
        try {
            $sector = Sector::findOrFail($id);
            $sector->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Sector deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Sector deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete sector'
            ], 500);
        }
    }

    /**
     * Get courses for a specific sector (for AJAX requests)
     */
    public function getCourses(Sector $sector)
    {
        try {
            $courses = $sector->courses()
                            ->where('is_active', true)
                            ->select('id', 'name')
                            ->get();
            
            return response()->json($courses);
        } catch (\Exception $e) {
            Log::error('Error in getting sector courses: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load courses'], 500);
        }
    }
}
