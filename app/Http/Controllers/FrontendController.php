<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Category;
use App\Models\User;
use App\Models\ContactMessage;
use App\Models\TeamMember;
use App\Events\NewContactMessageSent;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail; // Penting untuk mengirim email
use App\Mail\NewContactMessage;

class FrontendController extends Controller
{
    public function home()
    {
        $useContainer = false;
        $latestProjects = Project::with('category')->latest()->take(3)->get();
        $settings = \App\Models\SiteSetting::allAsArray();
        return view('frontend.home', compact('latestProjects', 'settings'));
    }

    public function aboutUs()
    {
        $teamMembers = TeamMember::orderBy('created_at')->get();
        return view('frontend.about_us', compact('teamMembers')); // pastikan nama view benar
    }

    public function projects(Request $request)
    {
        $query = Project::with('category')->latest();

        // Gunakan method input() dan filled() yang lebih bersih dan aman
        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        if ($request->filled('search')) {
            // Pastikan nama kolom di sini (misal: 'name') sesuai dengan tabel 'projects' Anda
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        // Gunakan withQueryString() agar filter tetap ada saat paginasi
        $projects = $query->paginate(9)->withQueryString();

        $categories = Category::all();

        return view('frontend.projects.index', compact('projects', 'categories'));
    }

    public function projectDetail(Project $project)
    {
        $project->load('category', 'images'); // Pastikan 'images' di-load
        return view('frontend.projects.show', compact('project'));
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    public function sendContactMessage(Request $request)
    {
        // 1. Validasi data dari form
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);
        $message = ContactMessage::create($validated);
        $adminEmail = 'asthatunggalmakmur@gmail.com';
        try {
            Mail::to($adminEmail)->send(new NewContactMessage($message));
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email kontak: ' . $e->getMessage());
        }
        return redirect()->route('contact.form')->with('success', 'Pesan Anda telah berhasil terkirim. Kami akan segera merespons.');
    }


    public function price()
    {
        return view('frontend.price');
    }
}
