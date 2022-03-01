<?php

namespace App\Http\Controllers;

use App\Sale;
use App\Product;
use Carbon\Carbon;
use App\SoldProduct;
use App\SoldProductsTmp;
use Illuminate\Http\Request;

class ReturnController extends Controller
{

    public function addproduct(Sale $sale)
    {
        $products = Product::all();

        return view('transactions.return.addproduct', compact('sale', 'products'));
    }

    public function storeproduct(Request $request, Sale $sale, SoldProductsTmp $soldProduct)
    {
        $request->merge(['total_amount' => $request->get('price') * $request->get('qty'),
                    'user_id' => auth()->user()->id]);

        $soldProduct->create($request->all());
        return redirect()
            ->route('return.sale.product', ['sale' => $sale])
            ->withStatus('Produit enregistré avec succès.');
    }

    public function editproduct(Sale $sale, SoldProductsTmp $soldproduct)
    {
        $products = Product::all();

        return view('transactions.return.editproduct', compact('sale', 'soldproduct', 'products'));
    }

    public function updateproduct(Request $request, Sale $sale, SoldProductsTmp $soldproduct)
    {
        $request->merge(['total_amount' => $request->get('price') * $request->get('qty')]);
        $soldproduct->update($request->all());
        return redirect()->route('return.sale.product', [$sale])->withStatus('Produit modifié avec succès.');
    }


    public function destroyproduct(Sale $sale, SoldProductsTmp $soldproduct)
    {
        $soldproduct->delete();

        return back()->withStatus('Le produit a été éliminé avec succès.');
    }

    public function finalize(Sale $sale)
    {
        $soldproducts = SoldProductsTmp::where('sale_id', $sale->id)->get();

        $oldAmount = $sale->total_amount;
        $sale->total_amount = $soldproducts->sum('total_amount');

        foreach ($soldproducts as $sold_product) {
            $product_name = $sold_product->product->name;
            $product_stock = $sold_product->product->stock;
            if($sold_product->qty > $product_stock)
                return back()->withError("La quantité en stock du produit '$product_name' est insuffisant. Il ne reste que $product_stock en stock.");
        }

        foreach ($soldproducts as $sold_product) {




            foreach ($sale->products as $saleItem) {

                if($sold_product->product->id == $saleItem->product->id)
                {

                    $ishere = true;

                    if($saleItem->qty > $sold_product->qty){

                        $sold_product->product->stock += ($saleItem->qty-$sold_product->qty);
                        $sold_product->product->save();
                    }

                    if($saleItem->qty < $sold_product->qty){

                        $sold_product->product->stock -=  ($sold_product->qty-$saleItem->qty);
                        $sold_product->product->save();
                    }

                }



            }

        }



        SoldProduct::where('sale_id', $sale->id)->delete();

        foreach ($soldproducts as $item) {

            SoldProduct::create([
                'sale_id' => $item->sale_id, 'product_id' => $item->product_id, 'price' => $item->price, 'qty'  => $item->qty, 'total_amount' => $item->total_amount
            ]);
        }



        $sale->paid = false;
        if($sale->total_amount >= $oldAmount){
            $sale->client->balance -= $sale->total_amount - $oldAmount;
        }else{
            $sale->client->balance += $oldAmount - $sale->total_amount;
        }

        $sale->save();
        $sale->client->save();
        SoldProductsTmp::where('sale_id', $sale->id)->delete();

        return redirect()->route('sales.show', ['sale' => $sale])->withStatus('Vente modifiée avec succès.');
    }
}

