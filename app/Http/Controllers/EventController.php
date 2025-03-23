<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    
    // Liste des événements du créateur
    public function index()
    {
        $events = Event::where('user_id', Auth::id())->get();
        return view('createur.index', compact('events'));
    }

    // Afficher le formulaire de création
    public function create()
    {
        return view('createur.create');
    }

    // Enregistrer un nouvel événement
    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'time' => 'required',
        ]);

        Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'location' => $request->location,
            'time' => $request->time,
            'user_id' => Auth::id(), // Associer l'événement au créateur
        ]);

        return redirect()->route('createur.index')->with('success', 'Événement créé avec succès.');
    }

    // Afficher le formulaire de modification
    public function edit(Event $event)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403, "Vous n'êtes pas autorisé à modifier cet événement.");
        }

        return view('createur.edit', compact('event'));
    }

    // Mettre à jour un événement
    public function update(Request $request, Event $event)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403, "Vous n'êtes pas autorisé à modifier cet événement.");
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'time' => 'required',
        ]);

        $event->update($request->all());

        return redirect()->route('createur.index')->with('success', 'Événement mis à jour avec succès.');
    }

    // Supprimer un événement
    public function destroy(Event $event)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403, "Vous n'êtes pas autorisé à supprimer cet événement.");
        }

        $event->delete();

        return redirect()->route('createur.index')->with('success', 'Événement supprimé avec succès.');
    }

    // Permet aux participants de s'inscrire à un événement
    public function participate($id)
    {
        $event = Event::findOrFail($id);
        auth()->user()->eventsParticipated()->attach($event);
        return redirect()->route('participant.dashboard')->with('success', 'Vous êtes inscrit à l\'événement.');
    }

    // Permet aux participants de se désinscrire d'un événement
    public function unparticipate($id)
    {
        $event = Event::findOrFail($id);
        auth()->user()->eventsParticipated()->detach($event);
        return redirect()->route('participant.dashboard')->with('success', 'Vous êtes désinscrit de l\'événement.');
    }
}
