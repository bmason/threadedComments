<?php namespace BMason\ThreadedComments\Models;

use App\Models\Model
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



class Comment extends Model
{
    /**
    * Fillable fields for a comment
    *
    * @return array
    */
   protected $fillable = ['root_id', 'root_type', 'text','commentable_id','commentable_type','user_id', 'published'];
   protected $dates = ['created_at', 'updated_at'];

    /**
     * Determine if the comment has children.
     *
     * @return bool
     */
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }



    /**
     * Get the user that created the comment.
     *
     * @param  $configKey  string
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user($configKey = 'auth.providers.users.model')
    {
        return $this->belongsTo(config()->get($configKey));
    }


    /**
     * answer an array of all direct replies to root and their reply trees
     *
     * @param   $rootId number   id of root object  Post, Issue etc.
     *          $rootType  string   full model name e.g. 'App\Model\Post'
     * @return array of Comments, reply tree with the most recently updated comment first
     * each comment contains its immediate replies, posting user, and a 'latestUpdate' of its tree
     */

	public static function allNestedRepliesTo($rootId, $rootType)
	{	

		$allReplies = self::where('root_id', $rootId)->where('root_type', $rootType)->with('user')->get()->toArray(); 
		
		foreach ($allReplies as &$e) 
			$e['poly_type'] = Comment::class;   
		
		$roots = array_filter($allReplies, function ($e) {
			return $e['commentable_type'] == $e['root_type'];
		});
		
		$nonRoots = array_diff_key($allReplies, $roots );

		
		foreach($roots as &$e) 
			self::nestReplies($e, $nonRoots);  
		
		usort($roots, function ($a, $b) {  //most recent tree first
			return $b['latestUpdate'] <=> $a['latestUpdate'];
		});
		
		return $roots;
	}	
	

   //for each recursion, gather one level of replies
	protected static function nestReplies(&$root, &$nonRoots) 
	{
	   
		$root['replies']  = array_filter($nonRoots, function ($e) use($root) {
			return $e['commentable_id'] == $root['id'];
		});
		
		$nonRoots = array_diff_key($nonRoots, $root['replies'] );
		
		
		$root['latestUpdate'] = $root['updated_at'];		
		
		foreach($root['replies']  as &$e) {
			self::nestReplies($e, $nonRoots);			
			
			$root['latestUpdate'] = max ($root['latestUpdate'], $e['latestUpdate']);

		}
		
		usort($root['replies'] , function ($a, $b) {
			return $b['latestUpdate'] <=> $a['latestUpdate'];
		});		
		
		return $root;
	   
	}	

    
    /**
     * count comments matching criteria 
     *
     * @param  $root_type  string|array|'all'  full root name e.g. 'App\Models\Post', if 'all'' return all roots
     *         $since timeStamp oldest updated date
     *         $sortBy  'count'|'latest' sort by
     *         $limit  maximum number to return
     *         $exclude array [[<full model name>, [<ids>]]] e.g. [['App\Models\Post', [5,1117]], ['App\Models\Issue', [10499, 37]]]
     * @return array of objects containing the id and model along with a count the the timestamp of the latest
     * 
     */
    public static function topRepliesFor($root_type='all', $since=null, $orderBy='count', $limit=10, $exclude=null)
    { 
        $whereClause = '';

        if ($root_type != 'all') {
            if(is_array($root_type)) 
                $whereClause = 'root_type in ("'.  implode('","', $root_type) . '")';
            else
                $whereClause = "root_type = '$root_type'";
        }

        if ($exclude) {

             foreach ($exclude as $e) {
                 if ($whereClause) $whereClause .= ' AND ';      
                 $ids = implode(',', $e[1]);          
                 $whereClause .= "(root_type != '$e[0]' OR root_id not in ($ids)) ";
             }
        }

        
        if ($since) {
            if ($whereClause) $whereClause .= ' AND ';
            $whereClause .= ' updated_at >= "' . date('Y-m-d H:i:s', $since) . '" ';
        }

         // '\' is an escape - so double escape, one for the parser and one for the match
        if ($whereClause) $whereClause = ' WHERE ' . str_replace("\\", '\\\\', $whereClause); 

        return DB::select("SELECT root_id, root_type, count(1) as count, MAX(updated_at) as latest FROM `comments` $whereClause GROUP BY root_type, root_id ORDER BY $orderBy DESC LIMIT $limit");   

    }




}
