#!/usr/bin/env python3
"""
Download fallback images from reliable sources for Vikasana website
Using multiple image sources for better success rate
"""

import requests
import os
import time
from urllib.parse import urlparse

def create_directories():
    """Create image directories"""
    dirs = ['leadership', 'activities', 'infrastructure', 'impact', 'logos']
    for d in dirs:
        os.makedirs(f'assets/images/{d}', exist_ok=True)
        print(f"Created directory: assets/images/{d}")

def download_image(url, filename, description=""):
    """Download a single image with error handling"""
    try:
        headers = {
            'User-Agent': 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
        }
        
        response = requests.get(url, headers=headers, stream=True, timeout=10)
        response.raise_for_status()
        
        # Create directory if it doesn't exist
        os.makedirs(os.path.dirname(filename), exist_ok=True)
        
        with open(filename, 'wb') as f:
            for chunk in response.iter_content(chunk_size=8192):
                f.write(chunk)
        
        print(f"✅ Downloaded: {filename} - {description}")
        return True
        
    except Exception as e:
        print(f"❌ Failed to download {url}: {e}")
        return False

def download_fallback_images():
    """Download fallback images from reliable sources"""
    
    # Leadership images - using different reliable sources
    leadership_images = [
        {
            'url': 'https://picsum.photos/400/400?random=1',
            'filename': 'assets/images/leadership/president.jpg',
            'description': 'President'
        },
        {
            'url': 'https://picsum.photos/400/400?random=2',
            'filename': 'assets/images/leadership/secretary.jpg',
            'description': 'Secretary'
        },
        {
            'url': 'https://picsum.photos/400/400?random=3',
            'filename': 'assets/images/leadership/vice-president.jpg',
            'description': 'Vice President'
        },
        {
            'url': 'https://picsum.photos/400/400?random=4',
            'filename': 'assets/images/leadership/founder.jpg',
            'description': 'Founder'
        },
        {
            'url': 'https://picsum.photos/400/400?random=5',
            'filename': 'assets/images/leadership/treasurer.jpg',
            'description': 'Treasurer'
        },
        {
            'url': 'https://picsum.photos/400/400?random=6',
            'filename': 'assets/images/leadership/member-mamatha.jpg',
            'description': 'Member Mamatha'
        },
        {
            'url': 'https://picsum.photos/400/400?random=7',
            'filename': 'assets/images/leadership/member-radhamani.jpg',
            'description': 'Member Radhamani'
        },
        {
            'url': 'https://picsum.photos/400/400?random=8',
            'filename': 'assets/images/leadership/member-mangegowda.jpg',
            'description': 'Member Mangegowda'
        }
    ]
    
    # Activity images
    activity_images = [
        {
            'url': 'https://picsum.photos/800/600?random=11',
            'filename': 'assets/images/activities/world-environment-day.jpg',
            'description': 'World Environment Day'
        },
        {
            'url': 'https://picsum.photos/800/600?random=12',
            'filename': 'assets/images/activities/covid-relief.jpg',
            'description': 'COVID-19 Relief'
        },
        {
            'url': 'https://picsum.photos/800/600?random=13',
            'filename': 'assets/images/activities/childline-awareness.jpg',
            'description': 'Childline Awareness'
        },
        {
            'url': 'https://picsum.photos/800/600?random=14',
            'filename': 'assets/images/activities/gender-discrimination.jpg',
            'description': 'Gender Equality'
        },
        {
            'url': 'https://picsum.photos/800/600?random=15',
            'filename': 'assets/images/activities/health-camp.jpg',
            'description': 'Health Camp'
        },
        {
            'url': 'https://picsum.photos/800/600?random=16',
            'filename': 'assets/images/activities/agriculture-development.jpg',
            'description': 'Agriculture Development'
        },
        {
            'url': 'https://picsum.photos/800/600?random=17',
            'filename': 'assets/images/activities/water-resources.jpg',
            'description': 'Water Resources'
        },
        {
            'url': 'https://picsum.photos/800/600?random=18',
            'filename': 'assets/images/activities/housing-project.jpg',
            'description': 'Housing Project'
        }
    ]
    
    # Infrastructure images
    infrastructure_images = [
        {
            'url': 'https://picsum.photos/800/600?random=21',
            'filename': 'assets/images/infrastructure/office-building.jpg',
            'description': 'Office Building'
        },
        {
            'url': 'https://picsum.photos/800/600?random=22',
            'filename': 'assets/images/infrastructure/community-center.jpg',
            'description': 'Community Center'
        },
        {
            'url': 'https://picsum.photos/800/600?random=23',
            'filename': 'assets/images/infrastructure/training-session.jpg',
            'description': 'Training Session'
        },
        {
            'url': 'https://picsum.photos/800/600?random=24',
            'filename': 'assets/images/infrastructure/village-meeting.jpg',
            'description': 'Village Meeting'
        }
    ]
    
    # Impact stories
    impact_images = [
        {
            'url': 'https://picsum.photos/600/400?random=31',
            'filename': 'assets/images/impact/success-story-1.jpg',
            'description': 'Success Story 1'
        },
        {
            'url': 'https://picsum.photos/600/400?random=32',
            'filename': 'assets/images/impact/success-story-2.jpg',
            'description': 'Success Story 2'
        },
        {
            'url': 'https://picsum.photos/600/400?random=33',
            'filename': 'assets/images/impact/success-story-3.jpg',
            'description': 'Success Story 3'
        }
    ]
    
    # Logo and branding
    logo_images = [
        {
            'url': 'https://picsum.photos/200/200?random=41',
            'filename': 'assets/images/logos/vikasana-logo.png',
            'description': 'Vikasana Logo'
        },
        {
            'url': 'https://picsum.photos/300/200?random=42',
            'filename': 'assets/images/logos/fcra-certificate.png',
            'description': 'FCRA Certificate'
        },
        {
            'url': 'https://picsum.photos/300/200?random=43',
            'filename': 'assets/images/logos/80g-certificate.png',
            'description': '80G Certificate'
        },
        {
            'url': 'https://picsum.photos/150/150?random=44',
            'filename': 'assets/images/logos/partner-police.png',
            'description': 'Police Partner'
        },
        {
            'url': 'https://picsum.photos/150/150?random=45',
            'filename': 'assets/images/logos/partner-bank.png',
            'description': 'Bank Partner'
        }
    ]
    
    all_images = leadership_images + activity_images + infrastructure_images + impact_images + logo_images
    return all_images

def main():
    """Main function to download all images"""
    print("🖼️  Vikasana Institute - Fallback Image Downloader")
    print("=" * 60)
    
    # Create directory structure
    create_directories()
    
    # Get all images to download
    images = download_fallback_images()
    
    # Download images
    success_count = 0
    total_count = len(images)
    
    print(f"\n📥 Downloading {total_count} images...")
    
    for i, image_data in enumerate(images, 1):
        print(f"\n[{i}/{total_count}] Processing...")
        if download_image(image_data['url'], image_data['filename'], image_data['description']):
            success_count += 1
        
        # Small delay between downloads
        time.sleep(0.3)
    
    print("\n" + "=" * 60)
    print(f"✅ Download complete: {success_count}/{total_count} images downloaded")
    print(f"📁 Images saved in: assets/images/")
    
    # Test image availability
    test_images()

def test_images():
    """Test if images were downloaded successfully"""
    print("\n🔍 Testing downloaded images...")
    
    categories = ['leadership', 'activities', 'infrastructure', 'impact', 'logos']
    
    for category in categories:
        folder_path = f'assets/images/{category}'
        if os.path.exists(folder_path):
            files = [f for f in os.listdir(folder_path) if f.endswith(('.jpg', '.png'))]
            print(f"📁 {category}: {len(files)} images")
            for file in files[:3]:  # Show first 3 files
                print(f"   - {file}")
        else:
            print(f"❌ {category}: Folder not found")

if __name__ == "__main__":
    main()
