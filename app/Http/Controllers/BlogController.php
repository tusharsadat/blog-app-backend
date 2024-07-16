<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    // This method will return all blogs
    public function index()
    {
    }

    // This method will return a single blogs
    public function show()
    {
    }

    // This method will store a blog
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:10',
            'short_des' => 'required',
            'author' => 'required|min:3',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'please fix the error',
                'errors' => $validator->errors(),
            ]);
        };

        $blog = new Blog();
        $blog->title = $request->title;
        $blog->short_des = $request->short_des;
        $blog->description = $request->description;
        $blog->author = $request->author;
        $blog->save();

        return response()->json([
            'status' => true,
            'message' => 'Blog added successfully',
            'data' => $blog,
        ]);
    }

    // This method will update a blog
    public function update()
    {
    }

    // This method will delete a blog
    public function destroy()
    {
    }
}
