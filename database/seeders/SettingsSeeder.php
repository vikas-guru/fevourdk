<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // General Settings
        Setting::setValue('site_name', 'FEVOURD-K', 'text', 'general', true);
        Setting::setValue('site_tagline', 'Voluntary Action | Karnataka NGO Hub', 'text', 'general', true);
        Setting::setValue('contact_email', 'fevourd.karnataka@gmail.com', 'text', 'general', true);
        Setting::setValue('contact_phone', '+91 94484 64171', 'text', 'general', true);
        Setting::setValue('contact_address', 'FEVOURD-K #37, 2nd Floor, 6th Cross, 1st Main, Chamarajapete, Bengaluru, Karnataka -560018', 'text', 'general', true);
        Setting::setValue('contact_phone_alt', '+91 74831 59735', 'text', 'general', true);

        // Theme Settings (extracted from original Fevourd-K website)
        Setting::setValue('primary_color', '#2563eb', 'color', 'theme', true);
        Setting::setValue('secondary_color', '#64748b', 'color', 'theme', true);
        Setting::setValue('accent_color', '#10b981', 'color', 'theme', true);
        Setting::setValue('background_color', '#ffffff', 'color', 'theme', true);
        Setting::setValue('text_color', '#1f2937', 'color', 'theme', true);
        Setting::setValue('header_style', 'modern', 'text', 'theme', true);
        Setting::setValue('footer_style', 'comprehensive', 'text', 'theme', true);
        Setting::setValue('button_style', 'rounded', 'text', 'theme', true);
        Setting::setValue('font_family', 'Inter', 'text', 'theme', true);

        // Branding Settings
        Setting::setValue('logo_url', '/images/fevourd-k-logo.png', 'text', 'branding', true);
        Setting::setValue('favicon_url', '/favicon.ico', 'text', 'branding', true);
        Setting::setValue('org_name', 'FEVOURD-K', 'text', 'branding', true);
        Setting::setValue('org_description', 'An apex body of voluntary organisations in Karnataka, committed to strengthening developmental indices through social, economic, and cultural empowerment of deprived communities.', 'text', 'branding', true);
        Setting::setValue('org_mission', 'Collective voice of voluntary organizations in support of marginalized communities in their determined pursuit for dignity, social justice and sustainable development', 'text', 'branding', true);
        Setting::setValue('org_vision', 'Ensure solidarity among voluntary organizations for collective voice and action on issues that affect the sector. Strengthen voluntary organizations to help them better accomplish their missions. Promote a robust civil society to protect democratic values and to advance human progress.', 'text', 'branding', true);

        // Social Media Settings
        Setting::setValue('facebook_url', 'https://www.facebook.com/fevourd-k', 'text', 'social', true);
        Setting::setValue('twitter_url', 'https://twitter.com/fevourd-k', 'text', 'social', true);
        Setting::setValue('linkedin_url', 'https://www.linkedin.com/company/fevourd-k', 'text', 'social', true);
        Setting::setValue('instagram_url', 'https://www.instagram.com/fevourd-k', 'text', 'social', true);
        Setting::setValue('youtube_url', 'https://www.youtube.com/fevourd-k', 'text', 'social', true);

        // SEO Settings
        Setting::setValue('meta_title', 'FEVOURD-K – Voluntary Action | Karnataka NGO Hub', 'text', 'seo', true);
        Setting::setValue('meta_description', 'Discover how fevourd-k empowers over 800 voluntary organizations across Karnataka. Join fevourd-k for sustainable development and vibrant democracy.', 'text', 'seo', true);
        Setting::setValue('meta_keywords', 'FEVOURD-K, voluntary action, Karnataka, NGO hub, social development, community empowerment, sustainable development', 'text', 'seo', true);
        Setting::setValue('og_image', '/images/fevourd-k-og-image.jpg', 'text', 'seo', true);

        // Feature Flags
        Setting::setValue('enable_donations', true, 'boolean', 'features', false);
        Setting::setValue('enable_volunteer_registration', true, 'boolean', 'features', false);
        Setting::setValue('enable_newsletter', true, 'boolean', 'features', false);
        Setting::setValue('enable_events', true, 'boolean', 'features', false);
        Setting::setValue('enable_gallery', true, 'boolean', 'features', false);
        Setting::setValue('enable_blog', false, 'boolean', 'features', false);
        Setting::setValue('enable_multilingual', false, 'boolean', 'features', false);

        // Contact Settings
        Setting::setValue('contact_form_enabled', true, 'boolean', 'contact', false);
        Setting::setValue('contact_form_email', 'fevourd.karnataka@gmail.com', 'text', 'contact', false);
        Setting::setValue('auto_reply_enabled', false, 'boolean', 'contact', false);
        Setting::setValue('auto_reply_subject', 'Thank you for contacting FEVOURD-K', 'text', 'contact', false);
        Setting::setValue('auto_reply_message', 'We have received your message and will get back to you soon.', 'text', 'contact', false);

        // Analytics Settings
        Setting::setValue('google_analytics_enabled', false, 'boolean', 'analytics', false);
        Setting::setValue('google_analytics_id', '', 'text', 'analytics', false);
        Setting::setValue('facebook_pixel_enabled', false, 'boolean', 'analytics', false);
        Setting::setValue('facebook_pixel_id', '', 'text', 'analytics', false);

        // Legal Settings
        Setting::setValue('privacy_policy_url', '/privacy', 'text', 'legal', true);
        Setting::setValue('terms_url', '/terms', 'text', 'legal', true);
        Setting::setValue('accessibility_url', '/accessibility', 'text', 'legal', true);
        Setting::setValue('legal_status_url', '/legal-status', 'text', 'legal', true);
    }
}
