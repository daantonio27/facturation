<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Devis</title>
{{-- <link rel="stylesheet" href="{{ public_path('assets/css/bootstrap.min.css') }}"> --}}
<style type="text/css">
    * {
        font-family: Verdana, Arial, sans-serif;
    }
    table{
        font-size: x-small;
    }
    tfoot tr td{
        font-weight: bold;
        font-size: x-small;
    }

    .gray {
        background-color: lightgray
    }

    .customer{
        border: 1px solid #000;
        border-radius: 5px;;
        padding-top: 0;

    }

    .cheader{
        background-color: #000;
        color: #fff;
        text-align: center;
    }
    .cbody{
        padding: 10px;
    }
</style>

</head>
<body>

  <table width="100%">
      <tr>
          <td>&nbsp;</td>
          <td align="right" >PROFORMA N° {{ $devis['reference'] }} DU {{ \Carbon\Carbon::parse($devis['created_at'])->format('d/m/Y') }}</td>
      </tr>
    <tr>
        <td>
            @if($whois == '1')
                <img src="{{ public_path('assets/img/example.png') }}" alt="logo"  width="100px">
            @else
                <img src="{{ public_path('assets/img/example.png') }}" alt="logo"  width="100px">
            @endif
            <div>
                <h1>{{ $info['nom'] }}</h1>
                <p>{{ $info['adresse'] }}</p>
                <p>Tel: (+221) {{ $info['telephone'] }}</p>
                <p>{{ $info['email'] }}</p>
                <p>{{ $info['rc'] }} - {{ $info['ninea'] }}</p>
                <p>DAKAR(SENEGAL)</p>
            </div>
        </td>

        <td>
           <div class="customer">
                <div class="cheader">
                    CLIENT
                </div>
                <div class="cbody">
                    <h3>{{ $devis['client']['name']}}</h3>
                    <h3>TELEPHONE: {{ $devis['client']['phone'] }}</h3>
                </div>

           </div>
        </td>
    </tr>

  </table>
  <p style="font-size: 12px">Montants exprimés en Francs CFA BCEAO</p>
  <table width="100%">
    <thead style="background-color: lightgray;">
      <tr>
        <th style="width:7%">Référence</th>
        <th style="width:43%">Désignation</th>
        <th style="width:7%">Quantité</th>
        <th style="width:7%">unité</th>
        <th style="width:20%">P.U</th>
        <th style="width:25%">Total</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($devis->products as $item)
        <tr>
            <th scope="row">{{$item['product']['reference']}}</th>
            <td>{{$item['product']['name']}}</td>
            <td style="text-align: center">{{ $item['qty'] }}</td>
            <td style="text-align: center">{{$item['product']['unite']['name']}}</td>
            <td align="right">{{$item['price']}}</td>
            <td align="right">{{$item['qty'] * $item['price']}}</td>
        </tr>
      @endforeach
    </tbody>
    <br>
    <tfoot>
        @if($devis->tva == false)
            <tr>
                <td colspan="4">&nbsp;</td>
                <td align="right">&nbsp;</td>
                <td align="right">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="4">&nbsp;</td>
                <td align="right">&nbsp;</td>
                <td align="right">&nbsp;</td>
            </tr>

            <tr>
                <td colspan="4">&nbsp;</td>
                <td align="right">MONTANT TOTAL</td>
                <td align="right">{{ $devis['total_amount'] }}</td>
            </tr>
        @else
            <tr>
                <td colspan="4">&nbsp;</td>
                <td align="right">&nbsp;</td>
                <td align="right">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="4">&nbsp;</td>
                <td align="right">&nbsp;</td>
                <td align="right">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td align="right">TOTAL HT</td>
                <td align="right">{{ $devis['total_amount'] - ($devis['total_amount'] * 18/100)}}</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td align="right">TOTAL TVA</td>
                <td align="right">{{ $devis['total_amount'] * 18/100 }}</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td align="right">TOTAL TTC</td>
                <td align="right" class="gray">{{ $devis['total_amount'] }}</td>
            </tr>
        @endif
    </tfoot>
  </table>

</body>
</html>
