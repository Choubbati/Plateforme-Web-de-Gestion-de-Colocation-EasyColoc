<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use App\Models\Colocation;

class CategoryController extends Controller
{
    public function store(StoreCategoryRequest $request, Colocation $colocation)
    {
        Category::create([
            'name' => $request->name,
            'colocation_id' => $colocation->id,
        ]);

        return back()->with('success', 'Catégorie ajoutée.');
    }

    public function destroy(Category $category)
    {
        // Owner + Member (active) يقدرو يحيدو
        $isActiveMember = $category->colocation->memberships()
            ->where('user_id', auth()->id())
            ->whereNull('left_at')
            ->exists();

        abort_unless($isActiveMember, 403);

        $category->delete();

        return back()->with('success', 'Catégorie supprimée.');
    }
}
