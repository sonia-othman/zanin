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
        $allCategories = [
            'Emerging Technologies' => [
                'Kurdish' => 'تکنەلۆژی نوێ و پێشکەوتوو',
                'subs' => [
                    ['en' => 'Quantum Computing', 'ku' => 'کۆمپیوتەری کۆانتیومی'],
                    ['en' => 'Edge Computing', 'ku' => 'کۆمپیوتەری سەرچەوانە'],
                    ['en' => '5G Technology', 'ku' => 'تکنەلۆژی ٥G'],
                    ['en' => 'Robotics', 'ku' => 'رۆبۆتیک'],
                    ['en' => 'Augmented Reality', 'ku' => 'راستی زیادکراو'],
                ],
            ],  
          'Core Software & Development' => [
                'Kurdish' => 'بەرهەم هێنانی سۆفتوێر و گەشەپێدان',
                'subs' => [
                    ['en' => 'Python', 'ku' => 'پایتۆن'],
                    ['en' => 'JavaScript', 'ku' => 'جاڤاسکریپت'],
                    ['en' => 'Web Development', 'ku' => 'گەشەپێدانی وێب'],
                    ['en' => 'Mobile Development', 'ku' => 'گەشەپێدانی مۆبایل'],
                    ['en' => 'Programming Fundamentals', 'ku' => 'بنەماکانی پرۆگرامسازی'],
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

            // Other categories grouped logically without duplicates
            'Data Structures & Algorithms' => [
                'Kurdish' => 'پێکهاتەی داتا و ئەلگۆریزم',
                'subs' => [
                    ['en' => 'Arrays', 'ku' => 'ئاڕەیەکان'],
                    ['en' => 'Linked Lists', 'ku' => 'لیستە بەستراوەکان'],
                    ['en' => 'Stacks & Queues', 'ku' => 'ستاک و کیوەکان'],
                    ['en' => 'Trees', 'ku' => 'درەختەکان'],
                    ['en' => 'Graphs', 'ku' => 'گرافەکان'],
                    ['en' => 'Hash Tables', 'ku' => 'خشتە هاشەکان'],
                    ['en' => 'Sorting Algorithms', 'ku' => 'ئەلگۆریزمی ڕیزکردن'],
                    ['en' => 'Search Algorithms', 'ku' => 'ئەلگۆریزمی گەڕان'],
                    ['en' => 'Dynamic Programming', 'ku' => 'پرۆگرامی داینامیک'],
                    ['en' => 'Greedy Algorithms', 'ku' => 'ئەلگۆریزمی چاوچنۆک'],
                ],
            ],
            'Computer Systems' => [
                'Kurdish' => 'سیستەمی کۆمپیوتەر',
                'subs' => [
                    ['en' => 'Operating Systems', 'ku' => 'سیستەمی کارپێکەر'],
                    ['en' => 'Computer Architecture', 'ku' => 'تەلارسازی کۆمپیوتەر'],
                    ['en' => 'Compilers', 'ku' => 'کۆمپایلەرەکان'],
                    ['en' => 'System Programming', 'ku' => 'پرۆگرامسازی سیستەم'],
                    ['en' => 'Distributed Systems', 'ku' => 'سیستەمی دابەشکراو'],
                ],
            ],
            'Mathematics for Programming' => [
                'Kurdish' => 'بیرکاری بۆ پرۆگرامسازی',
                'subs' => [
                    ['en' => 'Discrete Mathematics', 'ku' => 'بیرکاری جیاکراو'],
                    ['en' => 'Linear Algebra', 'ku' => 'ئەلجەبرای هێڵی'],
                    ['en' => 'Statistics & Probability', 'ku' => 'ئامار و ئەگەری'],
                    ['en' => 'Number Theory', 'ku' => 'تیۆری ژمارە'],
                    ['en' => 'Graph Theory', 'ku' => 'تیۆری گراف'],
                ],
            ],
            'Interview Preparation' => [
                'Kurdish' => 'ئامادەکاری چاوپێکەوتن',
                'subs' => [
                    ['en' => 'Coding Interview Questions', 'ku' => 'پرسیارەکانی چاوپێکەوتنی کۆدنووسی'],
                    ['en' => 'System Design Interview', 'ku' => 'چاوپێکەوتنی دیزاینی سیستەم'],
                    ['en' => 'Behavioral Interview', 'ku' => 'چاوپێکەوتنی ڕەفتاری'],
                    ['en' => 'Company-specific Preparation', 'ku' => 'ئامادەکاری تایبەت بە کۆمپانیا'],
                    ['en' => 'Mock Interviews', 'ku' => 'چاوپێکەوتنی نموونەیی'],
                ],
            ],
            'Competitive Programming' => [
                'Kurdish' => 'پرۆگرامسازی ڕکابەری',
                'subs' => [
                    ['en' => 'Contest Platforms', 'ku' => 'پلاتفۆرمەکانی ڕکابەری'],
                    ['en' => 'Problem Solving Techniques', 'ku' => 'تەکنیکەکانی چارەسەری کێشە'],
                    ['en' => 'Contest Strategies', 'ku' => 'ستراتیژەکانی ڕکابەری'],
                    ['en' => 'Mathematical Programming', 'ku' => 'پرۆگرامسازی بیرکاری'],
                ],
            ],
            'Gaming & Game Development' => [
                'Kurdish' => 'یاری و گەشەپێدانی یاری',
                'subs' => [
                    ['en' => 'Game Engines', 'ku' => 'بزوێنەری یاری'],
                    ['en' => 'Unity', 'ku' => 'یونیتی'],
                    ['en' => 'Unreal Engine', 'ku' => 'ئەنریاڵ ئینجین'],
                    ['en' => 'Game Design', 'ku' => 'دیزاینی یاری'],
                    ['en' => 'Virtual Reality', 'ku' => 'راستی مەجازی'],
                    ['en' => 'Augmented Reality', 'ku' => 'راستی زیادکراو'],
                ],
            ],
            'Hardware & Electronics' => [
                'Kurdish' => 'ڕەقەکالا و ئەلیکترۆنیات',
                'subs' => [
                    ['en' => 'Arduino', 'ku' => 'ئاردوینۆ'],
                    ['en' => 'Raspberry Pi', 'ku' => 'ڕاسبێری پای'],
                    ['en' => 'Circuit Design', 'ku' => 'دیزاینی مەداری'],
                    ['en' => 'Embedded Systems', 'ku' => 'سیستەمی نێودراو'],
                    ['en' => 'Microcontrollers', 'ku' => 'مایکرۆکۆنترۆڵەرەکان'],
                ],
            ],
            'Programming Languages' => [
                'Kurdish' => 'زمانەکانی پرۆگرامسازی',
                'subs' => [
                    ['en' => 'Java', 'ku' => 'جاڤا'],
                    ['en' => 'C++', 'ku' => 'سی پلەس پلەس'],
                    ['en' => 'C Programming', 'ku' => 'پرۆگرامسازی سی'],
                    ['en' => 'C#', 'ku' => 'سی شارپ'],
                    ['en' => 'Go (Golang)', 'ku' => 'گۆ (گۆلانگ)'],
                    ['en' => 'Rust', 'ku' => 'ڕەست'],
                    ['en' => 'Swift', 'ku' => 'سویفت'],
                    ['en' => 'Kotlin', 'ku' => 'کۆتلین'],
                    ['en' => 'PHP', 'ku' => 'پی‌ئێچ‌پی'],
                    ['en' => 'Ruby', 'ku' => 'ڕوبی'],
                    ['en' => 'Scala', 'ku' => 'سکالا'],
                    ['en' => 'R Programming', 'ku' => 'پرۆگرامسازی ئاڕ'],
                ],
            ],
            'Academic Computer Science' => [
                'Kurdish' => 'زانستی کۆمپیوتەری ئەکادیمی',
                'subs' => [
                    ['en' => 'Theory of Computation', 'ku' => 'تیۆری کۆمپیووتەشن'],
                    ['en' => 'Formal Languages', 'ku' => 'زمانە فەرمیەکان'],
                    ['en' => 'Automata Theory', 'ku' => 'تیۆری ئۆتۆماتا'],
                    ['en' => 'Computational Geometry', 'ku' => 'ئەندازیاری کۆمپیووتەری'],
                    ['en' => 'Computer Graphics', 'ku' => 'گرافیکی کۆمپیوتەر'],
                    ['en' => 'Human-Computer Interaction', 'ku' => 'کارلێکی مرۆڤ-کۆمپیوتەر'],
                ],
            ],
            'Software Engineering Practices' => [
                'Kurdish' => 'پراکتیسەکانی ئەندازیاری نەرمەواڵە',
                'subs' => [
                    ['en' => 'Agile Methodology', 'ku' => 'میتۆدۆلۆجیای چالاک'],
                    ['en' => 'Scrum', 'ku' => 'سکرۆم'],
                    ['en' => 'Code Review', 'ku' => 'پێداچوونەوەی کۆد'],
                    ['en' => 'Documentation', 'ku' => 'بەڵگەنامەکردن'],
                    ['en' => 'Version Control', 'ku' => 'کۆنترۆڵی وەشان'],
                    ['en' => 'Software Architecture', 'ku' => 'تەلارسازی نەرمەواڵە'],
                    ['en' => 'Requirements Analysis', 'ku' => 'شیکردنەوەی پێداویستیەکان'],
                ],
            ],
            'Digital Marketing & Analytics' => [
                'Kurdish' => 'مارکێتینگی دیجیتاڵ و شیکارانە',
                'subs' => [
                    ['en' => 'SEO (Search Engine Optimization)', 'ku' => 'باشکردنی بزوێنەری گەڕان'],
                    ['en' => 'SEM (Search Engine Marketing)', 'ku' => 'مارکێتینگی بزوێنەری گەڕان'],
                    ['en' => 'Google Analytics', 'ku' => 'گووگڵ ئەنالیتیکس'],
                    ['en' => 'Social Media Marketing', 'ku' => 'مارکێتینگی میدیای کۆمەڵایەتی'],
                    ['en' => 'Content Marketing', 'ku' => 'مارکێتینگی ناوەڕۆک'],
                    ['en' => 'E-commerce', 'ku' => 'بازرگانی ئەلیکترۆنی'],
                    ['en' => 'Digital Advertising', 'ku' => 'ڕێکلامی دیجیتاڵ'],
                ],
            ],
            'Career & Professional Development' => [
                'Kurdish' => 'پیشە و گەشەپێدانی پیشەیی',
                'subs' => [
                    ['en' => 'Resume Building', 'ku' => 'دروستکردنی ڕیزیومە'],
                    ['en' => 'Tech Career Paths', 'ku' => 'ڕێڕەوەکانی پیشەی تەکنەلۆجی'],
                    ['en' => 'Freelancing', 'ku' => 'کاری سەربەخۆ'],
                    ['en' => 'Remote Work', 'ku' => 'کاری دووربەدوور'],
                    ['en' => 'Salary Negotiation', 'ku' => 'گفتوگۆی مووچە'],
                    ['en' => 'Professional Networking', 'ku' => 'تۆڕسازی پیشەیی'],
                    ['en' => 'Skill Development', 'ku' => 'گەشەپێدانی لێهاتووی'],
                ],
            ],
            'Business & Entrepreneurship' => [
                'Kurdish' => 'بازرگانی و بازرگانسازی',
                'subs' => [
                    ['en' => 'Startup Strategies', 'ku' => 'ستراتیژەکانی دەستپێک'],
                    ['en' => 'Tech Business Models', 'ku' => 'مۆدێلەکانی بازرگانی تەکنەلۆجی'],
                    ['en' => 'Digital Transformation', 'ku' => 'گۆڕانکاری دیجیتاڵ'],
                    ['en' => 'Product Management', 'ku' => 'بەڕێوەبردنی بەرهەم'],
                    ['en' => 'Innovation Management', 'ku' => 'بەڕێوەبردنی داهێنان'],
                    ['en' => 'Tech Investment', 'ku' => 'وەبەرهێنانی تەکنەلۆجی'],
                ],
            ],
            
            'Education & Tutorials' => [
                'Kurdish' => 'پەروەردە و فێرکاری',
                'subs' => [
                    ['en' => 'Programming Tutorials', 'ku' => 'فێرکاری پرۆگرامسازی'],
                    ['en' => 'Coding Challenges', 'ku' => 'چەلنجەکانی کۆدنووسی'],
                    ['en' => 'Online Courses', 'ku' => 'کۆرسی ئۆنلاین'],
                    ['en' => 'Certification Guides', 'ku' => 'ڕێنمایی دەرەجەدان'],
                    ['en' => 'Study Plans', 'ku' => 'پلاندانانی خوێندن'],
                ],
            ],
            'Cloud Services' => [
                'Kurdish' => 'خزمەتگوزاریەکانی هەورە',
                'subs' => [
                    ['en' => 'AWS', 'ku' => 'ئێ‌دبلیو ئێس'],
                    ['en' => 'Microsoft Azure', 'ku' => 'مایکروسۆفت ئازور'],
                    ['en' => 'Google Cloud', 'ku' => 'گووگڵ کلۆد'],
                    ['en' => 'Serverless Computing', 'ku' => 'کۆمپیوتەری بێ سێرڤەر'],
                ],
            ],
            'Mobile Technologies' => [
                'Kurdish' => 'تکنەلۆژیەکانی مۆبایل',
                'subs' => [
                    ['en' => 'Android Development', 'ku' => 'گەشەپێدانی ئەندرۆید'],
                    ['en' => 'iOS Development', 'ku' => 'گەشەپێدانی ئایئۆئێس'],
                    ['en' => 'Cross-platform Development', 'ku' => 'گەشەپێدانی سەرهێڵی'],
                    ['en' => 'Flutter', 'ku' => 'فلاتەر'],
                    ['en' => 'React Native', 'ku' => 'ریاکت نیتڤ'],
                ],
            ],
            'Web Design & Development' => [
                'Kurdish' => 'دیزاین و گەشەپێدانی وێب',
                'subs' => [
                    ['en' => 'HTML & CSS', 'ku' => 'ئێچ‌تی‌ئێم‌ئێل و سی‌ئێس‌ئێس'],
                    ['en' => 'Responsive Design', 'ku' => 'دیزاینی وەکوپێوان'],
                    ['en' => 'Accessibility', 'ku' => 'دەسترس‌پێدان'],
                    ['en' => 'Performance Optimization', 'ku' => 'باشکردنی کارایی'],
                ],
            ],
            'Virtual & Augmented Reality' => [
                'Kurdish' => 'راستی مەجازی و زیادکراو',
                'subs' => [
                    ['en' => 'VR Development', 'ku' => 'گەشەپێدانی راستی مەجازی'],
                    ['en' => 'AR Development', 'ku' => 'گەشەپێدانی راستی زیادکراو'],
                    ['en' => 'Mixed Reality', 'ku' => 'راستی تەواو'],
                ],
            ],
            'Automation & Robotics' => [
                'Kurdish' => 'ئۆتۆماتیک و رۆبۆتەکان',
                'subs' => [
                    ['en' => 'Robotics', 'ku' => 'رۆبۆت'],
                    ['en' => 'Automation Tools', 'ku' => 'ئامێرەکانی ئۆتۆماتیک'],
                    ['en' => 'Process Automation', 'ku' => 'ئۆتۆماتیکی پرۆسەکان'],
                ],
            ],
            'Hardware & Embedded Systems' => [
                'Kurdish' => 'ڕەقەکالا و سیستەمی نێودراو',
                'subs' => [
                    ['en' => 'Embedded Systems', 'ku' => 'سیستەمی نێودراو'],
                    ['en' => 'Microcontrollers', 'ku' => 'مایکرۆکۆنترۆڵەرەکان'],
                    ['en' => 'IoT Hardware', 'ku' => 'هاردوەری ئینتەرنێتی شتان'],
                ],
            ],
            'Photography & Video' => [
                'Kurdish' => 'وێنەگرتن و ڤیدیۆ',
                'subs' => [
                    ['en' => 'Photography', 'ku' => 'وێنەگرتن'],
                    ['en' => 'Video Editing', 'ku' => 'دەستکاری ڤیدیۆ'],
                    ['en' => 'Motion Graphics', 'ku' => 'گرافیکی جۆرەکانی بەرز'],
                ],
            ],
            'Creative Arts & Design' => [
                'Kurdish' => 'هەڵسەنگاندنی خەیاڵ و دیزاین',
                'subs' => [
                    ['en' => 'Graphic Design', 'ku' => 'دیزاینی گرافیکی'],
                    ['en' => '3D Modeling', 'ku' => 'مۆدێلی 3D'],
                    ['en' => 'Animation', 'ku' => 'ئەنیماسیون'],
                ],
            ],
            'Writing & Content Creation' => [
                'Kurdish' => 'نووسین و دروستکردنی ناوەرۆک',
                'subs' => [
                    ['en' => 'Technical Writing', 'ku' => 'نووسینی تەکنیکی'],
                    ['en' => 'Blogging', 'ku' => 'بلاگنووسی'],
                    ['en' => 'Copywriting', 'ku' => 'نووسینی کۆپی'],
                ],
            ],
        ];
        

       foreach ($allCategories as $categoryName => $data) {
            // Create Category with slug
           $category = Category::firstOrCreate(
    ['slug' => Str::slug($categoryName)],
    ['created_at' => now(), 'updated_at' => now()]
);


            // Create translations for Category (no slug here)
          CategoryTranslation::firstOrCreate(
    ['category_id' => $category->id, 'language' => 'en'],
    ['name' => $categoryName]
);
CategoryTranslation::firstOrCreate(
    ['category_id' => $category->id, 'language' => 'ku'],
    ['name' => $data['Kurdish']]
);

            // Create SubCategories and their translations
            foreach ($data['subs'] as $sub) {
                $subCategory = SubCategory::firstOrCreate(
    ['slug' => Str::slug($sub['en']), 'category_id' => $category->id],
    ['created_at' => now(), 'updated_at' => now()]
);


               SubCategoryTranslation::firstOrCreate(
    ['sub_category_id' => $subCategory->id, 'language' => 'en'],
    ['name' => $sub['en']]
);
SubCategoryTranslation::firstOrCreate(
    ['sub_category_id' => $subCategory->id, 'language' => 'ku'],
    ['name' => $sub['ku']]
);

            }
        }
    }
}