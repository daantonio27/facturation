@extends('layouts.app', ['page' => 'Rechercher produits', 'pageSlug' => 'search-products', 'section' => 'inventory'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Produits</h4>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('products.create') }}" class="btn btn-sm btn-primary">Nouveau produit</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('alerts.success')

                    <div class="">
                        <table class="table tablesorter search-product" id="">
                            <thead class=" text-primary">
                                <th scope="col">#</th>
                                <th scope="col">Categorie</th>
                                <th scope="col">Référence</th>
                                <th scope="col">Produit</th>
                                <th scope="col">Unité</th>
                                <th scope="col">Prix de base</th>
                                <th scope="col">Stock</th>
                                <th scope="col">Défectueux</th>
                                <th scope="col"></th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-4">

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

var table = $('.search-product').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{url('products/json')}}",
    language: {
        processing: "Traitement en cours...",
        search: "Rechercher&nbsp;:",
        lengthMenu: "Afficher _MENU_ &eacute;l&eacute;ments",
        info: "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
        infoEmpty: "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
        infoFiltered: "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
        infoPostFix: "",
        loadingRecords: "Chargement en cours...",
        zeroRecords: "Aucun &eacute;l&eacute;ment &agrave; afficher",
        emptyTable: "Aucune donnée disponible dans le tableau",
        paginate: {
            first: "Premier",
            previous: "Pr&eacute;c&eacute;dent",
            next: "Suivant",
            last: "Dernier"
        },
        aria: {
            sortAscending: ": activer pour trier la colonne par ordre croissant",
            sortDescending: ": activer pour trier la colonne par ordre décroissant"
        },
        select: {
            rows: {
                "_": "%d lignes sélectionnées",
                "0": "Aucune ligne sélectionnée",
                "1": "1 ligne sélectionnée"
            }
        }
    },
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
        {data: 'category.name', name: 'category.name'},
        {data: 'reference', name: 'reference'},
        {data: 'name', name: 'name'},
        {data: 'unite.name', name: 'unite.name'},
        {data: 'price', name: 'price'},
        {data: 'stock', name: 'stock'},
        {data: 'stock_defective', name: 'stock_defective'},

    ],
    responsive: {
        breakpoints: [{
                name: 'bigdesktop',
                width: Infinity
            },
            {
                name: 'meddesktop',
                width: 1480
            },
            {
                name: 'smalldesktop',
                width: 1280
            },
            {
                name: 'medium',
                width: 1188
            },
            {
                name: 'tabletl',
                width: 1024
            },
            {
                name: 'btwtabllandp',
                width: 848
            },
            {
                name: 'tabletp',
                width: 768
            },
            {
                name: 'mobilel',
                width: 480
            },
            {
                name: 'mobilep',
                width: 320
            }
        ]
    }
});

    </script>
@endpush
