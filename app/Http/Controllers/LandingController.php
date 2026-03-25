<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function landing()
    {
        $categories = Categories::with([
            'sub_categories.items.item_documents.upload'
        ])->get();

        $path = public_path('backend/landing/slider');
        $images = File::files($path);

        return view('landing',  compact('categories', 'images'));
    }

    public function privacypolicy()
    {
        return view('privacy_policy');
    }
}
