@section('titre', 'Liste des produits')
@extends('admin.fixe')

@section('body')
    <!--page-content-wrapper-->
    <div class="page-content-wrapper">
        <div class="page-content">

            <!-- start page title -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item">
                                    <a href="javascript: void(0);">{{ config('app.name') }}</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('produits') }}">Produits</a>
                                </li>
                                <li class="breadcrumb-item active">Liste</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->


            <div class="card radius-15">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card-title">
                                <h5 class="mb-0 my-auto">
                                    Liste des produits
                                </h5>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <button class="btn btn-warning btn-sm  px-5" onclick="url('{{ route('promotions') }}')">
                                    Promotions
                                </button>
                                @can('gestion_stock')
                                    <button class="btn btn-dark btn-sm  px-5" data-bs-toggle="modal"
                                        data-bs-target="#modal-add-stock">
                                        Ajout de stock
                                    </button>
                                @endcan
                                @can('product_add')
                                    <button class="btn btn-primary btn-sm  px-5" onclick="url('{{ route('produit.add') }}')">
                                        Ajouter un produit
                                    </button>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <hr />
                    @livewire('Produits.ListProduit')
                </div>
            </div>
        </div>
    </div>


    @can('gestion_stock')
        <!-- Center modal content -->
        <div class="modal fade" id="modal-add-stock" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="myCenterModalLabel">
                            Ajout du stock
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @livewire('Produits.AddStock')
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    @endcan

@endsection
