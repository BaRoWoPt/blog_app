<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy thử 1 user_id có sẵn trong bảng users, nếu chưa có thì gán mặc định là 1
        $firstUserId = DB::table('users')->value('id') ?? 1;

        for ($i = 1; $i <= 5; $i++) {
            DB::table('blogs')->insert([
                'name'        => 'Tiêu đề bài blog mẫu số ' . $i,
                'author'      => 'Tác giả ' . $i,
                'description' => 'Nội dung chi tiết cho bài blog số ' . $i . '. ' . Str::random(100),
                'thumbnail'   => 'images/blog_' . $i . '.jpg',
                'user_id'     => $firstUserId,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}
