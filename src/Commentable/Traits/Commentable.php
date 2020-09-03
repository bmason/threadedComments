<?php namespace samk369\Commentable\Traits;

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
 * @author     samk369
 * @license    MIT
 * @copyright  (c) samk369
 */

use samk369\Commentable\Models\Comment;

trait Commentable
{
    /**
     * Get all of the model's comments.
     *
     * @return Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
