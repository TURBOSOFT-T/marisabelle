<?php

namespace App\Http\Controllers;

use App\Models\commandes;
use App\Models\historiques_stock;
use App\Models\config;
use App\Models\historiques_connexion;
use App\Models\{produits, Category,Marque, Contact, favoris, Service, Coupon, Testimonial};
use App\Models\User;
use App\Models\views;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportUser;
use App\Http\Traits\ListGouvernorats;
use App\Models\clients;
use App\Models\contenu_commande;
use App\Models\domaines;
use App\Models\notifications;
use App\Models\templates;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\{OrderChangeStatut, ChangeStatut};
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class AdminController extends Controller
{
    use ListGouvernorats;

    public function comptes(){
        $commmandes= commandes::where('user_id', auth()->id() )->get();
        return view('front.comptes.commandes' , compact('commandes'));
     }

     public function favories(){
        $favorie= favoris::where('user_id', auth()->id() )->get();
        return view('front.comptes.favoris' , compact('favoris'));
     }

     public function admin_contact_form(){
        $contacts = Contact::paginate(100);
        return view('admin.contacts.list', compact('contacts'));
     }

     public function supprimer_messages(Request $request , $id){
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return redirect()->back()->with('success', 'Message supprimé avec succès');;

     }

    public function dashboard(Request $request)
    {

        //veification des permissions
          if (auth()->user()->can('dashboard')) {
        }
         elseif (auth()->user()->can('product_view')) 
         {
            return redirect()->route('produits');
        }elseif (auth()->user()->can('order_view')) {
            return redirect()->route('commandes');
        } elseif (auth()->user()->can('clients_view')) {
            return redirect()->route('clients');
        } else {
           // return "Veuillez demande a l'administrateur de vous attribuer des permissions.";
           return redirect('/');
        } 
 

        $currentYear = date('Y');

        $currentYear2 = Carbon::now()->year;


        // Format ISO 8601 (YYYY-MM-DD)
        $firstDayOfYearISO = Carbon::createFromDate($currentYear2, 1, 1)->startOfDay()->format('Y-m-d');
        $lastDayOfYearISO = Carbon::createFromDate($currentYear2, 12, 31)->endOfDay()->format('Y-m-d');



        $date_debut = $request->input('date_debut') ??  $firstDayOfYearISO;
        $date_fin = $request->input('date_fin') ?? $lastDayOfYearISO;


        //get statistiques
        $visitsPerMonth = [];
        $commandesPerMonth = [];
        $ventesPerMonth = [];
        $inscriptionMonth = [];
        $stat_commande_confirmer_Month = [];
        $stat_commande_non_confirmer_Month = [];
        $profilNet = [];
        for ($i = 1; $i <= 12; $i++) {
            $visitsPerMonth[] = Views::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $i)
                ->count();
            $commandesPerMonth[] = Commandes::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $i)->count();

            $stat_commande_confirmer_Month[] =  Commandes::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $i)
                ->where('etat', 'confirmé')
                ->count();

            $stat_commande_non_confirmer_Month[] =  Commandes::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $i)
                ->where('etat', 'annulé')
                ->count();

            $montant = Commandes::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $i)
                ->where('statut', 'payée')
                ->get()
                ->sum(function ($commande) {
                    return $commande->montant();
                });
            $inscriptionMonth[] = clients::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $i)
                ->count();

            $ventesPerMonth[] = $montant;


            //calcul du profil net de tous les produit
            $stat_commande_non_confirmer_Month[] =  Commandes::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $i)
            ->where('etat', 'annulé')
            ->count();
            $profilNet[] = contenu_commande::whereBetween('created_at', [$date_debut, $date_fin])
                ->whereMonth('created_at', $i)
                ->whereHas('commandes', function ($query) {
                    $query->where('statut', 'payée');
                })
                ->sum('benefice');
        }


        $total_visites = views::whereBetween('created_at', [$date_debut, $date_fin])->count();
        $total_commandes = commandes::whereBetween('created_at', [$date_debut, $date_fin])->where('statut', 'payée')->get(['id']);
        $total_produits = produits::count();
        $totalDesCommandes = 0;
        foreach ($total_commandes as $command) {
            $totalDesCommandes += $command->montant();
        }
        $totalUser = clients::whereBetween('created_at', [$date_debut, $date_fin])->count();


        //get all command group by gouvernorrant collun Asc 
        $top_gouvernorat = [];
        $gouvernorats = commandes::select('gouvernorat', DB::raw('COUNT(*) as count'))
            ->whereBetween('commandes.created_at', [$date_debut, $date_fin])
            ->groupBy('gouvernorat')
            ->orderBy('count', 'desc')
            ->get();
        foreach ($gouvernorats as $top) {
            $top_gouvernorat[] = [
                "nom" => $top->gouvernorat,
                "total" => $top->count,
                "montant" => 0,
            ];
        }



        $commandes = DB::table('commandes')
            ->selectRaw('statut, COUNT(*) as count')
            ->whereBetween('created_at', [$date_debut, $date_fin])
            ->where(function ($query) {
                $query->where('statut', '!=', 'créé')
                    ->orWhere(function ($query) {
                        $query->where('statut', 'créé')
                            ->where('etat', 'confirmé');
                    });
            })
            ->groupBy('statut')
            ->whereIn('statut', ['créé', 'traitement', 'livraison', 'livrée', 'payée', 'planification retour', 'retournée'])
            ->get()
            ->pluck('count', 'statut')
            ->toArray();

        $nombre_total_commande = commandes::whereBetween('created_at', [$date_debut, $date_fin])->count();
        $statistique_commandes_graph = [];
        if ($nombre_total_commande > 0) {
            foreach ($commandes as $key => $coms) {
                $statistique_commandes_graph[] = [
                    "statut" => $key,
                    "valeur" => $coms,
                    "pourcentage" => round((($coms / $nombre_total_commande) * 100), 2),
                ];
            }
        }

        $etat_commandes = commandes::selectRaw('etat, COUNT(*) as count')
            ->whereBetween('created_at', [$date_debut, $date_fin])
            ->groupBy('etat')
            ->whereIn('etat', ['confirmé', 'annulé'])
            ->get()
            ->pluck('count', 'etat')
            ->toArray();
        // Vérifiez si $etat_commandes est défini, sinon définissez-le comme un tableau vide
        $etat_commandes = $etat_commandes ?? [];
        $pourcentage_confirmer = 0;
        $pourcentage_non_confirmer = 0;
        $total_confirme = $etat_commandes['confirmé'] ?? 0;
        $total_non_confirme = $etat_commandes['annulé'] ?? 0;
        $total_commandes = $total_confirme + $total_non_confirme;
        if ($total_commandes != 0) {
            $pourcentage_confirmer = round(($total_confirme / $total_commandes) * 100);
            $pourcentage_non_confirmer = round(($total_non_confirme / $total_commandes) * 100);
        }

        $etat_commandes = [
            'confirmer' => $total_confirme,
            'non-confirmer' => $total_non_confirme,
            'pourcentage_confirmer' => $pourcentage_confirmer,
            'pourcentage_non-confirmer' => $pourcentage_non_confirmer,
        ];


        $json_commandes = '[' . implode(',', [$commandes['créé'] ?? 0, $commandes['traitement'] ?? 0, $commandes['livraison'] ?? 0, $commandes['livré'] ?? 0, $commandes['payée'] ?? 0, $commandes['planification retour'] ?? 0, $commandes['retournée'] ?? 0]) . ']';

        $request->session()->flash('date_debut', $request->input('date_debut'));
        $request->session()->flash('date_fin', $request->input('date_fin'));
     


      //  $notifications = auth()->user()->unreadNotifications;

        return view('admin.index')
            ->with("totalUser", $totalUser)
            ->with('visitsPerMonth', $visitsPerMonth)
            ->with('commandesPerMonth', $commandesPerMonth)
            ->with('ventesPerMonth', $ventesPerMonth)
            ->with('total_visites', $total_visites)
            ->with('total_commandes', $total_commandes)
            ->with('total_produits', $total_produits)
            ->with("statistique_commandes_graph", $statistique_commandes_graph)
            ->with("commandes", $commandes)
            ->with('nombre_total_commande', $nombre_total_commande)
            ->with("json_commandes", $json_commandes)
            ->with('inscriptionMonth', $inscriptionMonth)
            ->with('profilNet', $profilNet)
            ->with('stat_commande_non_confirmer_Month', $stat_commande_non_confirmer_Month)
            ->with('stat_commande_confirmer_Month', $stat_commande_confirmer_Month)
            ->with('etat_commandes', $etat_commandes)
            ->with('top_gouvernorat', $top_gouvernorat)
            ->with('totalDesCommandes', $totalDesCommandes);
            //->with('notifications', $notifications);
    }



    public function update_config(Request $request)
    {
        $send_mail_update_commande = $request->input('send_mail_update_commande') ? 1 : 0;
        $config = config::first();
        $config->send_mail_update_commande = $send_mail_update_commande;
        $config->save();

        return redirect()
            ->route('commandes')
            ->with('success', 'Configuration mise à jour avec succès');
    }



    public function new_commande()
    {
        return view("admin.commandes.ajouter");
    }


    public function add_note(Request $request)
    {
        $id_commande = $request->input('id_commande');
        $note = $request->input("note");

        $commande = commandes::find($id_commande);
        $commande->note = $note;
        if (!$commande) {
            return redirect()
                ->route("commandes")
                ->with("error", "Commande introuvable!");
        }
        $commande->save();
        return redirect()
            ->route("commandes")
            ->with("success", "La note a été ajouté a la commande.");
    }


    public function corbeille()
    {
        return view("admin.produits.corbeille");
    }



    public function export_clients()
    {
        $users = clients::select('nom', 'phone', 'adresse', 'pays', 'gouvernorat')
            ->get();
        return Excel::download(new ExportUser($users), 'users.xlsx');
    }



    public function live_notifications()
    {
        $total = notifications::where("statut", "unread")->count();
        return response()->json(
            [
                'total' => $total
            ]
        );
    }


    public function edit_commande($id)
    {
        $commande = commandes::find($id);
        if (!$commande) {
            $message = "Commande introuvable";
            abort('404', $message);
        }
        if ($commande->statut == 'retournée' && $commande->statut == 'payée'&& $commande->statut == 'traitement') {
           
            $this->sendOrderConfirmationMail($commande);
            return redirect()->route('commandes');
       
        }
        $this->sendOrderConfirmationMail($commande);
        
   
        return view('admin.commandes.edit', compact('commande'));
    }

    public function sendOrderConfirmationMail($commande){
        Mail::to ($commande->email)->send(new OrderChangeStatut($commande));
      }
//////////Categories/////////////
public function category_add()
{
    return view('admin.categories.add');
}

public function categories()
{
    return view('admin.categories.list');
}

public function categories_update($id)
{
    $category = Category::find($id);
    if (!$category) {
        $message = "Category non disponible !";
        abort(404, $message);
    }
    return view('admin.categories.update', compact('category'));
}

///////////////Marques/////////////////////

public function marques()
{
    return view('admin.marques.list');
}


public function service_add()
{
    return view('admin.services.add');
}

public function services()
{
    return view('admin.services.list');
}

public function service_update($id)
{
    $service = Service::find($id);
    if (!$service) {
        $message = "Service non disponible !";
        abort(404, $message);
    }
    return view('admin.services.update', compact('service'));
}

////////////////coupons //////////////////

public function coupons()
{
    $coupons=Coupon::orderBy('id','DESC')->paginate('10');
    return view('admin.coupons.list', compact('coupons'));
}

public function coupon_add(){
    return view('admin.coupons.add');
}

public function coupons_update($id){
    $coupon = Coupons::find($id);
    if (!$coupon) {
        $message = "Coupon non disponible !";
        abort(404, $message);
    }
    return view('admin.coupons.update', compact('coupon'));
}


///////////////Testimonials////////////

public function testimonials()
{
    $testimonials = Testimonial::paginate(10);
    return view('admin.testimonials.list', compact('testimonials') );
}




//////////Produits/////////////////
    public function produit_add()
    {
        return view('admin.produits.add');
    }

    public function produits()
    {
        return view('admin.produits.list');
    }

    public function produits_update($id)
    {
        $produit = produits::find($id);
        if (!$produit) {
            $message = "Produit non disponible !";
            abort(404, $message);
        }
        return view('admin.produits.update', compact('produit'));
    }

    public function ajouterStock(Request $request, $id)
    {
    
          $request->validate([
            'quantite' => 'required|integer|min:1',
        ]);

        /* $pro = produits::findOrFail($id);
      
        $pro->stock = $pro->stock + $request->quantite;
       
        $pro->save();
 */
       
        $produit = produits::findOrFail($id);
        dd($produit);
        $produit->stock = $produit->stock + $request->quantite; 
       // $produit->updated_at = Carbon::now();  // Met à jour la date de modification du produit
        // Ajoute la quantité au stock actuel
        $produit->save();

        //enregistrer lhistorique du stock 
      ////  $historique_stock = new historiques_stock();
       // $historique_stock->quantite = $request->quantite;
      //  $historique_stock->id_produit = $pro->id;
      //  $historique_stock->save();


       
        return redirect()->route('produits', ['id' => $produit->id])
                         ->with('success', 'Stock ajouté avec succès!');
    }
    
    public function historique($id)
    {
        $produit = produits::find($id);
        if (!$produit) {
            $message = "Produit non disponible !";
            abort(404, $message);
        }
        return view('admin.produits.historique', compact('produit'));
    }



    public function commandes()
    {
        return view('admin.commandes.list');
    }

    public function parametres()
    {
        $connexions = historiques_connexion::Orderby("id", "Desc")
            ->where('user_id', Auth::id())
            ->get();

        $ipAddress = request()->ip();
        return view('admin.parametres.index', compact('connexions'));
    }


    public function personnels()
    {
        $personnels = User::where('role', 'personnel')->get();
        return view('admin.personnels.list', compact('personnels'));
    }


    public function details_commande($id)
    {
        $commande = commandes::find($id);
        if (!$commande) {
            $message = "Commande introuvable !";
            abort(404, $message);
        }
        return view('admin.commandes.details', compact('commande'));
    }



    public function promotions($id = null)
    {
        if ($id !== null) {
            $produit = Produits::find($id);
            if (!$produit) {
                abort(404);
            }
        } else {
            $produit = null;
        }
        return view('admin.promotions.index', compact('produit'));
    }


    public function clients()
    {
        $clients = User::where('role', 'client')->get();
        return view('admin.clients.list', compact('clients'));
    }



    public function contact_admin()
    {
        return view('admin.parametres.contact');
    }

    public function delete_personnel($id)
    {
        $user = User::where("id", '=', $id)->first();
        if ($user) {
            $user->delete();
            return redirect()->back()->with('success', 'Personnel supprimé avec succès!');
        }
    }


    public function update_permission(Request $request)
    {

        $selectedPermissions = $request->input('permissions', []);
        $user = User::findOrFail($request->input('id'));
        $user->syncPermissions($selectedPermissions);
        return redirect()
            ->back()
            ->with('success', 'Permissions mises à jour avec succès.');
    }

    public function storageLink(){
        // check if the storage folder already linked;
        if(File::exists(public_path('storage'))){
            // removed the existing symbolic link
            File::delete(public_path('storage'));

            //Regenerate the storage link folder
            try{
                Artisan::call('storage:link');
                request()->session()->flash('success', 'Successfully storage linked.');
                return redirect()->back();
            }
            catch(\Exception $exception){
                request()->session()->flash('error', $exception->getMessage());
                return redirect()->back();
            }
        }
        else{
            try{
                Artisan::call('storage:link');
                request()->session()->flash('success', 'Successfully storage linked.');
                return redirect()->back();
            }
            catch(\Exception $exception){
                request()->session()->flash('error', $exception->getMessage());
                return redirect()->back();
            }
        }
    }
}
