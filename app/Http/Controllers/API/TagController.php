<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BlogTag;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $data = [];
        $tags = Tag::all();
        foreach($tags as $row)
        {
            $data [] = [
                'id'   => $row->id,
                'name' => $row->name,
            ];
        }
        return response()->json([
            'success' => true,
            'mes'     => 'All Tags',
            'data'    => $data,
        ]);
    }
    public function store(Request $request)
    {
        $validationData = $request->validate([
            'name'    => 'required',
            'blog_id' => 'required'
        ]);
        $tag = Tag::where('name',$validationData['name'])->first();
        if(!isset($tag)) {
            $store = Tag::create([
                'name' => $validationData['name']
            ]);
            if($store) {
                $blogTag = BlogTag::create([
                    'blog_id' => $validationData['blog_id'],
                    'tag_id'  => $store->id
                ]);
            }
            return response()->json([
                'success' => true,
                'mes'     => 'Store Tag Successfully'
            ]); 
        } else {
            $blogTag = BlogTag::create([
                'blog_id' => $validationData['blog_id'],
                'tag_id'  => $tag->id
            ]);
            return response()->json([
                'success' => true,
                'mes'     => 'Store Blog Tag Successfully'
            ]); 
        }
    }
    public function delete($id)
    {
        $record = Tag::find($id);
        $blogTag = BlogTag::where('tag_id',$record->id)->get();
        foreach($blogTag as $row) {
            $row->delete();
        }
        $record->delete();
        return response()->json([
            'success' => true,
            'mes'     => 'Delete Tag Successfully'
        ]);
    }
}
