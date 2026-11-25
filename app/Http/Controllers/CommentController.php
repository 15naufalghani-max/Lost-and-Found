<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store a newly created comment on an item.
     */
    public function store(StoreCommentRequest $request, Item $item): RedirectResponse
    {
        $item->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $request->validated()['body'],
        ]);

        return back()->with('status', 'Komentar berhasil ditambahkan.');
    }

    /**
     * Remove a comment if requested by its author or the item's owner.
     */
    public function destroy(Request $request, Comment $comment): RedirectResponse
    {
        $userId = $request->user()->id;
        $isOwner = $comment->user_id === $userId || $comment->item?->user_id === $userId;

        abort_unless($isOwner, 403);

        $comment->delete();

        return back()->with('status', 'Komentar berhasil dihapus.');
    }
}
