<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Untuk membuat slug
use Illuminate\Validation\Rule; // Untuk validasi unik

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::latest()->paginate(10); // Ambil kategori terbaru, paginasi 10 per halaman
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            // 'parent_id' => 'nullable|exists:categories,id', // Jika ada kategori parent
            // 'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Jika ada gambar
        ]);

        $category = new Category();
        $category->name = $validatedData['name'];
        $category->slug = Str::slug($validatedData['name']); // Membuat slug dari nama
        $category->description = $validatedData['description'] ?? null;
        // $category->parent_id = $validatedData['parent_id'] ?? null;

        // if ($request->hasFile('image')) {
        //     $imagePath = $request->file('image')->store('categories', 'public');
        //     $category->image = $imagePath;
        // }

        $category->save();

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     * Biasanya tidak terlalu sering dipakai untuk kategori, lebih sering edit.
     */
    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        // $parentCategories = Category::where('id', '!=', $category->id)->get(); // Untuk dropdown parent
        return view('admin.categories.edit', compact('category' /*, 'parentCategories' */));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validatedData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($category->id), // Unik, kecuali untuk dirinya sendiri
            ],
            'description' => 'nullable|string',
            // 'parent_id' => 'nullable|exists:categories,id',
            // 'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $category->name = $validatedData['name'];
        $category->slug = Str::slug($validatedData['name']);
        $category->description = $validatedData['description'] ?? null;
        // $category->parent_id = $validatedData['parent_id'] ?? null;

        // if ($request->hasFile('image')) {
        //     // Hapus gambar lama jika ada dan jika gambar baru diupload
        //     if ($category->image) {
        //         Storage::disk('public')->delete($category->image);
        //     }
        //     $imagePath = $request->file('image')->store('categories', 'public');
        //     $category->image = $imagePath;
        // } elseif ($request->boolean('remove_image')) { // Jika ada checkbox untuk hapus gambar
        //     if ($category->image) {
        //         Storage::disk('public')->delete($category->image);
        //         $category->image = null;
        //     }
        // }

        $category->save();

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Validasi tambahan jika kategori memiliki relasi (misal produk)
        // if ($category->products()->count() > 0) {
        //    return redirect()->route('admin.categories.index')
        //                     ->with('error', 'Kategori tidak dapat dihapus karena memiliki produk terkait.');
        // }

        // Hapus gambar jika ada
        // if ($category->image) {
        //     Storage::disk('public')->delete($category->image);
        // }

        $category->delete();

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Kategori berhasil dihapus.');
    }
}