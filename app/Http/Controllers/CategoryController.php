<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function createCategory(Request $request) {
        $fields = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:20'],
            'color' => ['required', 'string', 'min:3', 'max:20']
        ], [
            'name.required' => 'Nazwa kategorii jest wymagana.',
            'name.string' => 'Nazwa kategorii musi być tekstem.',
            'name.min' => 'Nazwa kategorii musi mieć co najmniej 3 znaki.',
            'name.max' => 'Nazwa kategorii może mieć maksymalnie 20 znaków.',
            'color.required' => 'Kolor jest wymagany.',
            'color.string' => 'Kolor musi być tekstem.',
            'color.min' => 'Kolor musi mieć co najmniej 3 znaki.',
            'color.max' => 'Kolor może mieć maksymalnie 20 znaków.'
        ]);

        $fields['name'] = strip_tags($fields['name']);
        $fields['color'] = strip_tags($fields['color']);

        Category::create($fields);
        return redirect('/')->with('status', 'Kategoria została pomyślnie utworzona.');
    }

    public function deleteCategory(Category $category) {
        $category->delete();
        return redirect('/')->with('status', 'Kategoria została pomyślnie usunięta.');
    }

    public function modifyCategory(Category $category, Request $request) {
        $fields = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:20'],
            'color' => ['required', 'string', 'min:3', 'max:20'],
        ], [
            'name.required' => 'Nazwa kategorii jest wymagana.',
            'name.string' => 'Nazwa kategorii musi być tekstem.',
            'name.min' => 'Nazwa kategorii musi mieć co najmniej 3 znaki.',
            'name.max' => 'Nazwa kategorii może mieć maksymalnie 20 znaków.',
            'color.required' => 'Kolor jest wymagany.',
            'color.string' => 'Kolor musi być tekstem.',
            'color.min' => 'Kolor musi mieć co najmniej 3 znaki.',
            'color.max' => 'Kolor może mieć maksymalnie 20 znaków.'
        ]);

        $fields['name'] = strip_tags($fields['name']);
        $fields['color'] = strip_tags($fields['color']);

        $category->update($fields);
        return redirect('/')->with('status', 'Kategoria została pomyślnie zaktualizowana.');
    }
}
