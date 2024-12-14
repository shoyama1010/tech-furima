<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Item;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function store(CommentRequest $request)
    {
        // バリデーション
        // $request->validate([
        //     'content' => 'required|string|max:500',
        //     'item_id' => 'required|exists:items,id',
        // ]);

        // デバッグ用
        if (!auth()->check()) {
            dd('ユーザーがログインしていません');
        }

        if (is_null(auth()->id())) {
            dd('auth()->id()がNULLです');
        }

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
        $item = Item::with('comments.user')->findOrFail($id); 
        // 商品に紐づくコメントとユーザー情報を取得
        return view('items.detail', compact('item'));
    }
}
