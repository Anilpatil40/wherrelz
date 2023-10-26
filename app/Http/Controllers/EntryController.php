<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Entry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class EntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('entry.index');
    }

    /**
     * Display a listing of the resource according to datatable.
     */
    public function list()
    {
        $query = Entry::query()->where('user_id', Auth::user()->id);


        return DataTables::of($query)
            ->addColumn('action', function ($user) {
                // Add custom columns as needed
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming data
        $validatedData = $request->validate([
            'account' => 'required',
            'narration' => 'required',
            'currency' => 'required',
            'credit' => 'required',
            'debit' => 'required',
        ]);
        $validatedData['user_id'] = Auth::user()->id;

        // Create a new entry in the database
        $entry = Entry::create($validatedData);

        // Return a JSON response with the stored data
        return response()->json($entry, 201); // Use status code 201 for resource creation
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(Entry::find($id));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the incoming data
        $validatedData = $request->validate([
            'account' => 'required',
            'narration' => 'required',
            'currency' => 'required',
            'credit' => 'required',
            'debit' => 'required',
        ]);

        $entry = Entry::find($id);
        $originalData = $entry->toArray(); // Get the original attributes
        $entry->update($validatedData);
        $updatedData = $entry->toArray(); // Get the original attributes
        $diffs = array_diff($originalData, $updatedData);

        foreach ($diffs as $field => $value) {
            if(in_array($field, ['created_at', 'updated_at'])) continue;
            Audit::create([
                'user_id' => Auth::user()->id,
                'table' => 'entries',
                'field' => $field,
                'oldValue' => $originalData[$field],
                'newValue' => $updatedData[$field],
            ]);
        }

        // Return a JSON response with the stored data
        return response()->json($entry, 201); // Use status code 201 for resource creation
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $entry = Entry::find($id);
        return $entry->delete();
    }
}
