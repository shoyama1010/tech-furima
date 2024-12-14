<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Item;

class CommentController extends Controller
{
    public function store(Request $request)
    {
            if (!auth()->check()) {
                return redirect()->route('login')->with('error', 'コメントを投稿するにはログインが必要です。');
            }
        
        // バリデーション
        $request->validate([
            'content' => 'required|string|max:500',
            'item_id' => 'required|exists:items,id',
        ]);

        // コメントを保存
        Comment::create([
            'content' => $request->input('content'),
            'item_id' => $request->input('item_id'),
            'user_id' => auth()->id(), // ログインユーザーID
        ]);

        return redirect()->back()->with('success', 'コメントが投稿されました！');
    }

    public function show($id)
    {
        $item = Item::with('comments.user')->findOrFail($id); // 商品に紐づくコメントとユーザー情報を取得
        return view('items.detail', compact('item'));
    }
}
