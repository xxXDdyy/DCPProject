<?php

namespace App\Http\Controllers;

use App\Models\Degree;
use Illuminate\Http\Request;

class DegreeController extends Controller
{
    private function respond(Request $request, string $route, array|string $parameters = [], string $message = '')
    {
        $url = route($route, $parameters);

        if ($request->expectsJson()) {
            return response()->json([
                'redirect' => $url,
                'message' => $message,
            ]);
        }

        return redirect($url)->with('message', $message);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $degrees = Degree::paginate();
        return view('degree')->with('degrees', $degrees);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('add_degree');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'degree' => 'required|string|max:255',
        ]);

        Degree::create($data);

        return $this->respond($request, 'admin.degrees.index', [], 'Degree added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $degree = Degree::findOrFail($id);
        return view('show_degree_details')->with('degree', $degree);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $degree = Degree::findOrFail($id);
        return view('edit_degree')->with('degree', $degree);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'degree' => 'required|string|max:255',
        ]);

        $degree = Degree::findOrFail($id);
        $degree->degree = $data['degree'];
        $degree->save();

        return $this->respond($request, 'admin.degrees.show', $degree->degree_id, 'Degree updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        Degree::destroy($id);
        return $this->respond($request, 'admin.degrees.index', [], 'Degree deleted successfully!');
    }
}
