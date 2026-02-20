<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ProjectImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('category')->latest()->paginate(10);
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.projects.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Simpan hasil validasi ke variabel $validated
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'project_date' => 'required|date',
            'description' => 'required|string',
            'images' => 'required|array|min:1', // minimal 1 gambar
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240', // max 10MB per file
            'featured_image' => 'required|integer', // index gambar utama
        ]);

        // Buat data proyek
        $projectData = [
            'title' => $validated['title'],
            'category_id' => $validated['category_id'],
            'project_date' => $validated['project_date'],
            'description' => $validated['description'],
        ];

        $project = Project::create($projectData);

        // Simpan semua gambar
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('project-images', 'public');

                $project->images()->create([
                    'path' => $path,
                    'is_featured' => ($index == $validated['featured_image']),
                ]);
            }
        }

        return redirect()->route('admin.projects.index')->with('success', 'Proyek berhasil ditambahkan.');
    }


    public function show(Project $project)
    {
        $project->load('category', 'images');
        return view('admin.projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $categories = Category::all();
        // Eager load gambar yang sudah ada
        $project->load('images');
        return view('admin.projects.edit', compact('project', 'categories'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'project_date' => 'required|date',
            'description' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
            'featured_image' => 'nullable|integer',
            'existing_featured_image' => 'nullable|exists:project_images,id',
        ]);

        // Update data teks proyek
        $project->update([
            'title' => $validated['title'],
            'category_id' => $validated['category_id'],
            'project_date' => $validated['project_date'],
            'description' => $validated['description'],
        ]);

        // Reset semua gambar lama menjadi 'not featured'
        $project->images()->update(['is_featured' => false]);

        // Atur gambar LAMA yang dipilih sebagai featured (jika ada)
        if ($request->filled('existing_featured_image')) {
            ProjectImage::find($request->input('existing_featured_image'))->update(['is_featured' => true]);
        }

        // Upload gambar BARU
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('project-images', 'public');
                $isFeatured = ($request->filled('featured_image_index') && $index == $request->input('featured_image_index'));

                // Jika ada gambar baru yang di-set sebagai featured,
                // maka pilihan 'existing_featured_image' akan diabaikan.
                if ($isFeatured) {
                    $project->images()->update(['is_featured' => false]);
                }

                $project->images()->create([
                    'path' => $path,
                    'is_featured' => $isFeatured
                ]);
            }
        } elseif ($project->images()->where('is_featured', true)->count() === 0 && $project->images()->count() > 0) {
            // Jika tidak ada gambar baru & tidak ada featured yg dipilih, jadikan gambar pertama sebagai featured
            $project->images()->first()->update(['is_featured' => true]);
        }

        return redirect()->route('admin.projects.index')->with('success', 'Proyek berhasil diperbarui.');
    }

}
