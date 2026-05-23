<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Item;
use App\Http\Requests\CommentRequest;
use Illuminate\Support\Facades\Auth; // Authファサードをインポート

class CommentController extends Controller
{  
    public function store(CommentRequest $request)
    {
        $validated = $request->validated();
        
        // コメントを保存
        Comment::create([
            'user_id' => auth()->id(),
            'item_id' => $validated['item_id'],
            'content' => $validated['content'],
        ]);

        return redirect()
        ->route('items.detail', $validated['item_id'])
        ->with('success', 'コメントが投稿されました！');
    }

    public function show($id)
    {
        $item = Item::with('comments.user')->findOrFail($id); 
        
        // 商品に紐づくコメントとユーザー情報を取得
        return view('items.detail', compact('item'));
    }
}
