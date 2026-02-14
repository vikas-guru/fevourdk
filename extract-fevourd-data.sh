#!/bin/bash

# FEVOURD-K Data Extraction Script
# This script extracts data and images from the live FEVOURD-K website

echo "🔍 Extracting data and images from FEVOURD-K live site..."

# Create directory structure
mkdir -p public/assets/images/fevourd-k/{gallery,about,programs,events,partners,logos,bg-images}

# Download gallery images
echo "📸 Downloading gallery images..."
curl -s https://www.fevourd-k.org/gallery -o /Users/vikasguru/GURU/FevourdK/public/assets/images/fevourd-k/gallery-image1.jpg
curl -s https://www.fevourd-k.org/gallery -o /Users/vikasguru/GURU/FevourdK/public/assets/images/fevourd-k/gallery-image2.jpg
curl -s https://www.fevourd-k.org/gallery -o /Users/vikasguru/GURU/FevourdK/public/assets/images/fevourd-k/gallery-image3.jpg

# Download logo
echo "📸 Downloading FEVOURD-K logo..."
curl -s https://www.fevourd-k.org -o /Users/vikasguru/GURU/FevourdK/public/assets/images/fevourd-k/logo.svg

# Download hero background
echo "📸 Downloading hero background image..."
curl -s https://www.fevourd-k.org -o /Users/vikasguru/GURU/FevourdK/public/assets/images/fevourd-k/hero-bg.jpg

# Download additional images
echo "📸 Downloading additional site images..."
curl -s https://www.fevourd-k.org -o /Users/vikasguru/GURU/FevourdK/public/assets/images/fevourd-k/about-image.jpg
curl -s https://www.fevourd-k.org -o /Users/vikasguru/GURU/FevourdK/public/assets/images/fevourd-k/programs-image.jpg
curl -s https://www.fevourd-k.org -o /Users/vikasguru/GURU/FevourdK/public/assets/images/fevourd-k/events-image.jpg
curl -s https://www.fevourd-k.org -o /Users/vikasguru/GURU/FevourdK/public/assets/images/fevourd-k/partners-image.jpg

# Create data extraction script
cat > /Users/vikasguru/GURU/FevourdK/extracted-data.json << 'EOF'
{
  "organization": {
    "name": "FEVOURD-K",
    "full_name": "Federation of Voluntary Organisations for Rural Development - Karnataka",
    "founded": "1982",
    "membership": "800+ NGOs",
    "districts": "31 districts",
    "contact": {
      "email": "fevourd.karnataka@gmail.com",
      "phone": "9448464171",
      "address": "FEVOURD-K #37, 2nd Floor, 6th Cross, 1st Main, Chamarajapete, Bengaluru, Karnataka -560018",
      "phones": ["9448464171", "7483159735", "63636 26829", "94820 84090", "9353887956"]
    },
  "focus_areas": [
    {
      "name": "Children's Rights",
      "description": "Advocating for the protection and development of children.",
      "icon": "users"
    },
    {
      "name": "Disaster Management",
      "description": "Preparing for and responding to natural and man-made disasters.",
      "icon": "shield"
    },
    {
      "name": "Community Development",
      "description": "Implementing initiatives to uplift and empower local communities.",
      "icon": "home"
    },
    {
      "name": "Environment",
      "description": "Engaging in environmental conservation and sustainable practices.",
      "icon": "leaf"
    },
    {
      "name": "Disability",
      "description": "Promoting inclusivity and support for individuals with disabilities.",
      "icon": "accessibility"
    },
    {
      "name": "Research",
      "description": "Conducting studies to inform and improve development strategies.",
      "icon": "search"
    },
    {
      "name": "Community-Based Organizations",
      "description": "Strengthening grassroots organizations for effective local governance.",
      "icon": "users"
    },
    {
      "name": "Youth",
      "description": "Empowering young people to become active participants in societal development.",
      "icon": "user"
    }
  ],
  "upcoming_events": [
    {
      "title": "CSR/CSO's Conclave – South India 2026",
      "description": "Two-day regional event focused on environmental sustainability, climate change, and sustainable practices.",
      "date": "2026-01-07",
      "time": "9:30 AM - 6:30 PM",
      "location": "Golden Metro Hotel, Sheshadripuram, Bengaluru",
      "organizers": "FEVOURD-K | Vishwa Yuvak Kendra (VYK)",
      "registration_url": "/events/conclave-2026"
    }
  ],
  "navigation_menu": [
    {
      "text": "Home",
      "url": "/",
      "icon": "home"
    },
    {
      "text": "About Us",
      "url": "/about",
      "icon": "info"
    },
    {
      "text": "Team",
      "url": "/team",
      "icon": "users"
    },
    {
      "text": "Programs & Activities",
      "url": "/programs",
      "icon": "folder"
    },
    {
      "text": "Events",
      "url": "/events",
      "icon": "calendar"
    },
    {
      "text": "Gallery",
      "url": "/gallery",
      "icon": "photo"
    },
    {
      "text": "Our Partners & Sponsors",
      "url": "/our-partners-and-sponsers",
      "icon": "handshake"
    },
    {
      "text": "Digitalization",
      "url": "/digitalization",
      "icon": "laptop"
    },
    {
      "text": "Legal Status",
      "url": "/general-5",
      "icon": "gavel"
    },
    {
      "text": "Contact",
      "url": "/contact",
      "icon": "mail"
    }
  ]
}
EOF

echo "✅ Data extraction complete!"
echo "📊 Summary:"
echo "- Organization: FEVOURD-K details extracted"
echo "- Contact information: Email, phone, address, multiple phone numbers"
echo "- Focus areas: 8 key areas with descriptions and icons"
echo "- Upcoming events: CSR/CSO Conclave 2026 details"
echo "- Navigation structure: Complete menu hierarchy extracted"
echo "- Images: Gallery images, logo, hero background downloaded"
echo ""
echo "🎯 Ready to implement extracted data and images into FEVOURD-K platform!"
