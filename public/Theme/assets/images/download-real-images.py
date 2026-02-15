#!/usr/bin/env python3
"""
Download real images for Vikasana website
Using various sources for NGO and development related images
"""

import requests
import os
import time
from urllib.parse import urlparse
import json

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

def download_ngo_images():
    """Download real NGO and development related images"""
    
    # Leadership team images - professional diverse team photos
    leadership_images = [
        {
            'url': 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=400&fit=crop&crop=face',
            'filename': 'assets/images/leadership/president.jpg',
            'description': 'Professional male leader'
        },
        {
            'url': 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=400&h=400&fit=crop&crop=face',
            'filename': 'assets/images/leadership/secretary.jpg',
            'description': 'Professional male executive'
        },
        {
            'url': 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400&h=400&fit=crop&crop=face',
            'filename': 'assets/images/leadership/vice-president.jpg',
            'description': 'Senior professional leader'
        },
        {
            'url': 'https://images.unsplash.com/photo-1557862921-378ae0a2b2e9?w=400&h=400&fit=crop&crop=face',
            'filename': 'assets/images/leadership/founder.jpg',
            'description': 'Experienced founder'
        },
        {
            'url': 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=400&h=400&fit=crop&crop=face',
            'filename': 'assets/images/leadership/treasurer.jpg',
            'description': 'Female professional'
        },
        {
            'url': 'https://images.unsplash.com/photo-1494790108755-2616b332c2ca?w=400&h=400&fit=crop&crop=face',
            'filename': 'assets/images/leadership/member-mamatha.jpg',
            'description': 'Female team member'
        },
        {
            'url': 'https://images.unsplash.com/photo-1489424731084-a5d8b219a5bb?w=400&h=400&fit=crop&crop=face',
            'filename': 'assets/images/leadership/member-radhamani.jpg',
            'description': 'Female professional'
        },
        {
            'url': 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&h=400&fit=crop&crop=face',
            'filename': 'assets/images/leadership/member-mangegowda.jpg',
            'description': 'Male team member'
        }
    ]
    
    # Activity images - real NGO activities
    activity_images = [
        {
            'url': 'https://images.unsplash.com/photo-1563491069252-4a9896c1a9ee?w=800&h=600&fit=crop',
            'filename': 'assets/images/activities/world-environment-day.jpg',
            'description': 'Tree planting environmental activity'
        },
        {
            'url': 'https://images.unsplash.com/photo-1587557242747-6800b9db5a36?w=800&h=600&fit=crop',
            'filename': 'assets/images/activities/covid-relief.jpg',
            'description': 'Food distribution relief work'
        },
        {
            'url': 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=800&h=600&fit=crop',
            'filename': 'assets/images/activities/childline-awareness.jpg',
            'description': 'Children education awareness program'
        },
        {
            'url': 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800&h=600&fit=crop',
            'filename': 'assets/images/activities/gender-discrimination.jpg',
            'description': 'Women empowerment workshop'
        },
        {
            'url': 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=800&h=600&fit=crop',
            'filename': 'assets/images/activities/health-camp.jpg',
            'description': 'Medical health camp in rural area'
        },
        {
            'url': 'https://images.unsplash.com/photo-1591264998277-1e912739403a?w=800&h=600&fit=crop',
            'filename': 'assets/images/activities/agriculture-development.jpg',
            'description': 'Sustainable agriculture farming'
        },
        {
            'url': 'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?w=800&h=600&fit=crop',
            'filename': 'assets/images/activities/water-resources.jpg',
            'description': 'Water resource management project'
        },
        {
            'url': 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800&h=600&fit=crop',
            'filename': 'assets/images/activities/housing-project.jpg',
            'description': 'Housing construction project'
        }
    ]
    
    # Infrastructure images
    infrastructure_images = [
        {
            'url': 'https://images.unsplash.com/photo-1497366216548-375f70e60b3a?w=800&h=600&fit=crop',
            'filename': 'assets/images/infrastructure/office-building.jpg',
            'description': 'NGO office building'
        },
        {
            'url': 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800&h=600&fit=crop',
            'filename': 'assets/images/infrastructure/community-center.jpg',
            'description': 'Community development center'
        },
        {
            'url': 'https://images.unsplash.com/photo-1515378791036-0648a3e77a8a?w=800&h=600&fit=crop',
            'filename': 'assets/images/infrastructure/training-session.jpg',
            'description': 'Training and capacity building session'
        },
        {
            'url': 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=800&h=600&fit=crop',
            'filename': 'assets/images/infrastructure/village-meeting.jpg',
            'description': 'Village community meeting'
        }
    ]
    
    # Impact stories
    impact_images = [
        {
            'url': 'https://images.unsplash.com/photo-1511632765486-a01980e01a18?w=600&h=400&fit=crop',
            'filename': 'assets/images/impact/success-story-1.jpg',
            'description': 'Community success story'
        },
        {
            'url': 'https://images.unsplash.com/photo-1531483519425-248f8c5c3ec7?w=600&h=400&fit=crop',
            'filename': 'assets/images/impact/success-story-2.jpg',
            'description': 'Beneficiary transformation story'
        },
        {
            'url': 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=600&h=400&fit=crop',
            'filename': 'assets/images/impact/success-story-3.jpg',
            'description': 'Empowerment success story'
        }
    ]
    
    # Logo and branding
    logo_images = [
        {
            'url': 'https://images.unsplash.com/photo-1620321023374-1a2b5b5c5d5f?w=200&h=200&fit=crop',
            'filename': 'assets/images/logos/vikasana-logo.png',
            'description': 'Vikasana organization logo'
        },
        {
            'url': 'https://images.unsplash.com/photo-1554224154-2603257028d5?w=300&h=200&fit=crop',
            'filename': 'assets/images/logos/fcra-certificate.png',
            'description': 'FCRA certificate'
        },
        {
            'url': 'https://images.unsplash.com/photo-1554224154-2603257028d5?w=300&h=200&fit=crop',
            'filename': 'assets/images/logos/80g-certificate.png',
            'description': '80G tax exemption certificate'
        },
        {
            'url': 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=150&h=150&fit=crop',
            'filename': 'assets/images/logos/partner-police.png',
            'description': 'Police department partner'
        },
        {
            'url': 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=150&h=150&fit=crop',
            'filename': 'assets/images/logos/partner-bank.png',
            'description': 'Banking partner logo'
        }
    ]
    
    all_images = leadership_images + activity_images + infrastructure_images + impact_images + logo_images
    return all_images

def main():
    """Main function to download all images"""
    print("🖼️  Vikasana Institute - Real Image Downloader")
    print("=" * 60)
    
    # Create directory structure
    create_directories()
    
    # Get all images to download
    images = download_ngo_images()
    
    # Download images
    success_count = 0
    total_count = len(images)
    
    print(f"\n📥 Downloading {total_count} images...")
    
    for i, image_data in enumerate(images, 1):
        print(f"\n[{i}/{total_count}] Processing...")
        if download_image(image_data['url'], image_data['filename'], image_data['description']):
            success_count += 1
        
        # Small delay between downloads
        time.sleep(0.5)
    
    print("\n" + "=" * 60)
    print(f"✅ Download complete: {success_count}/{total_count} images downloaded")
    print(f"📁 Images saved in: assets/images/")
    
    # Create summary
    create_download_summary(success_count, total_count)
    
    # Test image availability
    test_images()

def create_download_summary(success_count, total_count):
    """Create a summary of downloaded images"""
    summary = f"""# Image Download Summary
**Date**: {time.strftime("%Y-%m-%d %H:%M:%S")}
**Success Rate**: {success_count}/{total_count} ({(success_count/total_count)*100:.1f}%)

## Downloaded Categories:
- Leadership Team: 8 images
- Activities: 8 images  
- Infrastructure: 4 images
- Impact Stories: 3 images
- Logos & Certifications: 5 images

## Next Steps:
1. Verify all images display correctly
2. Test website functionality
3. Optimize image sizes if needed
4. Replace with actual Vikasana photos when available
"""
    
    with open('assets/images/download-summary.md', 'w') as f:
        f.write(summary)
    
    print("📄 Created: assets/images/download-summary.md")

def test_images():
    """Test if images were downloaded successfully"""
    print("\n🔍 Testing downloaded images...")
    
    categories = ['leadership', 'activities', 'infrastructure', 'impact', 'logos']
    
    for category in categories:
        folder_path = f'assets/images/{category}'
        if os.path.exists(folder_path):
            files = [f for f in os.listdir(folder_path) if f.endswith(('.jpg', '.png'))]
            print(f"📁 {category}: {len(files)} images")
        else:
            print(f"❌ {category}: Folder not found")

if __name__ == "__main__":
    main()
