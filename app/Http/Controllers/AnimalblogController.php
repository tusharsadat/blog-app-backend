<?php

namespace App\Http\Controllers;

use App\Models\Animalblog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnimalblogController extends Controller
{
    public function index()
    {
        return Animalblog::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
        }

        $blog = Animalblog::create([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $path,
        ]);

        return response()->json($blog, 201);
    }

    public function show($id)
    {
        return Animalblog::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|max:2048',
        ]);

        $blog = Animalblog::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($blog->image) {
                Storage::disk('public')->delete($blog->image);
            }
            $path = $request->file('image')->store('images', 'public');
            $blog->update([
                'title' => $request->title,
                'content' => $request->content,
                'image' => $path,
            ]);
        } else {
            $blog->update($request->only('title', 'content'));
        }

        return response()->json($blog, 200);
    }

    public function destroy($id)
    {
        $blog = Animalblog::findOrFail($id);

        if ($blog->image) {
            Storage::disk('public')->delete($blog->image);
        }

        $blog->delete();

        return response()->noContent();
    }
}
