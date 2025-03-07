<?php

namespace App\Http\Controllers;

use App\Models\{Event, config};
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Requests\Front\SearchRequest;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{

    public function events()
{
    $events = Event::paginate(10); 
    return view('admin.evenements.list', compact('events') );
}

public function evenements(){
    $events = Event::paginate(10); 
    $lastevents = Event::latest()->take(8)->get();
    return view('front.evenements.index', compact('events', 'lastevents') );
}

public function details($id){
    $event =Event:: findOrFail($id);
    $configs= config::all();
    $lastevents = Event::latest()->take(8)->get();
    return view('front.evenements.details', compact('event','configs','lastevents'));
}

public function recherche(SearchRequest $request)
{
    $search = $request->search;
    $lastevents = Event::latest()->take(8)->get();
  
    $events = Event::where('titre', 'like', '%'.$search.'%')
      ->orWhere('description', 'like', '%'.$search.'%')
        ->paginate(10);
    $titre = __('Actualité nont trouvée avec la recherche: ') . '<strong>' . $search . '</strong>';
 
    return view('front.evenements.index', compact('events', 'titre', 'lastevents'));
}
    public function destroy($id)
    {
     $event=   Event::find($id);

     if ($event) {
        // Supprimer l'image si elle existe
        if($event->image ?? ' '){
            Storage::disk('public')->delete($event->image ?? ' '); 
        }

        // Supprimer le event
        $event->delete();

     
    return redirect()->back()
    ->with('success', 'Event supprimé avec succès, ainsi que son image.');
    } else {
        return redirect()->back()('error', 'event non trouvé.');
    }
    }

    
    public function event_update($id){

        $event = Event::find($id);
       if (!$event) {
            $message = "Actualité non disponible !";
            abort(404, $message);
        } 
        
     //  dd($event);
        return view('admin.evenements.update', compact('event'));
    }

      
    public function update(Request $request, $id)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
           // 'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
           'image' => 'nullable|image|max:4048',
            'titre' => 'required|string|max:255',
            

            
        ], [
           'image.mimes' => 'Le format de l\'image doit être jpeg, png, jpg ou gif.',
           'image.max' => 'La taille de l\'image ne doit pas dépasser 2MB.',
           'titre.required' => 'Le titre est requis.',
           'titre.max' => 'Le titre ne doit pas dépasser 255 caractères.',
        
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Trouver le sponsor
        $sponsor = Event::findOrFail($id);

        // Traitement de l'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si nécessaire
            if ($sponsor->image) {
                Storage::delete($sponsor->image);
            }

           
            $path = $request->file('image')->store('evenments', 'public');
            $sponsor->image = $path;
        }

        
        $sponsor->titre = $request->input('titre');
        $sponsor->description = $request->input('description');

      
        $sponsor->save();

        return redirect()->back()->with('success', 'Evènement mis à jour avec succès !');
    
    }
    public function update1(UpdateEventRequest $request, $id)
    {
        // Récupérer l'événement existant
        $event = Event::findOrFail($id); // Utilisation de `findOrFail` pour gérer les erreurs si l'événement n'existe pas.
    
        // Mettre à jour les champs de l'événement
        $event->titre = $request->input('titre');
        $event->description = $request->input('description');
    
        // Vérifier si une nouvelle image a été téléchargée
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($event->image) {
                // Suppression de l'ancienne image depuis le disque 'public'
                Storage::disk('public')->delete('images/' . $event->image);

               
            }
    
            // Télécharger la nouvelle image
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            
            // Sauvegarde de l'image dans le répertoire 'public/images' via Storage
            $imagePath = $image->storeAs('events', $imageName, 'public'); // Utilisation de 'public' pour indiquer que le fichier est dans le stockage public
    
            // Mise à jour du nom de l'image dans la base de données
            $event->image = basename($imagePath); // Assurez-vous d'utiliser seulement le nom du fichier (sans le chemin)
        }
    
        // Sauvegarder les modifications dans la base de données
        $event->save();
    
        // Retourner à la page précédente avec un message de succès
        return redirect()->back()->with('success', 'Actualité mise à jour avec succès !');
    }
    
}



