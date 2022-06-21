<?php namespace BMason\ThreadedComments\Traits;

/**
 * Part of the Laravel-Commentable package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the MIT License.
 *
 * This source file is subject to the MIT License that is
 * bundled with this package in the LICENSE file.
 * It is also available at the following URL: http://opensource.org/licenses/MIT
 *
 * @version    1.0.0
 * @author     BMason
 * @license    MIT
 */

use BMason\ThreadedComments\Models\Comment;

trait ThreadedComment
{
    /**
     * Get the first level of the model's comments.
     *
     * @return Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }


    /**
     * Get all of the model's comments nested under their parent.
     *
     * @return array of comments
     * see BMason\ThreadedComments\Models\Comment
     */    
    public function threadedComments() {
        return Comment::allNestedRepliesTo($this->id, get_class($this));
    }

    }
}
