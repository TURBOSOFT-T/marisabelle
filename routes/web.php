<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DevelopperController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PackController;
use App\Http\Controllers\BannersController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\favoris_client;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\MyAccountController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\{

    ContactController ,
};
use App\Http\Controllers\panier_client;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LocaleController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//Route::get('/locale/{locale}', [LocaleController::class, 'change'])->name("locale.change");

Route::post('/locale', [LocaleController::class ,'change'])->name("locale.change");
    // CACHE CLEAR ROUTE
    Route::get('cache-clear', function () {
        Artisan::call('optimize:clear');
        request()->session()->flash('success', 'Successfully cache cleared.');
        return redirect()->back();
    })->name('cache.clear');


    // STORAGE LINKED ROUTE
    Route::get('storage-link',[AdminController::class,'storageLink'])->name('storage.link');


Route::get('contact', [ContactController::class, 'contact'])->name("contact");
Route::get('about', [ContactController::class, 'about'])->name("about");
/////Actualités
Route::get('actualites', [EventController::class, 'evenements'])->name("actualites");
Route::get('/details-actualites/{id}/{slug}', [EventController::class, 'details'])->name('details-actualites');
Route::get('recherche', [EventController::class, 'recherche'])->name("recherche");


/////temoignages
Route::resource('testimonial', TestimonialController::class);



Route::resource('contacts', ContactController::class, ['only' => ['create', 'store']]);
Route::get('forgot_password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forgot_password');
Route::get('/confirmation', [HomeController::class, 'confirmation'])->name('confirmation');
Route::get('/logout', [HomeController::class, 'logout']);
Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/print/commande/{id}', [HomeController::class, 'print_commande'])->name('print_commande');

Route::get('/marque/{id}', [HomeController::class, 'produits'])->where('id', '[0-9]+');
//Route::get('/details-produits/{id}', [HomeController::class, 'details'])->name('details-produits');
Route::get('/details-produits/{id}/{slug}', [HomeController::class, 'details'])->name('details-produits');
Route::get('/details-services/{id}/{slug}', [HomeController::class, 'detailsServices'])->name('details-services');


 ///gestion boutique
Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
Route::get('ordres-croissant', [HomeController::class, 'croissant'])->name('ordres.croissant');
Route::post('/shop', [HomeController::class, 'shop']);
Route::get('/category/{id}', [HomeController::class, 'products'])->where('id', '[0-9]+');
Route::get('/decroissant', [HomeController::class, 'decroissant'])
->name('decroissant');
Route::get('/croissant', [HomeController::class, 'croissant'])
->name('croissant');
Route::get('/promotion', [HomeController::class, 'promotion'])
->name('promotion');
//Route::get('/search-product',[HomeController::class,'search_products'])->name('search.products');
Route::get('/sort-by',[HomeController::class,'sort_by'])->name('sort.by');
Route::get('search', [HomeController::class, 'search'])->name("search");



//gestion du panier
Route::get('cart', [panier_client::class, 'cart'])->name('cart');
Route::post('/client/ajouter_au_panier', [panier_client::class, 'add']);
Route::get('/client/count_panier', [panier_client::class, 'count_panier']);
Route::get('/client/mon_panier', [panier_client::class, 'contenu_mon_panier']);
Route::get('/client/delete_produit_au_panier', [panier_client::class, 'delete_produit']);

use App\Http\Controllers\ProductController;

Route::get('addcart/{id}', [ProductController::class, 'addToCart'])->name('addcart');



Route::get('/commander', [CommandeController::class, 'commander'])->name('commander');
Route::post('/order', [CommandeController::class, 'confirmOrder'])->name('order.confirm');
Route::get('/thank-you', [CommandeController::class, 'index'])->name('thank-you');
//Route::get('cart', [CommandeController::class, 'cart'])->name('cart');
Route::delete('/cart/clear', [CommandeController::class, 'clear'])->name('cart.clear');

    Route::post('/savecoupon', [CouponController::class, 'savecoupon'])->name('savecoupon');
    Route::post('/apply-coupon', [CouponController::class, 'applyCoupon'])->name('apply.coupon');



// Utilisateur authentifié
Route::middleware('auth')->group(function () {

    /////////////////Commandes////////////////////////////////////////
  //  Route::post('/order', [CommandeController::class, 'confirmOrder'])->name('order.confirm');
//Route::get('/thank-you', [CommandeController::class, 'index'])->name('thank-you');
    //Route::get('/commander', [CommandeController::class, 'commander'])->name('commander');

    //gestion des favoris
    Route::post('/client/ajouter_favoris', [favoris_client::class, 'add']);
    Route::get('/favories', [MyAccountController::class, 'favories'])->name('favories');

    ///Mon compte
    Route::get('/comptes', [MyAccountController::class, 'comptes'])->name('comptes');
    Route::get('/account', [MyAccountController::class, 'account'])->name('account');

    ///Mon profil
    Route::get('/profile', [MyAccountController::class, 'profile'])->name('profile');





});


Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])
        ->name('dashboard');
    Route::post('/dashboard/filtre', [AdminController::class, 'dashboard'])
        ->name('filtre-dashboard');

    ////////////Les categories/////////////////////////////////////////////////////
    Route::get('/admin/categories', [AdminController::class, 'categories'])
        ->name('categories')
        ->middleware('permission:category_view');
    Route::get('/admin/category/add', [AdminController::class, 'category_add'])
        ->name('category.add')
        ->middleware('permission:category_add');
    Route::get('/admin/category/{id}/update', [AdminController::class, 'categories_update'])
        ->name('categories.update')
        ->middleware('permission:category_edit');


    ///////////////Les services/////////////////////////////////

    Route::get('/admin/services', [AdminController::class, 'services'])
        ->name('services')
        ->middleware('permission:category_view');
    Route::get('/admin/service/add', [AdminController::class, 'service_add'])
        ->name('service.add')
        ->middleware('permission:category_add');
    Route::get('/admin/service/{id}/update', [AdminController::class, 'service_update'])
        ->name('service.update')
        ->middleware('permission:service_edit');

        ///////////////////Les coupons////////////////////////////////////////////////
        Route::get('/admin/coupons', [AdminController::class, 'coupons'])
            ->name('coupons');
            
    Route::post('/savecoupon', [CouponController::class, 'savecoupon'])->name('savecoupon');
    Route::get('/updatecoupon/{id}', [CouponController::class, 'updatecoupon'])->name('updatecoupon');
    Route::delete('/deletecoupon/{id}', [CouponController::class, 'destroy'])->name('coupon.destroy');
    Route::get('/putcoupon/{id}', [CouponController::class, 'putcoupon'])->name('putcoupon');
   
    Route::resource('/coupons', CouponController::class);


       /////////Testimonials///////////////////
       Route::get('/admin/testimonials', [AdminController::class, 'testimonials'])
       ->name('testimonials');
       Route::get('/admin/testimonial/{id}/delete', [AdminController::class, 'testimonial_delete']);
       Route::resource('testimonials', TestimonialController::class);
       Route::get('temoignages/{id}/disapprove', [TestimonialController::class, 'disapprove'])->name('temoignages.disapprove');
       Route::get('temoignages/{id}/approve', [TestimonialController::class, 'approve'])->name('temoignages.approve');
      // Route::get('testimoniale/{id}/destroy', [TestimonialController::class, 'destroy'])->name('testimoniale.destroy');


   // Route::resource('/coupon', CouponController::class);
            
        Route::get('/admin/coupon/add', [AdminController::class, 'coupon_add'])
            ->name('coupon.add');
           
       // Route::get('/admin/coupon/{id}/update', [AdminController::class, 'coupon_update'])
        //    ->name('coupon.update');
         

    ///////////////////Les produits////////////////////////////


    /////////////////////////Les marques//////////////////////////////////////
    Route::get('/admin/marques', [AdminController::class, 'marques'])
        ->name('marques');


//////////////import client/////////////////////////////

Route::post('import', [CustomerController::class, 'importExcelData']);



    ///////////////////les  produits////////////////////////////////////////////////
    Route::prefix('admin')->group(function () {
        Route::get('/produits', [AdminController::class, 'produits'])
            ->name('produits')
            ->middleware('permission:product_view');

        Route::get('/corbeille', [AdminController::class, 'corbeille'])->name('corbeille');
        Route::get('/produit/{id}/update', [AdminController::class, 'produits_update'])
            ->name('produits.update')
            ->middleware('permission:product_edit');

        Route::get('/produit/{id}/historique', [AdminController::class, 'historique'])
            ->name('produits.historique')
            ->middleware('role:admin');

        Route::get('/produit/add', [AdminController::class, 'produit_add'])
            ->name('produit.add')
            ->middleware('permission:product_add');

        Route::get('/commandes', [AdminController::class, 'commandes'])
            ->name('commandes')
            ->middleware('permission:order_view');
        Route::get('/parametres', [AdminController::class, 'parametres'])
            ->name('parametres');

        Route::get('/personnels', [AdminController::class, 'personnels'])
            ->name('personnels')
            ->middleware('role:admin');
        Route::get('/promotions', [AdminController::class, 'promotions'])
            ->name('promotions');
        Route::get('/promotions/{id}', [AdminController::class, 'promotions'])
            ->name('promotions_produit');
        Route::get('/commande/{id}', [AdminController::class, 'details_commande'])
            ->name('details_commande');

            Route::post('/produits/{id}/ajouter-stock', [AdminController::class, 'ajouterStock'])->name('produits.ajouterStock');


      
    });



    
    

    Route::get('clients', [AdminController::class, 'clients'])
        ->name('clients')
        ->middleware('permission:clients_view');
    Route::get('/admin/export/clients', [AdminController::class, 'export_clients'])
        ->name('export_clients')
        ->middleware('permission:clients_view');

    Route::get('contact-admin', [AdminController::class, 'contact_admin'])
        ->name('contact-admin')
        ->middleware('permission:setting_view');
    Route::get('/admin/get_live_notifications', [AdminController::class, 'live_notifications'])
        ->name('live_notifications');

    Route::post('/update-config', [AdminController::class, 'update_config'])
        ->name('update-config');

    Route::get('admin/new_commande', [AdminController::class, 'new_commande'])
        ->name('new_commande')
        ->middleware('permission:order_add');

    Route::post('admin/add_note', [AdminController::class, 'add_note'])
        ->name('add_note')
        ->middleware('permission:order_edit');


    Route::get('/admin/commande/{id}/edit_commande', [AdminController::class, 'edit_commande'])
        ->name('edit_commande')
        ->middleware('permission:order_edit');






    Route::group(['middleware' => 'role:admin'], function () {

        Route::get('/admin/personnel/delete/{id}', [AdminController::class, 'delete_personnel'])
            ->name('delete_personnel');

        Route::post('/admin/update-personnel-permissions', [AdminController::class, 'update_permission'])
            ->name('update-personnel-permissions');

        Route::get('/admin/packs', [PackController::class, 'index'])
            ->name('packs');

        Route::get('/admin/add_packs', [PackController::class, 'create'])
            ->name('add_packs');


        //gestion des routes pour le forumlaire de contact
        Route::get('/admin/admin_contact_form', [AdminController::class, 'admin_contact_form'])
            ->name('admin_contact_form');

        Route::get('/admin/supprimer_messages/{id}', [AdminController::class, 'supprimer_messages'])
            ->name('supprimer_messages');


           /////////////////// events ////////////////////////////////////////
           Route::get('/admin/events', [EventController::class, 'events'])
           ->name('events');
           
           route::resource('events', EventController::class);
           Route::get('calendar', [ EventController::class, 'calendar' ])->name('calendar');
      
       Route::get('/admin/event_update/{id}', [ EventController::class, 'event_update'])
           ->name('event_update');
         

        //getion des banniers
        Route::get('/admin/banner/index', [BannersController::class, 'index'])
            ->name('banner.index');
        Route::get('/admin/banner/{id}', [BannersController::class, 'index_update'])
            ->name('banner.update');
    });




    //reserver au developper
    Route::group(['middleware' => 'developper'], function () {
        Route::get('/admin/developper', [DevelopperController::class, 'developper'])
            ->name('developper');
        Route::get('/admin/add-template', [DevelopperController::class, 'add_template'])
            ->name('add-template');
        Route::get('/admin/edit-template/{id}', [DevelopperController::class, 'edit_template'])
            ->name('edit-template');
        Route::post('/admin/post-template', [DevelopperController::class, 'post_template'])
            ->name('post-template');
        Route::get('/admin/importation-excel', [DevelopperController::class, 'importation_excel'])
            ->name('importation_excel');
    });



});





require __DIR__ . '/auth.php';
