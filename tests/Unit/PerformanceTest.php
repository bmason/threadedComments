<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Comment;


    /**
     * Setup for extreme test.
     * Each iteration generates 1,000 trees of ten nested comments.
     * @return void
     */
   function extreme_setup()
    {

        $id = 50000;  //root id,  high so test comments can be easily removed
        $rootModels = ['App\Models\Issue', 'App\Models\Post'];

        for ($i = 0; $i<1000; $i++) {
            $rootModel = $rootModels[$i % 2];
            foreach (range(1,4) as $j)
                $rootModel = $rootModels[$i % 2];
                $newComment = Comment::create([
                    'text'=> "test $j    $i $id",
                    'commentable_id'=> $id,
                    'commentable_type' => $rootModel,
                    'root_id' => $id,
                    'root_type' => $rootModel,
                    'user_id' => 1,               
                ]);
                foreach (range(1,3) as $j)    
                $newComment = Comment::create([
                    'text'=> "test 4 $j  $i $id",
                    'commentable_id'=> $newComment->id,
                    'commentable_type' => Comment::class,
                    'root_id' => $id,
                    'root_type' => $rootModel,
                    'user_id' => 1,
                ]);
            foreach (range(1,3) as $j)    
                $newComment = Comment::create([
                    'text'=> "test 4 3 $j $i a $id",
                    'commentable_id'=> $newComment->id,
                    'commentable_type' => Comment::class,
                    'root_id' => $id,
                    'root_type' => $rootModel,
                    'user_id' => 1,
                ]);           
                $id++;
        }

    }

