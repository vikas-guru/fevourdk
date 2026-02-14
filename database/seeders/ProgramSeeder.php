<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            [
                'title' => 'Capacity Building for NGOs',
                'slug' => 'capacity-building-for-ngos',
                'description' => 'Training workshops on governance, fundraising, and project management. Digital transformation support for NGO operations. Skill development programs for grassroots organizations.',
                'content' => '<h3>Our Approach</h3><p>We provide comprehensive capacity building programs designed to strengthen NGOs from the ground up. Our workshops cover essential topics including organizational governance, strategic planning, financial management, and effective fundraising strategies.</p><h3>Key Activities</h3><ul><li>Interactive training workshops</li><li>Digital literacy and transformation support</li><li>One-on-one mentoring sessions</li><li>Peer learning networks</li></ul>',
                'category' => 'Capacity Building',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Women Empowerment Initiatives',
                'slug' => 'women-empowerment-initiatives',
                'description' => 'Livelihood training and entrepreneurship programs for women. Awareness campaigns on gender equality and women\'s rights. Support for self-help groups (SHGs) and microfinance initiatives.',
                'content' => '<h3>Empowering Women</h3><p>Our women empowerment programs focus on creating sustainable change through economic independence, education, and social awareness.</p><h3>Program Components</h3><ul><li>Vocational training programs</li><li>Entrepreneurship development</li><li>Legal rights awareness</li><li>SHG formation and support</li></ul>',
                'category' => 'Women Empowerment',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Education & Child Welfare',
                'slug' => 'education-child-welfare',
                'description' => 'Scholarships and mentorship programs for underprivileged students. Collaboration with schools for better learning opportunities. Child protection initiatives and advocacy for child rights.',
                'content' => '<h3>Educational Support</h3><p>We believe education is the foundation of community development. Our programs ensure every child has access to quality education.</p><h3>Initiatives</h3><ul><li>Scholarship programs</li><li>After-school tutoring</li><li>Learning material distribution</li><li>Child rights advocacy</li></ul>',
                'category' => 'Education & Child Welfare',
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'Health & Well-being Programs',
                'slug' => 'health-well-being-programs',
                'description' => 'Free health check-ups and medical camps in rural areas. Awareness campaigns on nutrition, sanitation, and hygiene. Mental health support programs for vulnerable communities.',
                'content' => '<h3>Healthcare Access</h3><p>We bring healthcare services to underserved communities through regular medical camps and health awareness programs.</p><h3>Services</h3><ul><li>General health check-ups</li><li>Specialist medical camps</li><li>Health education sessions</li><li>Mental health support</li></ul>',
                'category' => 'Health & Well-being',
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'title' => 'Environmental Sustainability Initiatives',
                'slug' => 'environmental-sustainability-initiatives',
                'description' => 'Tree plantation drives and biodiversity conservation programs. Waste management and eco-friendly awareness campaigns. Climate change adaptation projects for sustainable communities.',
                'content' => '<h3>Environmental Conservation</h3><p>Our environmental programs focus on creating sustainable communities through conservation and awareness initiatives.</p><h3>Activities</h3><ul><li>Tree plantation drives</li><li>Waste management training</li><li>Climate awareness campaigns</li><li>Sustainable agriculture promotion</li></ul>',
                'category' => 'Environmental Sustainability',
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'title' => 'Rural Development & Livelihood Support',
                'slug' => 'rural-development-livelihood-support',
                'description' => 'Skill training programs for youth and rural entrepreneurs. Promotion of sustainable agriculture and organic farming. Support for artisans and traditional handicraft industries.',
                'content' => '<h3>Rural Empowerment</h3><p>We focus on strengthening rural economies through skill development, agricultural support, and traditional craft preservation.</p><h3>Program Areas</h3><ul><li>Vocational skill training</li><li>Agricultural development</li><li>Artisan support programs</li><li>Market linkages</li></ul>',
                'category' => 'Rural Development',
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'title' => 'Disaster Management & Relief',
                'slug' => 'disaster-management-relief',
                'description' => 'Immediate response and rehabilitation support for disaster-affected areas. Training communities in disaster preparedness and resilience. Collaboration with local authorities for effective disaster response.',
                'content' => '<h3>Disaster Response</h3><p>We provide rapid response during disasters and work on building community resilience through preparedness training.</p><h3>Response Activities</h3><ul><li>Emergency relief distribution</li><li>Temporary shelter management</li><li>Rehabilitation support</li><li>Disaster preparedness training</li></ul>',
                'category' => 'Disaster Management',
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 7,
            ],
        ];

        foreach ($programs as $program) {
            Program::create($program);
        }
    }
}
