<?php

namespace App\Http\Controllers;

use App\Sale;
use App\Client;
use App\Provider;
use Carbon\Carbon;
use App\SoldProduct;
use App\Transaction;
use App\PaymentMethod;
use App\CanceledIncome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactionname = [
            'income' => 'Encaissement',
            'payment' => 'Paiement',
            'expense' => 'Dépense',
            'transfer' => 'Transfert'
        ];

        $transactions = Transaction::latest()->paginate(25);

        return view('transactions.index', compact('transactions', 'transactionname'));
    }

    public function stats()
    {
        Carbon::setWeekStartsAt(Carbon::MONDAY);
        Carbon::setWeekEndsAt(Carbon::SUNDAY);

        $salesperiods = [];
        $transactionsperiods = [];

        $salesperiods['Aujourd\'hui'] = Sale::whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])->get();
        $transactionsperiods['Aujourd\'hui'] = Transaction::whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])->get();

        $salesperiods['Hier'] = Sale::whereBetween('created_at', [Carbon::now()->subDay(1)->startOfDay(), Carbon::now()->subDay(1)->endOfDay()])->get();
        $transactionsperiods['Hier'] = Transaction::whereBetween('created_at', [Carbon::now()->subDay(1)->startOfDay(), Carbon::now()->subDay(1)->endOfDay()])->get();

        $salesperiods['Semaine'] = Sale::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
        $transactionsperiods['Semaine'] = Transaction::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();

        $salesperiods['Mois'] = Sale::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->get();
        $transactionsperiods['Mois'] = Transaction::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->get();

        $salesperiods['Trimestre'] = Sale::whereBetween('created_at', [Carbon::now()->startOfQuarter(), Carbon::now()->endOfQuarter()])->get();
        $transactionsperiods['Trimestre'] = Transaction::whereBetween('created_at', [Carbon::now()->startOfQuarter(), Carbon::now()->endOfQuarter()])->get();

        $salesperiods['Année'] = Sale::whereYear('created_at', Carbon::now()->year)->get();
        $transactionsperiods['Année'] = Transaction::whereYear('created_at', Carbon::now()->year)->get();

        return view('transactions.stats', [
            'clients'               => Client::where('balance', '!=', '0.00')->get(),
            'salesperiods'          => $salesperiods,
            'transactionsperiods'   => $transactionsperiods,
            'date'                  => Carbon::now(),
            'methods'               => PaymentMethod::all()
        ]);
    }

    public function type($type)
    {
        switch ($type) {
            case 'expense':
                return view('transactions.expense.index', ['transactions' => Transaction::where('type', 'expense')->latest()->paginate(25)]);

            case 'payment':
                return view('transactions.payment.index', ['transactions' => Transaction::where('type', 'payment')->latest()->paginate(25)]);

            case 'income':
                return view('transactions.income.index', ['transactions' => Transaction::where('type', 'income')->latest()->paginate(25)]);
            case 'cancel':
                return view('transactions.income.canceled', ['transactions' => CanceledIncome::latest()->paginate(25)]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type)
    {
        switch ($type) {
            case 'expense':
                return view('transactions.expense.create', [
                    'payment_methods' => PaymentMethod::all(),
                ]);

            case 'payment':
                return view('transactions.payment.create', [
                    'payment_methods' => PaymentMethod::all(),
                    'providers' => Provider::all(),
                ]);

            case 'income':
                return view('transactions.income.create', [
                    'payment_methods' => PaymentMethod::all(),
                ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Transaction $transaction)
    {
        if ($request->get('client_id')) {
            switch ($request->get('type')) {
                case 'income':
                    $request->merge(['title' => 'Paiement reçu du client: ' . $request->get('client_name')]);
                    break;

                case 'expense':
                    $request->merge(['title' => 'Paiement du client: ' . $request->get('client_name')]);

                    if ($request->get('amount') > 0) {
                        $request->merge(['amount' => $request->get('amount')]);
                    }
                    break;
            }
            $request->merge(['reference' => IdGenerator::generate(['table' => 'transactions','field'=>'reference', 'length' => 7, 'prefix' =>'TR-'])]);
            $transaction->create($request->all());
            $client = Client::find($request->get('client_id'));
            $client->balance += $request->get('amount');
            $client->save();


            $sale = Sale::find($request->get('sale_id'));

            if($sale->transactions->sum('amount') == $sale->total_amount){
                $sale->paid = true;
                $sale->save();
            }

            return redirect()
                ->route('clients.show', $request->get('client_id'))
                ->withStatus('Transaction enregistrée avec succès.');
        }
        $request->merge(['reference' => IdGenerator::generate(['table' => 'transactions','field'=>'reference', 'length' => 7, 'prefix' =>'TR-'])]);
        switch ($request->get('type')) {
            case 'expense':
                if ($request->get('amount') > 0) {
                    $request->merge(['amount' => $request->get('amount')]);
                }

                $transaction->create($request->all());

                return redirect()
                    ->route('transactions.type', ['type' => 'expense'])
                    ->withStatus('Dépense enregistrée avec succès.');

            case 'payment':
                if ($request->get('amount') > 0) {
                    $request->merge(['amount' =>  $request->get('amount')]);
                }

                $transaction->create($request->all());

                return redirect()
                    ->route('transactions.type', ['type' => 'payment'])
                    ->withStatus('Paiement enregistré avec succès.');

            case 'income':
                $transaction->create($request->all());

                return redirect()
                    ->route('transactions.type', ['type' => 'income'])
                    ->withStatus('Connexion enregistrée avec succès.');

            default:
                return redirect()
                    ->route('transactions.index')
                    ->withStatus('Transaction enregistrée avec succès.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        switch ($transaction->type) {
            case 'expense':
                return view('transactions.expense.edit', [
                    'transaction' => $transaction,
                    'payment_methods' => PaymentMethod::all()
                ]);

            case 'payment':
                return view('transactions.payment.edit', [
                    'transaction' => $transaction,
                    'payment_methods' => PaymentMethod::all(),
                    'providers' => Provider::all()
                ]);

            case 'income':
                return view('transactions.income.edit', [
                    'transaction' => $transaction,
                    'payment_methods' => PaymentMethod::all(),
                ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        $transaction->update($request->all());

        switch ($request->get('type')) {
            case 'expense':
                if ($request->get('amount') > 0) {
                    $request->merge(['amount' => ((float) $request->get('amount') * (-1))]);
                }
                return redirect()
                    ->route('transactions.type', ['type' => 'expense'])
                    ->withStatus('Dépenses mises à jour avec succès.');

            case 'payment':
                if ($request->get('amount') > 0) {
                    $request->merge(['amount' => ((float) $request->get('amount') * (-1))]);
                }

                return redirect()
                    ->route('transactions.type', ['type' => 'payment'])
                    ->withStatus('Paiement mis à jour de manière satisfaisante.');

            case 'income':
                return redirect()
                    ->route('transactions.type', ['type' => 'income'])
                    ->withStatus('Connexion mise à jour avec succès.');

            default:
                return redirect()
                    ->route('transactions.index')
                    ->withStatus('Transaction mise à jour avec succès.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //if ($transaction->sale)
        //{
        //    return back()->withStatus('You cannot remove a transaction from a completed sale. You can delete the sale and its entire record.');
        //}

        if ($transaction->transfer) {
            return back()->withStatus('Vous ne pouvez pas supprimer une transaction d\'un transfert. Vous devez supprimer le transfert pour supprimer ses enregistrements.');
        }

        $type = $transaction->type;
        if($type == 'income'){
            $sale = Sale::find($transaction->sale_id);
            $sale->paid = false;
            $sale->save();
            CanceledIncome::create([
                'title' => $transaction->title, 'reference' => $transaction->reference, 'amount' => $transaction->amount, 'payment_method_id' => $transaction->payment_method_id, 'client_id' => $transaction->client_id, 'user_id' => Auth::id(), 'sale_id' => $transaction->sale_id
            ]);
            $transaction->delete();
        }


        switch ($type) {
            case 'expense':
                return back()->withStatus('Dépenses supprimées avec succès.');

            case 'payment':
                return back()->withStatus('Le paiement a bien été supprimé.');

            case 'income':
                return back()->withStatus('Entrée supprimée avec succès.');

            default:
                return back()->withStatus('Transaction supprimée avec succès.');
        }
    }


    public function createEncaissement(Sale $sale)
    {

        return view('transactions.income.create', [
            'payment_methods' => PaymentMethod::all(),
            'sale' => $sale
        ]);
    }
}
