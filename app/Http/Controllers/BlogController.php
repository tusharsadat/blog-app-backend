<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    // This method will return all blogs
    public function index(Request $request)
    {
        $blogs = Blog::orderBy('created_at', 'DESC');

        if (!empty($request->keyword)) {
            $blogs = $blogs->where('title', 'like', '%' . $request->keyword . '%');
        }

        $blogs = $blogs->get();

        return response()->json([
            'status' => true,
            'data' => $blogs
        ]);
    }

    // This method will return a single blogs
    public function show($id)
    {
        $blog = Blog::find($id);

        if ($blog == null) {
            return response()->json([
                'status' => false,
                'message' => 'Blog not found',
            ]);
        }

        $blog['date'] = \Carbon\Carbon::parse($blog->created_at)->format('d M, Y');

        return response()->json([
            'status' => true,
            'data' => $blog,
        ]);
    }

    // This method will store a blog
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:10',
            'short_des' => 'required',
            //'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000',
            'author' => 'required|min:3',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'please fix the error',
                'errors' => $validator->errors(),
            ]);
        };

        // $img = $request->image;
        // $img_name = hexdec(uniqid()) . '.' . $img->getClientOriginalExtension();
        // $request->image->move(public_path('upload'), $img_name);

        $blog = new Blog();
        $blog->title = $request->title;
        $blog->short_des = $request->short_des;
        $blog->description = $request->description;
        //$blog->image = $img_name;
        $blog->author = $request->author;
        $blog->save();

        // Save Image Here
        $tempImage = TempImage::find($request->image_id);

        if ($tempImage != null) {

            $imageExtArray = explode('.', $tempImage->name);
            $ext = last($imageExtArray);
            $imageName = time() . '-' . $blog->id . '.' . $ext;

            $blog->image = $imageName;
            $blog->save();

            $sourcePath = public_path('uploads/temp/' . $tempImage->name);
            $destPath = public_path('uploads/blogs/' . $imageName);

            File::copy($sourcePath, $destPath);
        }


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
