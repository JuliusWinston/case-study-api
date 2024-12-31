<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function index(Request $request) {
        $author = $request->query('author');
        $category = $request->query('category');
        $source = $request->query('source');
        $published_at = $request->query('published_at');
        $pageSize = $request->query('per_page', 10);

        $query = Article::query();

        $query->when($author, function ($q) use ($author) {
            $q->orWhere('author', 'like', "%{$author}%");
        });

        $query->when($category, function ($q) use ($category) {
            $q->orWhere('category', 'like', "%{$category}%");
        });

        $query->when($source, function ($q) use ($source) {
            $q->orWhere('source', 'like', "%{$source}%");
        });

        $query->when($published_at, function ($q) use ($published_at) {
            $q->orWhere('published_at', $published_at);
        });

        $articles = $query->paginate($pageSize);

        if ($articles->isNotEmpty()) {
            return response()->json([
                'message' => 'Articles retrieved successfully',
                'data' => ArticleResource::collection($articles),
                'total_results' => $articles->total(),
                'page' => $articles->currentPage(),
                'pageSize' => $articles->perPage(),
            ], 200);
        } else {
            return response()->json(['message' => 'No records found!'], 200);
        }
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'source' => 'required',
            'author' => 'required',
            'category' => 'required',
            'thumbnail' => 'required',
            'content' => 'required',
            'published_at' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'All fields required',
                'error' => $validator->messages(),
            ], 422);
        }

        $article = Article::create([
            'title' => $request->title,
            'source' => $request->source,
            'author' => $request->author,
            'thumbnail' => $request->thumbnail,
            'category' => $request->category,
            'content' => $request->content,
            'published_at' => $request->published_at,
        ]);

        return response()->json([
            'message' => 'article created successfully',
            'data' => new ArticleResource($article)
        ], 201);
    }

    public function update(Request $request, Article $article) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'source' => 'required',
            'author' => 'required',
            'category' => 'required',
            'thumbnail' => 'required',
            'content' => 'required',
            'published_at' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'All fields required',
                'error' => $validator->messages(),
            ], 422);
        }

        $article->update([
            'title' => $request->title,
            'source' => $request->source,
            'author' => $request->author,
            'category' => $request->category,
            'thumbnail' => $request->thumbnail,
            'content' => $request->content,
            'published_at' => $request->published_at,
        ]);

        return response()->json([
            'message' => 'article updated successfully',
            'data' => new ArticleResource($article)
        ], 201);
    }

    public function show(Article $article) {
        return new ArticleResource($article);
    }

    public function destroy(Article $article) {
        $article->delete();
        return response()->json([
            'message' => 'Article deleted successfully!',
        ], 200);
    }
}
