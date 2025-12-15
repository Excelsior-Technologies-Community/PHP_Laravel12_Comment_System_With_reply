<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'parent_id',
        'body'
    ];

    // User who wrote the comment
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Parent comment (for replies)
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    // Child comments (replies)
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    // Check if comment is a reply
    public function isReply(): bool
    {
        return !is_null($this->parent_id);
    }
}