<?php

namespace App\Http\Controllers;

use App\Unite;
use App\Product;
use App\ProductCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Requests\ProductRequest;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::paginate(25);

        return view('inventory.products.index', compact('products'));
    }

    public function search()
    {

        return view('inventory.products.search');
    }

    public function listJson(Request $request)
    {

        $data = Product::with(['category','unite'])->get();

        //return $data;
        if ($request->ajax()) {

            return DataTables::of($data)
                ->addIndexColumn()

                ->rawColumns(['action'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ProductCategory::all();
        $unites = Unite::all();

        return view('inventory.products.create', compact(['categories','unites']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\ProductRequest  $request
     * @param  App\Product  $model
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request, Product $model)
    {
        $category = ProductCategory::find($request->product_category_id);
        $prefixe = substr($category->name,0,3);
        $request->merge(['reference' => IdGenerator::generate(['table' => 'products', 'field'=>'reference', 'length' => 6, 'prefix' =>$prefixe])]);
        $model->create($request->all());
        return redirect()
            ->route('products.index')
            ->withStatus('Produit enregistré avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $solds = $product->solds()->latest()->limit(25)->get();

        $receiveds = $product->receiveds()->latest()->limit(25)->get();

        return view('inventory.products.show', compact('product', 'solds', 'receiveds'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $categories = ProductCategory::all();
        $unites = Unite::all();

        return view('inventory.products.edit', compact('product', 'categories','unites'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->all());

        return redirect()
            ->route('products.index')
            ->withStatus('Produit mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('products.index')
            ->withStatus('Produit supprimé avec succès.');
    }
}
