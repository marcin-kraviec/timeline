<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function createEvent(Request $request) {
        $fields = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:30'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'start_date' => ['required', 'date', 'before_or_equal:end_date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'description' => ['required', 'string', 'min:3', 'max:200'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048']
        ], [
            'name.required' => 'Nazwa wydarzenia jest wymagana.',
            'name.string' => 'Nazwa wydarzenia musi być tekstem.',
            'name.min' => 'Nazwa wydarzenia musi mieć co najmniej 3 znaki.',
            'name.max' => 'Nazwa wydarzenia może mieć maksymalnie 30 znaków.',
            'category_id.required' => 'Kategoria jest wymagana.',
            'category_id.integer' => 'Kategoria musi być liczbą całkowitą.',
            'category_id.exists' => 'Podana kategoria nie istnieje.',
            'start_date.required' => 'Data rozpoczęcia jest wymagana.',
            'start_date.date' => 'Data rozpoczęcia musi być prawidłową datą.',
            'start_date.before_or_equal' => 'Data rozpoczęcia musi być wcześniejsza lub równa dacie zakończenia.',
            'end_date.required' => 'Data zakończenia jest wymagana.',
            'end_date.date' => 'Data zakończenia musi być prawidłową datą.',
            'end_date.after_or_equal' => 'Data zakończenia musi być późniejsza lub równa dacie rozpoczęcia.',
            'description.required' => 'Opis wydarzenia jest wymagany.',
            'description.string' => 'Opis wydarzenia musi być tekstem.',
            'description.min' => 'Opis wydarzenia musi mieć co najmniej 3 znaki.',
            'description.max' => 'Opis wydarzenia może mieć maksymalnie 200 znaków.',
            'image.required' => 'Obraz jest wymagany.',
            'image.image' => 'Plik musi być obrazem.',
            'image.mimes' => 'Obraz musi być w formacie jpg, jpeg, lub png.',
            'image.max' => 'Rozmiar obrazu nie może przekraczać 2MB.'
        ]);

        $fields['name'] = strip_tags($fields['name']);
        $fields['category_id'] = strip_tags($fields['category_id']);
        $fields['start_date'] = strip_tags($fields['start_date']);
        $fields['end_date'] = strip_tags($fields['end_date']);
        $fields['description'] = strip_tags($fields['description']);
        $fields['created_by'] = Auth::id();
        $fields['updated_by'] = Auth::id();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('storage', 'public');
            $fields['image'] = $path;
        }

        Event::create($fields);
        return redirect('/')->with('status', 'Wydarzenie zostało pomyślnie utworzone.');
    }

    public function deleteEvent(Event $event) {
        $event->delete();
        return redirect('/')->with('status', 'Wydarzenie zostało pomyślnie usunięte.');
    }

    public function modifyEvent(Event $event, Request $request) {
        $fields = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:30'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'start_date' => ['required', 'date', 'before_or_equal:end_date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'description' => ['required', 'string', 'min:3', 'max:200'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048']
        ], [
            'name.required' => 'Nazwa wydarzenia jest wymagana.',
            'name.string' => 'Nazwa wydarzenia musi być tekstem.',
            'name.min' => 'Nazwa wydarzenia musi mieć co najmniej 3 znaki.',
            'name.max' => 'Nazwa wydarzenia może mieć maksymalnie 30 znaków.',
            'category_id.required' => 'Kategoria jest wymagana.',
            'category_id.integer' => 'Kategoria musi być liczbą całkowitą.',
            'category_id.exists' => 'Podana kategoria nie istnieje.',
            'start_date.required' => 'Data rozpoczęcia jest wymagana.',
            'start_date.date' => 'Data rozpoczęcia musi być prawidłową datą.',
            'start_date.before_or_equal' => 'Data rozpoczęcia musi być wcześniejsza lub równa dacie zakończenia.',
            'end_date.required' => 'Data zakończenia jest wymagana.',
            'end_date.date' => 'Data zakończenia musi być prawidłową datą.',
            'end_date.after_or_equal' => 'Data zakończenia musi być późniejsza lub równa dacie rozpoczęcia.',
            'description.required' => 'Opis wydarzenia jest wymagany.',
            'description.string' => 'Opis wydarzenia musi być tekstem.',
            'description.min' => 'Opis wydarzenia musi mieć co najmniej 3 znaki.',
            'description.max' => 'Opis wydarzenia może mieć maksymalnie 200 znaków.',
            'image.image' => 'Plik musi być obrazem.',
            'image.mimes' => 'Obraz musi być w formacie jpg, jpeg, lub png.',
            'image.max' => 'Rozmiar obrazu nie może przekraczać 2MB.'
        ]);

        $fields['name'] = strip_tags($fields['name']);
        $fields['category_id'] = strip_tags($fields['category_id']);
        $fields['start_date'] = strip_tags($fields['start_date']);
        $fields['end_date'] = strip_tags($fields['end_date']);
        $fields['description'] = strip_tags($fields['description']);
        $fields['updated_by'] = Auth::id();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('storage', 'public');
            $fields['image'] = $path;
        } else {
            $fields['image'] = $event->image;
        }

        $event->update($fields);
        return redirect('/')->with('status', 'Wydarzenie zostało pomyślnie zaktualizowane.');
    }
}
