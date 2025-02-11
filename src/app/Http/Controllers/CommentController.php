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
        // コメントを保存
        Comment::create([
            'user_id' => Auth::id(),
            'item_id' => $request->validated()['item_id'], 
            'content' => $request->validated()['content'],
        ]);

        return redirect()->back()->with('success', 'コメントが投稿されました！');
    }

    public function show($id)
    {
        $item = Item::with('comments.user')->findOrFail($id); 
        
        // 商品に紐づくコメントとユーザー情報を取得
        return view('items.detail', compact('item'));
    }
}
