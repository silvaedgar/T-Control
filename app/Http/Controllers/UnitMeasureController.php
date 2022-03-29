<?php

namespace App\Http\Controllers;

use App\Models\UnitMeasure;
use App\Http\Requests\StoreUnitMeasureRequest;
use App\Http\Requests\UpdateUnitMeasureRequest;
use Spatie\Permission\Models\Role;

class UnitMeasureController extends Controller
{
    public function __construct() {      // Manera de proteger ruta en RoleController hay otra forma
        $this->middleware('can:maintenance');
        // $this->middleware('can:users.create')->only('create');

    }

    public function index()
    {
        $unitmeasures = UnitMeasure::all();
        return view('maintenance.unit-measures.index',compact('unitmeasures'));

        // return view(product_rubric.index);
        //
    }

    public function create()
    {
      return view('maintenance.unit-measures.create');
        //
    }

    public function store(StoreUnitMeasureRequest $request)
    {
        UnitMeasure::create($request->all());
        return redirect()->route('maintenance.unitmeasures.index')->with('status',"Ok_Creación de unidad de medida $request->description");
    }
    public function show(UnitMeasure $unitmeasure)
    {
        return view('maintenance.unit-measures.index');
        //
    }

    public function edit($id)
    {
        $unitmeasure = UnitMeasure::find($id);
        return view('maintenance.unit-measures.edit',compact('unitmeasure'));
        //
    }

    public function update(Request $request)
    {
        $request->validate ([
            'symbol' => ["max:5",Rule::unique('unitmeasures')->ignore($request->id)],
            'description' => ["required",Rule::unique('unitmeasures')->ignore($request->id)]
        ]);
        // }
        $unitmeasure = UnitMeasure::find($request->id);
        $unitmeasure->update($request->all());
        return redirect()->route('maintenance.unitmeasures.index')->with('status',"Ok_Creación de unidad de medida $request->description");
        //
    }

    public function destroy(UnitMeasure $productRubric)
    {
        //
    }
}
