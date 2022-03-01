<?php

namespace App\Http\Controllers;

use App\Devis;
use App\Client;
use App\Product;
use App\SoldProduct;
use Carbon\Carbon;
use App\ProductDevis;
use App\Sale;
use PDF;
use Illuminate\Http\Request;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class DevisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $devis = Devis::latest()->paginate(25);

        return view('devis.index', compact('devis'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::latest()->paginate(25);

        return view('devis.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Devis $model)
    {
        $existent = Devis::where('client_id', $request->get('client_id'))->where('finalized_at', null)->get();

        if($existent->count()) {
            return back()->withError('Il y a déjà un devis inachevé appartenant à ce client. <a href="'.route('devis.show', $existent->first()).'">cliquez-ici pour continuer ce devis</a>');
        }
        $client = Client::find($request->get('client_id'));
        $request->merge(['reference' => IdGenerator::generate(['table' => 'sales', 'field'=>'reference', 'length' => 7, 'prefix' =>'F-']),'tva' => $client->tva]);
        $devis = $model->create($request->all());

        return redirect()
            ->route('devis.show', $devis->id)
            ->withStatus('Devis enregistré avec succès, vous pouvez commencer à enregistrer des produits.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $devis = Devis::find($id);
        return view('devis.show', ['devis' => $devis]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Devis  $devis
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $item = Devis::find($id);
        $item->delete();

        return redirect()
            ->route('devis.index')
            ->withStatus('Ce devis a été supprimé avec succès.');
    }


    public function addproduct(Devis $devis)
    {
        $products = Product::all();

        return view('devis.addproduct', compact('devis', 'products'));
    }

    public function storeproduct(Request $request, Devis $devis, ProductDevis $soldProduct)
    {
        $request->merge(['total_amount' => $request->get('price') * $request->get('qty')]);

        $soldProduct->create($request->all());

        return redirect()
            ->route('devis.show', $devis->id)
            ->withStatus('Produit enregistré avec succès.');
    }

    public function editproduct(Devis $devis, ProductDevis $soldproduct)
    {
        $products = Product::all();

        return view('devis.editproduct', compact('devis', 'soldproduct', 'products'));
    }


    public function updateproduct(Request $request, Devis $devis, ProductDevis $soldproduct)
    {
        $request->merge(['total_amount' => $request->get('price') * $request->get('qty')]);

        $soldproduct->update($request->all());

        return redirect()->route('devis.show', $devis->id)->withStatus('Produit modifié avec succès.');
    }

    public function destroyproduct(Devis $devis, ProductDevis $soldproduct)
    {
        $soldproduct->delete();

        return back()->withStatus('Le produit a été éliminé avec succès.');
    }

    public function finalize(Devis $devis)
    {
        $devis->total_amount = $devis->products->sum('total_amount');

        // foreach ($devis->products as $sold_product) {
        //     $product_name = $sold_product->product->name;
        //     $product_stock = $sold_product->product->stock;
        //     if($sold_product->qty > $product_stock) return back()->withError("La quantité en stock du produit '$product_name' est insuffisant. Il ne reste que $product_stock en stock.");
        // }

        // foreach ($devis->products as $sold_product) {
        //     $sold_product->product->stock -= $sold_product->qty;
        //     $sold_product->product->save();
        // }

        $devis->finalized_at = Carbon::now()->toDateTimeString();
        //$sale->client->balance -= $sale->total_amount;
        $devis->save();
        //$sale->client->save();

        return back()->withStatus('Le devis a été conclu avec succès.');
    }


    public function optionsPDF(Devis $devis) {


        // download PDF file with download method
        return view('pdf.options',compact('devis'));
    }

    public function createPDF(Devis $devis, Request $request) {
        $info1 = array("nom"=>"ENTREPRISE OFAYE", "adresse"=>"Point E", "telephone"=>"+221 77 000 00 00/ 76 000 00 00","email"=>"ofaye@example.com","rc"=>"SN DKR A 00000","ninea" => "000000000","compte" => "");
        $info2 = array("nom"=>"ENTREPRISE LAMINE", "adresse"=>"Mbour", "telephone"=>"+221 77 000 00 00/ 76 000 00 00","email"=>"lamine@example.com","rc"=>"SN DKR 0000 A 0000","ninea" => "000000000","compte" => "");
        $whois = $request->get('whois');
        $info = $request->get('whois') == "1" ? $info1 : $info2;
        $pdf = PDF::loadView('pdf.devis', compact('devis','info','whois'));

        $pdf->setPaper('a4', 'landscape');
        $now = Carbon::now();

        // download PDF file with download method
        return $pdf->download('devis-'.$devis->client->name.'-'.$now->toDateTimeString().'.pdf');
    }


    public function validateDevis(Devis $devis)
    {

        $sale = Sale::create([
            'client_id' => $devis->client_id,
            'user_id' => auth()->user()->id,
            'reference' => IdGenerator::generate(['table' => 'sales','field'=>'reference', 'length' => 7, 'prefix' =>'V-']),'tva' => $devis->tva,'total_amount' => $devis->total_amount
        ]);

        foreach ($devis->products as $sold_product) {

            $product_name = $sold_product->product->name;
            $product_stock = $sold_product->product->stock;
            if($sold_product->qty > $product_stock) return back()->with("error","La quantité en stock du produit '$product_name' est insuffisant. Il ne reste que $product_stock en stock.");
        }
        $devis->validate=true;
        $devis->save();
        foreach ($devis->products as $sold_product) {
            SoldProduct::create([
                'sale_id' => $sale->id, 'product_id' => $sold_product->product->id, 'price' => $sold_product->price, 'qty' => $sold_product->qty, 'total_amount' => $sold_product->total_amount
            ]);

        }

        return redirect()->route('sales.show', [$sale]);
    }


    public function editDevis(Devis $devis) {
        $devis->finalized_at = null;
        $devis->save();

        // download PDF file with download method
        return redirect()->route('devis.show', [$devis->id]);
    }
}
