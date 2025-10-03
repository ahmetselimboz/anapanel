<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plugin;
use Carbon\Carbon;

class PluginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plugins = [
            [
                'name' => 'SEO Optimizer Pro',
                'description' => 'Advanced SEO optimization plugin with keyword analysis, meta tag management, and sitemap generation.',
                'version' => '2.1.0',
                'author' => 'Ershaber Team',
                'author_url' => 'https://ershaber.com',
                'plugin_url' => 'https://ershaber.com/plugins/seo-optimizer-pro',
                'documentation_url' => 'https://ershaber.com/docs/seo-optimizer-pro',
                'license' => 'MIT',
                'license_url' => 'https://opensource.org/licenses/MIT',
                'requirements' => 'PHP 8.0+, Laravel 10+, MySQL 5.7+',
                'minimum_php_version' => '8.0',
                'minimum_laravel_version' => '10.0',
                'changelog' => "v2.1.0 - Added keyword density analysis\nv2.0.0 - Complete rewrite with new UI\nv1.5.0 - Added sitemap generation",
                'is_installed' => true,
                'is_active' => true,
                'installed_at' => Carbon::now()->subDays(30),
                'activated_at' => Carbon::now()->subDays(30),
                'download_count' => 1250,
                'rating' => 4.8,
                'rating_count' => 89,
                'settings' => [
                    'auto_generate_meta' => true,
                    'keyword_density_threshold' => 2.5,
                    'sitemap_frequency' => 'daily',
                    'google_analytics_id' => 'GA-123456789'
                ]
            ],
            [
                'name' => 'Social Media Auto Share',
                'description' => 'Automatically share your posts to Facebook, Twitter, LinkedIn, and Instagram with customizable templates.',
                'version' => '1.8.5',
                'author' => 'Ershaber Team',
                'author_url' => 'https://ershaber.com',
                'plugin_url' => 'https://ershaber.com/plugins/social-auto-share',
                'documentation_url' => 'https://ershaber.com/docs/social-auto-share',
                'license' => 'GPL v3',
                'license_url' => 'https://www.gnu.org/licenses/gpl-3.0.html',
                'requirements' => 'PHP 8.0+, Laravel 10+, cURL extension',
                'minimum_php_version' => '8.0',
                'minimum_laravel_version' => '10.0',
                'changelog' => "v1.8.5 - Fixed Instagram API issues\nv1.8.0 - Added LinkedIn support\nv1.7.0 - Added custom templates",
                'is_installed' => true,
                'is_active' => false,
                'installed_at' => Carbon::now()->subDays(15),
                'activated_at' => null,
                'download_count' => 890,
                'rating' => 4.2,
                'rating_count' => 45,
                'settings' => [
                    'facebook_enabled' => true,
                    'twitter_enabled' => true,
                    'linkedin_enabled' => false,
                    'instagram_enabled' => false,
                    'auto_share_delay' => 5
                ]
            ],
            [
                'name' => 'Advanced Analytics Dashboard',
                'description' => 'Comprehensive analytics dashboard with real-time visitor tracking, conversion analysis, and custom reports.',
                'version' => '3.2.1',
                'author' => 'Ershaber Team',
                'author_url' => 'https://ershaber.com',
                'plugin_url' => 'https://ershaber.com/plugins/analytics-dashboard',
                'documentation_url' => 'https://ershaber.com/docs/analytics-dashboard',
                'license' => 'MIT',
                'license_url' => 'https://opensource.org/licenses/MIT',
                'requirements' => 'PHP 8.1+, Laravel 10+, Redis (optional)',
                'minimum_php_version' => '8.1',
                'minimum_laravel_version' => '10.0',
                'changelog' => "v3.2.1 - Performance improvements\nv3.2.0 - Added real-time tracking\nv3.1.0 - Added custom reports",
                'is_installed' => false,
                'is_active' => false,
                'installed_at' => null,
                'activated_at' => null,
                'download_count' => 2100,
                'rating' => 4.9,
                'rating_count' => 156,
                'settings' => [
                    'track_page_views' => true,
                    'track_user_behavior' => true,
                    'real_time_tracking' => false,
                    'data_retention_days' => 90
                ]
            ],
            [
                'name' => 'Auto Backup & Restore',
                'description' => 'Automated database and file backup system with cloud storage support and one-click restore functionality.',
                'version' => '1.5.2',
                'author' => 'Ershaber Team',
                'author_url' => 'https://ershaber.com',
                'plugin_url' => 'https://ershaber.com/plugins/auto-backup',
                'documentation_url' => 'https://ershaber.com/docs/auto-backup',
                'license' => 'GPL v2',
                'license_url' => 'https://www.gnu.org/licenses/gpl-2.0.html',
                'requirements' => 'PHP 8.0+, Laravel 10+, 100MB free space',
                'minimum_php_version' => '8.0',
                'minimum_laravel_version' => '10.0',
                'changelog' => "v1.5.2 - Fixed cloud storage issues\nv1.5.0 - Added cloud storage support\nv1.4.0 - Added incremental backups",
                'is_installed' => false,
                'is_active' => false,
                'installed_at' => null,
                'activated_at' => null,
                'download_count' => 750,
                'rating' => 4.6,
                'rating_count' => 67,
                'settings' => [
                    'backup_frequency' => 'daily',
                    'backup_retention' => 30,
                    'cloud_storage_enabled' => false,
                    'compress_backups' => true
                ]
            ],
            [
                'name' => 'Content Scheduler',
                'description' => 'Schedule posts, articles, and content for automatic publishing with advanced timing options.',
                'version' => '2.0.0',
                'author' => 'Ershaber Team',
                'author_url' => 'https://ershaber.com',
                'plugin_url' => 'https://ershaber.com/plugins/content-scheduler',
                'documentation_url' => 'https://ershaber.com/docs/content-scheduler',
                'license' => 'MIT',
                'license_url' => 'https://opensource.org/licenses/MIT',
                'requirements' => 'PHP 8.0+, Laravel 10+, Queue system',
                'minimum_php_version' => '8.0',
                'minimum_laravel_version' => '10.0',
                'changelog' => "v2.0.0 - Complete rewrite with new UI\nv1.8.0 - Added recurring schedules\nv1.7.0 - Added bulk scheduling",
                'is_installed' => true,
                'is_active' => true,
                'installed_at' => Carbon::now()->subDays(7),
                'activated_at' => Carbon::now()->subDays(7),
                'download_count' => 1800,
                'rating' => 4.7,
                'rating_count' => 123,
                'settings' => [
                    'default_publish_time' => '09:00',
                    'timezone' => 'Europe/Istanbul',
                    'auto_social_share' => true,
                    'notification_email' => 'admin@ershaber.com'
                ]
            ],
            [
                'name' => 'Multi-Language Support',
                'description' => 'Add multi-language support to your website with automatic translation and language detection.',
                'version' => '1.3.4',
                'author' => 'Ershaber Team',
                'author_url' => 'https://ershaber.com',
                'plugin_url' => 'https://ershaber.com/plugins/multi-language',
                'documentation_url' => 'https://ershaber.com/docs/multi-language',
                'license' => 'MIT',
                'license_url' => 'https://opensource.org/licenses/MIT',
                'requirements' => 'PHP 8.0+, Laravel 10+, Translation files',
                'minimum_php_version' => '8.0',
                'minimum_laravel_version' => '10.0',
                'changelog' => "v1.3.4 - Fixed translation caching\nv1.3.0 - Added automatic translation\nv1.2.0 - Added language detection",
                'is_installed' => false,
                'is_active' => false,
                'installed_at' => null,
                'activated_at' => null,
                'download_count' => 950,
                'rating' => 4.4,
                'rating_count' => 78,
                'settings' => [
                    'default_language' => 'tr',
                    'supported_languages' => ['tr', 'en', 'de'],
                    'auto_translate' => false,
                    'show_language_switcher' => true
                ]
            ]
        ];

        foreach ($plugins as $pluginData) {
            Plugin::create($pluginData);
        }

        $this->command->info('Plugin seeder completed successfully!');
    }
}
