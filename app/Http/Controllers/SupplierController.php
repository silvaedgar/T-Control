<?php

namespace App\Http\Controllers;

use App\Models\Supplier;

use Illuminate\Http\Request;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;


class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::orderBy('name')->get();
        return view('suppliers.index',compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(StoreSupplierRequest $request)
    {
        $supplier = Supplier::create($request->all());
        return redirect()->route('suppliers.index')->with("status","Ok_Proveedor $supplier->name Creado con Exito");
    }

    public function show(Supplier $supplier)
    {
        //
    }
    public function edit(Supplier $supplier)
    {
        $supplier = Supplier::find($supplier->id);
        return view('suppliers.edit',compact('supplier'));
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $request->validate ([
            'document' => "required|string|unique:suppliers,document,$supplier->id"
        ]);
        $supplier = Supplier::find($supplier->id);
        $supplier->update($request->all());
        echo $supplier;
        return redirect()->route('suppliers.index')->with("status","Ok_Proveedor $supplier->name  Actualizado con Exito");
        //
    }

    public function destroy(Supplier $supplier)
    {
    }
}
