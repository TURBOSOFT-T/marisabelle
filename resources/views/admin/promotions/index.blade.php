@section('titre', 'Gestion des promotions')
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
                                <li class="breadcrumb-item active">
                                    Gestion des promotions
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->


            <div class="card radius-15">
                <div class="card-body ">
                    <div class="card-title">
                        <h5 class="mb-0 my-auto">
                            Gestion des promotions
                        </h5>
                    </div>
                    <hr>
                    @livewire('Promotions.Promotions', ['produit' => $produit])
                </div>
            </div>
        </div>
    </div>
    </div>



@endsection
