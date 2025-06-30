<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->latest()->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $iconValue = null;

        // Handle image upload
        if ($request->hasFile('icon')) {
            $iconValue = $request->file('icon')->store('categories', 'public');
        }

        Category::create([
            'name' => $validated['name'],
            'icon' => $iconValue,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'تم إضافة الفئة بنجاح');
    }

    public function show(Category $category)
    {
        $category->load('products');
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $iconValue = $category->icon; // Keep existing icon by default

        // Handle image upload
        if ($request->hasFile('icon')) {
            // Delete old image if it exists
            if ($category->icon && Storage::disk('public')->exists($category->icon)) {
                Storage::disk('public')->delete($category->icon);
            }
            $iconValue = $request->file('icon')->store('categories', 'public');
        }

        $category->update([
            'name' => $validated['name'],
            'icon' => $iconValue,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'تم تحديث الفئة بنجاح');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'لا يمكن حذف الفئة لوجود منتجات مرتبطة بها');
        }

        // Delete associated image if it exists
        if ($category->icon && Storage::disk('public')->exists($category->icon)) {
            Storage::disk('public')->delete($category->icon);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'تم حذف الفئة بنجاح');
    }
}
