<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AuditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('audit.index');
    }

    /**
     * Display a listing of the resource according to datatable.
     */
    public function list()
    {
        $query = Audit::query();


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

        // Create a new audit in the database
        $audit = Audit::create($validatedData);

        // Return a JSON response with the stored data
        return response()->json($audit, 201); // Use status code 201 for resource creation
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(Audit::find($id));
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

        $audit = Audit::find($id);
        $audit->update($validatedData);

        // Return a JSON response with the stored data
        return response()->json($audit, 201); // Use status code 201 for resource creation
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $audit = Audit::find($id);
        return $audit->delete();
    }
}
