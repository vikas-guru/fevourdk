#!/usr/bin/env python3
"""
Vikasana Institute - Image Downloader Script
Downloads placeholder images for the website based on requirements
"""

import requests
import os
from urllib.parse import urlparse
import time

def create_directory_structure():
    """Create the image directory structure"""
    directories = [
        'leadership',
        'activities',
        'infrastructure',
        'impact',
        'logos'
    ]
    
    for directory in directories:
        os.makedirs(f'assets/images/{directory}', exist_ok=True)
        print(f"Created directory: assets/images/{directory}")

def download_placeholder_images():
    """Download placeholder images for different categories"""
    
    # Leadership images - professional headshots
    leadership_images = {
        'leadership/president.jpg': 'https://via.placeholder.com/400x400/4CAF50/FFFFFF?text=Dr.+R+L+Jagadish',
        'leadership/secretary.jpg': 'https://via.placeholder.com/400x400/2196F3/FFFFFF?text=Mahesh+Chandra+Guru',
        'leadership/vice-president.jpg': 'https://via.placeholder.com/400x400/FF9800/FFFFFF?text=H+D+Nagesh',
        'leadership/founder.jpg': 'https://via.placeholder.com/400x400/9C27B0/FFFFFF?text=Keshavan+Namboodari',
        'leadership/treasurer.jpg': 'https://via.placeholder.com/400x400/E91E63/FFFFFF?text=S+Balasubramanian',
        'leadership/member-mamatha.jpg': 'https://via.placeholder.com/400x400/00BCD4/FFFFFF?text=Mamatha',
        'leadership/member-radhamani.jpg': 'https://via.placeholder.com/400x400/795548/FFFFFF?text=Radhamani',
        'leadership/member-mangegowda.jpg': 'https://via.placeholder.com/400x400/607D8B/FFFFFF?text=B+J+Mangegowda'
    }
    
    # Activity images - various NGO activities
    activity_images = {
        'activities/world-environment-day.jpg': 'https://via.placeholder.com/800x600/4CAF50/FFFFFF?text=World+Environment+Day',
        'activities/covid-relief.jpg': 'https://via.placeholder.com/800x600/2196F3/FFFFFF?text=COVID-19+Relief',
        'activities/childline-awareness.jpg': 'https://via.placeholder.com/800x600/FF9800/FFFFFF?text=Childline+Awareness',
        'activities/gender-discrimination.jpg': 'https://via.placeholder.com/800x600/9C27B0/FFFFFF?text=Gender+Equality',
        'activities/health-camp.jpg': 'https://via.placeholder.com/800x600/E91E63/FFFFFF?text=Health+Camp',
        'activities/agriculture-development.jpg': 'https://via.placeholder.com/800x600/00BCD4/FFFFFF?text=Agriculture',
        'activities/water-resources.jpg': 'https://via.placeholder.com/800x600/795548/FFFFFF?text=Water+Resources',
        'activities/housing-project.jpg': 'https://via.placeholder.com/800x600/607D8B/FFFFFF?text=Housing+Project'
    }
    
    # Infrastructure images
    infrastructure_images = {
        'infrastructure/office-building.jpg': 'https://via.placeholder.com/800x600/3498db/FFFFFF?text=Vikasana+Office',
        'infrastructure/community-center.jpg': 'https://via.placeholder.com/800x600/3498db/FFFFFF?text=Community+Center',
        'infrastructure/training-session.jpg': 'https://via.placeholder.com/800x600/3498db/FFFFFF?text=Training+Session',
        'infrastructure/village-meeting.jpg': 'https://via.placeholder.com/800x600/3498db/FFFFFF?text=Village+Meeting'
    }
    
    # Impact stories
    impact_images = {
        'impact/success-story-1.jpg': 'https://via.placeholder.com/600x400/27ae60/FFFFFF?text=Success+Story+1',
        'impact/success-story-2.jpg': 'https://via.placeholder.com/600x400/27ae60/FFFFFF?text=Success+Story+2',
        'impact/success-story-3.jpg': 'https://via.placeholder.com/600x400/27ae60/FFFFFF?text=Success+Story+3'
    }
    
    # Logo and branding
    logo_images = {
        'logos/vikasana-logo.png': 'https://via.placeholder.com/200x200/4CAF50/FFFFFF?text=VIKASANA',
        'logos/fcra-certificate.png': 'https://via.placeholder.com/300x200/2196F3/FFFFFF?text=FCRA',
        'logos/80g-certificate.png': 'https://via.placeholder.com/300x200/FF9800/FFFFFF?text=80G',
        'logos/partner-police.png': 'https://via.placeholder.com/150x150/000000/FFFFFF?text=Police',
        'logos/partner-bank.png': 'https://via.placeholder.com/150x150/007BFF/FFFFFF?text=Bank'
    }
    
    all_images = {**leadership_images, **activity_images, **infrastructure_images, **impact_images, **logo_images}
    
    return all_images

def download_image(url, filename):
    """Download a single image"""
    try:
        response = requests.get(url, stream=True)
        response.raise_for_status()
        
        # Create directory if it doesn't exist
        os.makedirs(os.path.dirname(filename), exist_ok=True)
        
        with open(filename, 'wb') as f:
            for chunk in response.iter_content(chunk_size=8192):
                f.write(chunk)
        
        print(f"Downloaded: {filename}")
        return True
        
    except Exception as e:
        print(f"Failed to download {url}: {e}")
        return False

def main():
    """Main function to download all images"""
    print("Vikasana Institute - Image Downloader")
    print("=" * 50)
    
    # Create directory structure
    create_directory_structure()
    
    # Get all images to download
    images = download_placeholder_images()
    
    # Download images
    success_count = 0
    total_count = len(images)
    
    for filename, url in images.items():
        if download_image(url, filename):
            success_count += 1
        time.sleep(0.5)  # Be respectful to the placeholder service
    
    print("=" * 50)
    print(f"Download complete: {success_count}/{total_count} images downloaded")
    print(f"Images saved in: assets/images/")
    
    # Create image manifest
    create_image_manifest(images)

def create_image_manifest(images):
    """Create a manifest file with image information"""
    manifest_content = """# Image Manifest
# Generated on: {date}

## Downloaded Images:

""".format(date=time.strftime("%Y-%m-%d %H:%M:%S"))
    
    for filename, url in images.items():
        manifest_content += f"- {filename}: {url}\\n"
    
    manifest_content += """
## Usage:
Replace the placeholder URLs in index.html with local paths:
- Change 'https://via.placeholder.com/...' to 'assets/images/filename'

## Next Steps:
1. Replace placeholder images with actual photographs
2. Optimize images for web (compress, resize)
3. Add alt text for accessibility
4. Test image loading performance
"""
    
    with open('assets/images/manifest.txt', 'w') as f:
        f.write(manifest_content)
    
    print("Created: assets/images/manifest.txt")

if __name__ == "__main__":
    main()
