<?php


namespace StatisticLv\AdminPanel\Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


use StatisticLv\AdminPanel\Models\AdminUser;
use StatisticLv\AdminPanel\Models\News;
use StatisticLv\AdminPanel\Models\Menu;
use StatisticLv\AdminPanel\Models\MenuItem;
use StatisticLv\AdminPanel\Models\Page;

class DemoDataSeeder extends Seeder
{


        use WithoutModelEvents;
        
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        $admin = AdminUser::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'is_active' => true,
            ]
        );

        // Create demo news articles
        $newsData = [
            [
                'title' => 'Welcome to Your New Website',
                'slug' => 'welcome-to-your-new-website',
                'excerpt' => 'We are excited to launch our new website with improved features and design.',
                'content' => '<p>We are thrilled to announce the launch of our brand new website! After months of hard work and dedication, we are delighted to officially announce the launch of our new website.</p><p>The new website offers a fresh new design, improved functionality and better user experience. We have worked hard to ensure that the site is easy to navigate and provides all the information you need about our products and services.</p><p>Thank you for your continued support, and we look forward to serving you better through our new online platform.</p>',
                'status' => 'published',
                'published_at' => now(),
                'author_id' => $admin->id,
            ],
            [
                'title' => 'Introducing Our New Features',
                'slug' => 'introducing-our-new-features',
                'excerpt' => 'Discover the latest features we have added to enhance your experience.',
                'content' => '<p>We are constantly working to improve our services and add new features that make your experience better. Today, we are excited to introduce several new features that we believe will significantly enhance your experience.</p><h3>Enhanced User Interface</h3><p>Our new interface is cleaner, faster, and more intuitive. We have redesigned every element with your needs in mind.</p><h3>Improved Performance</h3><p>The website now loads faster and performs better across all devices.</p><h3>New Content Management</h3><p>We have added powerful content management capabilities that make it easier to find what you are looking for.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(1),
                'author_id' => $admin->id,
            ],
            [
                'title' => 'Tips for Getting Started',
                'slug' => 'tips-for-getting-started',
                'excerpt' => 'Here are some helpful tips to help you get the most out of our platform.',
                'content' => '<p>Getting started with our platform is easy! Here are some tips to help you make the most of all the features we offer:</p><ol><li><strong>Explore the Dashboard:</strong> Take a few minutes to familiarize yourself with the admin dashboard. All the tools you need are organized into easy-to-find sections.</li><li><strong>Create Your First Page:</strong> Start by creating a simple page. This will help you understand how our page builder works.</li><li><strong>Customize Your Menu:</strong> Use the menu manager to create a navigation structure that works for your site.</li><li><strong>Publish Content:</strong> Once you are comfortable with the basics, start publishing news articles and pages.</li></ol><p>Remember, we are here to help! If you have any questions, do not hesitate to reach out.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(2),
                'author_id' => $admin->id,
            ],
        ];

        foreach ($newsData as $newsItem) {
            News::firstOrCreate(
                ['slug' => $newsItem['slug']],
                $newsItem
            );
        }

        // Create demo pages
        $pagesData = [
            [
                'title' => 'About Us',
                'slug' => 'about',
                'content' => '<h1>About Us</h1><p>Welcome to our website! We are dedicated to providing you with the best service possible.</p><h2>Our Mission</h2><p>Our mission is to deliver high-quality content and services that meet your needs. We believe in innovation, integrity, and customer satisfaction.</p><h2>Our Team</h2><p>We have a passionate team of professionals who work tirelessly to ensure your experience is exceptional. Each member brings unique skills and expertise to help us achieve our goals.</p><h2>Why Choose Us?</h2><ul><li>Years of experience in the industry</li><li>Commitment to quality and excellence</li><li>Customer-first approach</li><li>Innovative solutions</li></ul>',
                'excerpt' => 'Learn more about our company, mission, and team.',
                'template' => 'default',
                'meta_title' => 'About Us - Learn More About Our Company',
                'meta_description' => 'Discover who we are, what we do, and why we are passionate about serving you.',
                'is_published' => true,
                'order' => 1,
                'author_id' => $admin->id,
            ],
            [
                'title' => 'Services',
                'slug' => 'services',
                'content' => '<h1>Our Services</h1><p>We offer a comprehensive range of services designed to meet your needs.</p><h2>Web Development</h2><p>Our web development team creates beautiful, functional websites that help your business grow online.</p><h2>Content Management</h2><p>We provide powerful content management solutions that make it easy to update and maintain your website.</p><h2>Design Services</h2><p>Our designers create stunning visuals that capture your brands essence and engage your audience.</p><h2>Support & Maintenance</h2><p>We offer ongoing support and maintenance to ensure your website runs smoothly.</p>',
                'excerpt' => 'Explore our range of professional services.',
                'template' => 'sidebar',
                'meta_title' => 'Our Services - What We Offer',
                'meta_description' => 'Discover the full range of professional services we provide to help your business succeed.',
                'is_published' => true,
                'order' => 2,
                'author_id' => $admin->id,
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact',
                'content' => '<h1>Get in Touch</h1><p>We would love to hear from you! Whether you have a question, feedback, or just want to say hello, feel free to reach out.</p><h2>Contact Information</h2><p><strong>Email:</strong> info@example.com<br><strong>Phone:</strong> (555) 123-4567<br><strong>Address:</strong> 123 Main Street, City, State 12345</p><h2>Business Hours</h2><p>Monday - Friday: 9:00 AM - 5:00 PM<br>Saturday: 10:00 AM - 2:00 PM<br>Sunday: Closed</p><p>We typically respond to all inquiries within 24 hours during business days.</p>',
                'excerpt' => 'Contact us for any inquiries or support.',
                'template' => 'default',
                'meta_title' => 'Contact Us - Get in Touch',
                'meta_description' => 'Reach out to us with your questions, feedback, or inquiries. We are here to help!',
                'is_published' => true,
                'order' => 3,
                'author_id' => $admin->id,
            ],
        ];

        foreach ($pagesData as $pageData) {
            Page::firstOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );
        }

        // Create main menu
        $mainMenu = Menu::firstOrCreate(
            ['slug' => 'main-menu'],
            [
                'name' => 'Main Menu',
                'location' => 'main',
                'is_active' => true,
            ]
        );

        // Create menu items
        $menuItems = [
            [
                'menu_id' => $mainMenu->id,
                'title' => 'Home',
                'url' => '/',
                'order' => 1,
            ],
            [
                'menu_id' => $mainMenu->id,
                'title' => 'News',
                'url' => '/news',
                'order' => 2,
            ],
            [
                'menu_id' => $mainMenu->id,
                'title' => 'About',
                'url' => '/about',
                'order' => 3,
            ],
            [
                'menu_id' => $mainMenu->id,
                'title' => 'Services',
                'url' => '/services',
                'order' => 4,
            ],
            [
                'menu_id' => $mainMenu->id,
                'title' => 'Contact',
                'url' => '/contact',
                'order' => 5,
            ],
        ];

        foreach ($menuItems as $item) {
            MenuItem::firstOrCreate(
                [
                    'menu_id' => $item['menu_id'],
                    'title' => $item['title'],
                ],
                $item
            );
        }

        // Create footer menu
        $footerMenu = Menu::firstOrCreate(
            ['slug' => 'footer-menu'],
            [
                'name' => 'Footer Menu',
                'location' => 'footer',
                'is_active' => true,
            ]
        );

        $footerMenuItems = [
            [
                'menu_id' => $footerMenu->id,
                'title' => 'About',
                'url' => '/about',
                'order' => 1,
            ],
            [
                'menu_id' => $footerMenu->id,
                'title' => 'Services',
                'url' => '/services',
                'order' => 2,
            ],
            [
                'menu_id' => $footerMenu->id,
                'title' => 'Contact',
                'url' => '/contact',
                'order' => 3,
            ],
        ];

        foreach ($footerMenuItems as $item) {
            MenuItem::firstOrCreate(
                [
                    'menu_id' => $item['menu_id'],
                    'title' => $item['title'],
                ],
                $item
            );
        }
    }
}
