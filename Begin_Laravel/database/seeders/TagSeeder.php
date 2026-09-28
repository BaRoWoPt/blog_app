<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Thể Thao Điện Tử', 'Gym', 'Văn Hoá', 'Xã Hội', 'Công Nghệ', 'Giải Trí'] as $name) {
            $slug = Str::slug($name);

            Tag::updateOrCreate(
                ['slug' => $slug],
                ['name' => $name],
            );
        }
    }
}
