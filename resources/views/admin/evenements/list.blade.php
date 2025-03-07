@section('titre', 'Liste des sponsors')
@extends('admin.fixe')

@section('body')




    <!--page-content-wrapper-->
    <div class="page-content-wrapper">
        <div class="page-content">

                    <div class="container-xxl flex-grow-1 container-p-y">
                        
                      <div class="row mb-3">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item">
                                            <a href="javascript: void(0);">{{ config('app.name') }}</a>
                                        </li>
                                        <li class="breadcrumb-item">
                                            <a href="{{ route('events') }}">Atualités</a>
                                        </li>
                                        <li class="breadcrumb-item active">Liste</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                       
                        <!-- Sponsors List Table -->

                        <div class="card radius-15">
                          <div class="card-body">
                              <div class="d-flex justify-content-between">
                                  <div class="card-title">
                                      <h5 class="mb-0 my-auto">
                                          Liste des actualités
                                      </h5>
                                  </div>
                                  <div>
                                      {{-- <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                          data-bs-target="#import">
                                          <i class="ri-user-add-line"></i>
                                          Importer fiche exel
                                      </button> --}}
                                    
          
                                      <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                      data-bs-target="#add">
                                      <i class="ri-user-add-line"></i>
                                      Ajouter une actualite
                                  </button>
                                  </div>
                              </div>
                              <hr />
                              @include('components.alert')
          
                              <div class="table-responsive-sm">
                                  <table id="basic-datatable"  class="datatables-users table" {{-- class="table table-striped dt-responsive nowrap w-100" --}}>
                                      <thead class="table-dark cusor">
                                          <tr>
                                              <th>Image</th>
                                              <th>Titre</th>
                                             
                                              <th>Créé le</th>
                                              <th scope="col" width="15%">Actions</th>
                                            
                                              
                                              <th style="text-align: right;">
                                                  <span wire:loading>
                                                      <img src="https://i.gifer.com/ZKZg.gif" width="20" height="20"
                                                          class="rounded shadow" alt="">
                                                  </span>
                                              </th>
                                          </tr>
                                         
                                      </thead>
          
          
                                      <tbody>
                                          @forelse ($events as $event)
                                              <tr>
                                                  <td>
                                                    <img src="{{ Storage::url($event->image) }}" width="40 " height="40 "
                                                    class="rounded shadow" alt="">
                                                  </td>
                                                  <td>
                                                      {{ $event->titre }}
                                                  </td>
                                                 
                                                
                                                
                                                  <td>{{ $event->created_at }} </td>

                                                  <td>
                                                    <div class="row">
                                                        <div class="col">
                                                            <form action="{{ route('events.destroy',$event->id) }}" method="POST">
                                                                @csrf
                                                                <input name="_method" type="hidden" value="DELETE">
                                                                <button type="submit" class="btn btn-xs btn-danger btn-flat show_confirm" data-toggle="tooltip"
                                                                    title='Delete'><svg xmlns="http://www.w3.org/2000/svg"
                                                                    viewBox="0 0 24 24" width="20"
                                                                    style="background-color: #e0610d22; fill:#dbd7d7;"
                                                                    height="22" fill="currentColor">
                                                                    <path
                                                                        d="M6.45455 19L2 22.5V4C2 3.44772 2.44772 3 3 3H21C21.5523 3 22 3.44772 22 4V18C22 18.5523 21.5523 19 21 19H6.45455ZM13.4142 11L15.8891 8.52513L14.4749 7.11091L12 9.58579L9.52513 7.11091L8.11091 8.52513L10.5858 11L8.11091 13.4749L9.52513 14.8891L12 12.4142L14.4749 14.8891L15.8891 13.4749L13.4142 11Z">
                                                                    </path>
                                                                </svg></button>
                                            
                                                                </form>
        
                                                        </div>
                                                           <div class="col">
                                                            <a href="{{ route('event_update',['id'=>$event->id]) }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                viewBox="0 0 24 24" width="35" hight="38"
                                                                fill="currentColor">
                                                                <path
                                                                    d="M16.7574 2.99677L9.29145 10.4627L9.29886 14.7098L13.537 14.7024L21 7.23941V19.9968C21 20.5491 20.5523 20.9968 20 20.9968H4C3.44772 20.9968 3 20.5491 3 19.9968V3.99677C3 3.44448 3.44772 2.99677 4 2.99677H16.7574ZM20.4853 2.09727L21.8995 3.51149L12.7071 12.7039L11.2954 12.7063L11.2929 11.2897L20.4853 2.09727Z">
                                                                </path>
                                                            </svg>
                                                            </a>
                                                           </div>
                                                    </div>
                                                  </td>
                                                  
                                              </tr>
                                          @empty
                                              <tr>
                                                  <td colspan="9" class="text-center">Aucune actualité trouvée</td>
                                              </tr>
                                          @endforelse
          
                                      </tbody>
          
          
                                  </table>
                              </div>
                             
          
                          </div>
                      </div>
  
                        
                      </div>



                </div>

            </div>

     


    </div>

 <!-- Center modal content -->
 <div class="modal fade" id="add" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
          <div class="modal-header">
              <h6 class="modal-title" id="myCenterModalLabel">
                  Ajouter une actualité.
              </h6>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
       
         @livewire('Evenements.AddEvenement',['event'=> null]  )
      </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<script src="../../assets/js/app-user-list.js"></script>


<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script type="text/javascript">
    $('.show_confirm').click(function(event) {
          var form =  $(this).closest("form");
          var name = $(this).data("name");
          event.preventDefault();
          swal({
              title: `Are you sure you want to delete this record?`,
              text: "If you delete this, it will be gone forever.",
              icon: "warning",
              buttons: true,
              dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              form.submit();
            }
          });
      });

</script>

@endsection