<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ProjectImage;
use Illuminate\Support\Facades\Storage;
class ProjectImageController extends Controller {
    public function destroy(ProjectImage $image) {
        Storage::disk('public')->delete($image->path);
        $image->delete();
        return back()->with('success', 'Gambar berhasil dihapus.');
    }
}