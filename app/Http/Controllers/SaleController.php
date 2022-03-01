<?php

namespace App\Http\Controllers;

use PDF;
use App\Sale;
use App\Client;
use App\Product;
use Money\Money;
use Carbon\Carbon;
use App\SoldProduct;
use App\Transaction;
use App\PaymentMethod;
use App\SoldProductsTmp;
use Illuminate\Http\Request;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sales = Sale::latest()->paginate(25);

        return view('sales.index', compact('sales'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::latest()->paginate(25);

        return view('sales.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Sale $model)
    {
        $existent = Sale::where('client_id', $request->get('client_id'))->where('finalized_at', null)->get();

        if($existent->count()) {
            return back()->withError('Il y a déjà une vente inachevée appartenant à ce client. <a href="'.route('sales.show', $existent->first()).'">cliquez-ici pour continuer cette vente</a>');
        }
        $client = Client::find($request->get('client_id'));
        $request->merge(['reference' => IdGenerator::generate(['table' => 'sales','field'=>'reference', 'length' => 7, 'prefix' =>'V-']),'tva' => $client->tva]);

        $sale = $model->create($request->all());


        return redirect()
            ->route('sales.show', ['sale' => $sale->id])
            ->withStatus('Vente enregistrée avec succès, vous pouvez commencer à enregistrer des produits et des transactions.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Sale $sale)
    {
        return view('sales.show', ['sale' => $sale]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sale $sale)
    {
        $sale->delete();

        return redirect()
            ->route('sales.index')
            ->withStatus('Cette vente a été supprimé avec succès.');
    }

    public function finalize(Sale $sale)
    {
        $sale->total_amount = $sale->products->sum('total_amount');

        foreach ($sale->products as $sold_product) {
            $product_name = $sold_product->product->name;
            $product_stock = $sold_product->product->stock;
            if($sold_product->qty > $product_stock) return back()->withError("La quantité en stock du produit '$product_name' est insuffisant. Il ne reste que $product_stock en stock.");
        }

        foreach ($sale->products as $sold_product) {
            $sold_product->product->stock -= $sold_product->qty;
            $sold_product->product->save();
        }

        $sale->finalized_at = Carbon::now()->toDateTimeString();
        $sale->client->balance -= $sale->total_amount;
        $sale->save();
        $sale->client->save();

        return back()->withStatus('La vente a été conclue avec succès.');
    }

    public function addproduct(Sale $sale)
    {
        $products = Product::all();

        return view('sales.addproduct', compact('sale', 'products'));
    }

    public function storeproduct(Request $request, Sale $sale, SoldProduct $soldProduct)
    {
        $request->merge(['total_amount' => $request->get('price') * $request->get('qty')]);

        $soldProduct->create($request->all());

        return redirect()
            ->route('sales.show', ['sale' => $sale])
            ->withStatus('Produit enregistré avec succès.');
    }

    public function editproduct(Sale $sale, SoldProduct $soldproduct)
    {
        $products = Product::all();

        return view('sales.editproduct', compact('sale', 'soldproduct', 'products'));
    }

    public function updateproduct(Request $request, Sale $sale, SoldProduct $soldproduct)
    {
        $request->merge(['total_amount' => $request->get('price') * $request->get('qty')]);

        $soldproduct->update($request->all());

        return redirect()->route('sales.show', $sale)->withStatus('Produit modifié avec succès.');
    }

    public function destroyproduct(Sale $sale, SoldProduct $soldproduct)
    {
        $soldproduct->delete();

        return back()->withStatus('Le produit a été éliminé avec succès.');
    }

    public function addtransaction(Sale $sale)
    {
        $payment_methods = PaymentMethod::all();

        return view('sales.addtransaction', compact('sale', 'payment_methods'));
    }

    public function storetransaction(Request $request, Sale $sale, Transaction $transaction)
    {
        switch($request->all()['type']) {
            case 'income':
                $request->merge(['title' => 'Paiement reçu de l\'ID de vente: ' . $request->get('sale_id')]);
                break;

            case 'expense':
                $request->merge(['title' => 'ID du paiement de la vente: ' . $request->all('sale_id')]);

                if($request->get('amount') > 0) {
                    $request->merge(['amount' => $request->get('amount') ]);
                }
                break;
        }

        $transaction->create($request->all());

        return redirect()
            ->route('sales.show', compact('sale'))
            ->withStatus('Transaction enregistrée avec succès.');
    }

    public function edittransaction(Sale $sale, Transaction $transaction)
    {
        $payment_methods = PaymentMethod::all();

        return view('sales.edittransaction', compact('sale', 'transaction', 'payment_methods'));
    }

    public function updatetransaction(Request $request, Sale $sale, Transaction $transaction)
    {
        switch($request->get('type')) {
            case 'income':
                $request->merge(['title' => 'Paiement reçu de l\'ID de vente: '. $request->get('sale_id')]);
                break;

            case 'expense':
                $request->merge(['title' => 'ID du paiement de la vente: '. $request->get('sale_id')]);

                if($request->get('amount') > 0) {
                    $request->merge(['amount' => $request->get('amount')]);
                }
                break;
        }
        $transaction->update($request->all());

        return redirect()
            ->route('sales.show', compact('sale'))
            ->withStatus('Transaction modifiée avec succès.');
    }

    public function destroytransaction(Sale $sale, Transaction $transaction)
    {
        $transaction->delete();

        return back()->withStatus('Transaction supprimée avec succès.');
    }

    /**
     * Methode encaisser paiement vente client
     */
    public function encaisser(Sale $sale)
    {

        return redirect()
            ->route('sales.index')
            ->withStatus('Cette ligne de vente a été supprimé avec succès.');
    }

    public function optionsPDF(Sale $sale) {


        // download PDF file with download method
        return view('pdf.optionsvente',compact('sale'));
    }

    public function createPDF(Sale $sale, Request $request) {
        $info1 = array("nom"=>"ENTREPRISE OFAYE", "adresse"=>"Point E", "telephone"=>"+221 77 000 00 00/ 76 000 00 00","email"=>"ofaye@example.com","rc"=>"SN DKR A 00000","ninea" => "000000000","compte" => "");
        $info2 = array("nom"=>"ENTREPRISE LAMINE", "adresse"=>"Mbour", "telephone"=>"+221 77 000 00 00/ 76 000 00 00","email"=>"lamine@example.com","rc"=>"SN DKR 0000 A 0000","ninea" => "000000000","compte" => "");
        $whois = $request->get('whois');
        $size = $request->get('size');
        $info = $request->get('whois') == "1" ? $info1 : $info2;

        $pdf = PDF::loadView('pdf.facturea5', compact('sale','info','whois'));
        if($size == '1')
        {
            $pdf->setPaper('a4');
        }else{
            $pdf->setPaper('a5');
        }

        $now = Carbon::now();
        // download PDF file with download method
        return $pdf->download('facture-a5-'.$sale->client->name.'-'.$now->toDateTimeString().'.pdf');



    }

    public function returnSale(Sale $sale) {

        $returnliste = SoldProductsTmp::where([
            ['sale_id','=',$sale->id],
            ['user_id','!=',auth()->user()->id]
        ])->get();


        if($returnliste->count() != 0){
            return back()->withStatus('Cette vente est en cours de modification!');
        }

        SoldProductsTmp::where('sale_id', $sale->id)->delete();
        foreach ($sale->products as $item) {
            # code...
            SoldProductsTmp::create([
                'sale_id' => $sale->id, 'product_id' => $item->product->id, 'price' => $item->price, 'qty'  => $item->qty, 'total_amount' => $item-> total_amount, 'user_id' => auth()->user()->id
            ]);
        }

        return redirect()->route('return.sale.product',[$sale]);

    }

    public function refreshReturnSale(Sale $sale) {

        $items = SoldProductsTmp::where('sale_id',$sale->id)->get();
        return view('transactions.return.show',compact('sale','items'));
    }
}
