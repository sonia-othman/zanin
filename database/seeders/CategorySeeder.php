<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\SubCategory;
use App\Models\SubCategoryTranslation;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Core Software & Development' => [
                'Kurdish' => 'بەرهەم هێنانی سۆفتوێر و گەشەپێدان',
                'subs' => [
                    ['en' => 'Python', 'ku' => 'پایتۆن'],
                    ['en' => 'JavaScript', 'ku' => 'جاڤاسکریپت'],
                    ['en' => 'Web Development', 'ku' => 'گەشەپێدانی وێب'],
                    ['en' => 'Mobile Development', 'ku' => 'گەشەپێدانی مۆبایل'],
                ],
            ],
            'Web & Internet' => [
                'Kurdish' => 'وێب و ئینتەرنێت',
                'subs' => [
                    ['en' => 'React', 'ku' => 'ریاکت'],
                    ['en' => 'Vue.js', 'ku' => 'ڤیو.جس'],
                    ['en' => 'APIs & Microservices', 'ku' => 'API و مایکروسێرڤیس'],
                ],
            ],
            'Databases & Data' => [
                'Kurdish' => 'داتابەیس و داتا',
                'subs' => [
                    ['en' => 'MySQL', 'ku' => 'مای‌ئس‌کیوئڵ'],
                    ['en' => 'MongoDB', 'ku' => 'مونگو دی‌بی'],
                    ['en' => 'Big Data', 'ku' => 'داتای گەورە'],
                ],
            ],
            'Networking & Infrastructure' => [
                'Kurdish' => 'تۆڕ و بنەماکان',
                'subs' => [
                    ['en' => 'Network Protocols', 'ku' => 'پرۆتۆکۆل تایبەتمەندەکانی تۆڕ'],
                    ['en' => 'Cloud Computing', 'ku' => 'کۆمپیوتەری هەورە'],
                    ['en' => 'DevOps', 'ku' => 'دێڤۆپس'],
                ],
            ],
            'Security & Privacy' => [
                'Kurdish' => 'ئیمنا و تایبەتمەندی',
                'subs' => [
                    ['en' => 'Cryptography', 'ku' => 'کریپتوگرافی'],
                    ['en' => 'Network Security', 'ku' => 'ئیمنا تۆڕ'],
                    ['en' => 'Data Privacy', 'ku' => 'تایبەتمەندی داتا'],
                ],
            ],
            'AI, ML & Emerging Tech' => [
                'Kurdish' => 'زیرەکی ئەرکی، فێرکاری ماشینی، و تەکنەلۆجیای نوێ',
                'subs' => [
                    ['en' => 'Machine Learning', 'ku' => 'فێرکاری ماشینی'],
                    ['en' => 'Artificial Intelligence', 'ku' => 'زیرەکی ئەرکی'],
                    ['en' => 'Blockchain', 'ku' => 'بلاکچەین'],
                ],
            ],
            'Internet of Things (IoT)' => [
                'Kurdish' => 'ئینتەرنێتی شتان',
                'subs' => [
                    ['en' => 'Smart Devices', 'ku' => 'ئامێرە زیرەکان'],
                    ['en' => 'Home Automation', 'ku' => 'ئۆتۆماتیکی خانوو'],
                    ['en' => 'Wearables', 'ku' => 'ئامێرەی بەرەوپێش'],
                ],
            ],
            'Scientific & Data Science' => [
                'Kurdish' => 'زانیاری زانستی و داتاسیانس',
                'subs' => [
                    ['en' => 'Data Analysis', 'ku' => 'هەڵسەنگاندنی داتا'],
                    ['en' => 'Visualization', 'ku' => 'بینینی داتاکان'],
                    ['en' => 'Scientific Computing', 'ku' => 'کۆمپیووتەری زانستی'],
                ],
            ],
            'Software Engineering' => [
                'Kurdish' => 'مهندسی نرم‌افزار',
                'subs' => [
                    ['en' => 'Design Patterns', 'ku' => 'شێوازەکانی دیزاین'],
                    ['en' => 'Testing', 'ku' => 'تاقیکردنەوە'],
                    ['en' => 'Project Management', 'ku' => 'بەرێوەبردنی پرۆژە'],
                ],
            ],
            'Tools & Practices' => [
                'Kurdish' => 'ئامێرەکان و ڕەوشەکان',
                'subs' => [
                    ['en' => 'Git', 'ku' => 'گیت'],
                    ['en' => 'CI/CD', 'ku' => 'سی‌آی/سی‌دی'],
                    ['en' => 'Containers', 'ku' => 'کۆنتەینەرەکان'],
                ],
            ],
            'UI/UX & Design' => [
                'Kurdish' => 'دەربارەی کەرتی بەکارهێنەر و دیزاین',
                'subs' => [
                    ['en' => 'User Experience', 'ku' => 'تاقیکردنی بەکارهێنەر'],
                    ['en' => 'User Interface', 'ku' => 'ڕووکاری بەکارهێنەر'],
                    ['en' => 'Graphic Design', 'ku' => 'دیزاینی گرافیکی'],
                ],
            ],
            'Systems & Admin' => [
                'Kurdish' => 'سیستەم و بەڕێوەبەر',
                'subs' => [
                    ['en' => 'Linux', 'ku' => 'لینۆکس'],
                    ['en' => 'Windows Server', 'ku' => 'وینۆز سێرڤەر'],
                    ['en' => 'Virtualization', 'ku' => 'وێرتوەلازەیشن'],
                ],
            ],
        ];

        foreach ($categories as $categoryName => $data) {
            // Create Category
            $category = Category::firstOrCreate([
                'slug' => Str::slug($categoryName),
            ]);

            // Add translations for category
            foreach (['en' => $categoryName, 'ku' => $data['Kurdish']] as $lang => $name) {
                CategoryTranslation::updateOrCreate(
                    ['category_id' => $category->id, 'language' => $lang],
                    ['name' => $name]
                );
            }

            // Create Subcategories and their translations
            foreach ($data['subs'] as $subcat) {
                $subcategory = SubCategory::firstOrCreate([
                    'slug' => Str::slug($subcat['en']),
                    'category_id' => $category->id,
                ], [
                    'name' => $subcat['en'], // fallback name
                ]);

                foreach (['en', 'ku'] as $lang) {
                    $subcatName = $subcat[$lang] ?? $subcat['en'];

                    SubCategoryTranslation::updateOrCreate(
                        ['sub_category_id' => $subcategory->id, 'language' => $lang],
                        ['name' => $subcatName]
                    );
                }
            }
        }
    }
}
