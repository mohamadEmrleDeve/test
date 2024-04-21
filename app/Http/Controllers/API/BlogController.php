<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginatRequest;
use App\Http\Traits\imageTrait;
use App\Models\Blog;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class BlogController extends Controller
{
    use imageTrait;
    public function index(PaginatRequest $request)
    {
        if(isset($request->search)) {
            $paginate = Blog::where('title','like', '%' . $request->search . '%')->paginate(10);
            $nextPageUrl = $paginate->nextPageUrl();
            $data = $paginate->map(function ($blog) {
                return [
                    'id'            => $blog->id,
                    'title'         => $blog->title,
                    'description'   => $blog->description,
                    'image'         => '/uploads/Blogs/'.$blog->image,
                    'contact'       => $blog->contact,
                ];
            });
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $data,
                'next_page_url' => $nextPageUrl,
                'total' => $paginate->count(),
                'currentPage' => $paginate->currentPage(),
                'lastPage' => $paginate->lastPage(),
                'perPage' => $paginate->perPage(),
            ]);
        }
        if($request->paginate) {
            $paginate = Blog::paginate($request->paginate);
            $nextPageUrl = $paginate->nextPageUrl();
            $data = $paginate->map(function ($blog) {
                return [
                    'id'            => $blog->id,
                    'title'         => $blog->title,
                    'description'   => $blog->description,
                    'image'         => '/uploads/Blogs/'.$blog->image,
                    'contact'       => $blog->contact,
                ];
            });
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $data,
                'next_page_url' => $nextPageUrl,
                'total' => $paginate->count(),
                'currentPage' => $paginate->currentPage(),
                'lastPage' => $paginate->lastPage(),
                'perPage' => $paginate->perPage(),
            ]);
        } else {
            $paginate = Blog::paginate(10);
            $nextPageUrl = $paginate->nextPageUrl();
            $data = $paginate->map(function ($blog) {
                return [
                    'id'            => $blog->id,
                    'title'         => $blog->title,
                    'description'   => $blog->description,
                    'image'         => '/uploads/Blogs/'.$blog->image,
                    'contact'       => $blog->contact,
                ];
            });
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $data,
                'next_page_url' => $nextPageUrl,
                'total' => $paginate->count(),
                'currentPage' => $paginate->currentPage(),
                'lastPage' => $paginate->lastPage(),
                'perPage' => $paginate->perPage(),
            ]);
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'image'       => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'contact'     => 'required'
        ]);

        // Begin the database transaction
        DB::beginTransaction();

        $imageName = $this->saveImage($validatedData['image'],'uploads/Blogs');
        $fileName = $validatedData['title'];
        file_put_contents(public_path('uploads/Pages/' . $fileName . '.php'), $validatedData['contact']);
        // Save the filename in the database along with other data
        $blog = Blog::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'image' => $imageName,
            'contact' => $validatedData['contact']
        ]);

        // Commit the transaction if everything is successful
        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Blogs stored successfully',
            'id'      => $blog->id
        ]);
    }
    public function show($id)
    {
        $data = [];
        $tag = [];
        $record = Blog::find($id);
        $tags = BlogTag::whereHas('blog')->where('blog_id',$id)->get();
        foreach($tags as $row)
        {
            $tag[] = [
                'id'   => $row->id,
                'name' => $row->name,
            ];
        }
        $data [] = [
            'id' => $record->id,
            'title'       => $record->title,
            'description' => $record->description,
            'contact'     => $record->contact,
            'image'       => 'uploads/Blogs/'.$record->image,
            'tags'        => $tag
        ];
        return response()->json([
            'success' => true,
            'message' => 'Blog found',
            'data'    => $data
        ]);
    }
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'contact' => 'nullable|string',
        ]);

       $record = Blog::find($id);
        if(request()->hasFile('image')) {
            File::delete('uploads/Blogs/'.$record->image);
        }
        if(isset($request->image)) {
            $data['image'] = $this->saveImage($request->image,'uploads/Blogs');
        }
        $record->update([
            'title'         => $request->title ?? $record->title,
            'description'   => $request->description ?? $record->description,
            'image'         => $data['image'] ?? $record->image,
            'contact'       => $request->contact ?? $record->contact,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Blog updated successfully',
        ]);
    }


    public function delete($id)
    {
        $record = Blog::findOrFail($id);
        $path = 'uploads/Blogs/'.$record->image;
        if(File::exists($path)) {
            File::delete('uploads/Blogs/'.$record->image);
        }
        $blogTag = BlogTag::where('id',$record->id)->get();
        foreach($blogTag as $row) {
            $row->delete();
        }
        $record->delete();
        return response()->json([
            'success' => true,
            'message' => 'Blog deleted successfully',
        ]);
    }
}
