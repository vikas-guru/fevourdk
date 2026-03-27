#!/usr/bin/env python3
"""
Create local placeholder images for Vikasana website
Generates colored placeholder images with text using PIL
"""

from PIL import Image, ImageDraw, ImageFont
import os
import textwrap

def create_directory_structure():
    """Create image directory structure"""
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

def create_placeholder_image(width, height, color, text, filename):
    """Create a placeholder image with text"""
    # Create image with color
    img = Image.new('RGB', (width, height), color)
    draw = ImageDraw.Draw(img)
    
    # Try to use a larger font, fallback to default
    try:
        font = ImageFont.truetype("/System/Library/Fonts/Arial.ttf", 24)
    except:
        font = ImageFont.load_default()
    
    # Wrap text if too long
    lines = textwrap.wrap(text, width=20)
    
    # Calculate text position
    y_position = (height - len(lines) * 30) // 2
    
    # Draw each line of text
    for line in lines:
        # Get text bounding box
        bbox = draw.textbbox((0, 0), line, font=font)
        text_width = bbox[2] - bbox[0]
        x_position = (width - text_width) // 2
        
        # Draw text
        draw.text((x_position, y_position), line, fill="white", font=font)
        y_position += 30
    
    # Save image
    img.save(filename)
    print(f"Created: {filename}")

def create_all_images():
    """Create all placeholder images"""
    
    # Leadership images - professional headshots
    leadership_images = [
        (400, 400, '#4CAF50', 'Dr. R L Jagadish\nPresident', 'assets/images/leadership/president.jpg'),
        (400, 400, '#2196F3', 'Mahesh Chandra Guru\nSecretary', 'assets/images/leadership/secretary.jpg'),
        (400, 400, '#FF9800', 'H D Nagesh\nVice President', 'assets/images/leadership/vice-president.jpg'),
        (400, 400, '#9C27B0', 'Keshavan Namboodari\nFounder', 'assets/images/leadership/founder.jpg'),
        (400, 400, '#E91E63', 'S Balasubramanian\nTreasurer', 'assets/images/leadership/treasurer.jpg'),
        (400, 400, '#00BCD4', 'Mamatha\nMember', 'assets/images/leadership/member-mamatha.jpg'),
        (400, 400, '#795548', 'Radhamani\nMember', 'assets/images/leadership/member-radhamani.jpg'),
        (400, 400, '#607D8B', 'B J Mangegowda\nMember', 'assets/images/leadership/member-mangegowda.jpg')
    ]
    
    # Activity images - various NGO activities
    activity_images = [
        (800, 600, '#4CAF50', 'World Environment Day\nCelebration', 'assets/images/activities/world-environment-day.jpg'),
        (800, 600, '#2196F3', 'COVID-19 Relief\nDistribution', 'assets/images/activities/covid-relief.jpg'),
        (800, 600, '#FF9800', 'Childline Awareness\nProgram', 'assets/images/activities/childline-awareness.jpg'),
        (800, 600, '#9C27B0', 'Gender Discrimination\nAwareness', 'assets/images/activities/gender-discrimination.jpg'),
        (800, 600, '#E91E63', 'Health Camp\nRural Communities', 'assets/images/activities/health-camp.jpg'),
        (800, 600, '#00BCD4', 'Agriculture\nDevelopment', 'assets/images/activities/agriculture-development.jpg'),
        (800, 600, '#795548', 'Water Resources\nManagement', 'assets/images/activities/water-resources.jpg'),
        (800, 600, '#607D8B', 'Housing Project\nCommunity Development', 'assets/images/activities/housing-project.jpg')
    ]
    
    # Infrastructure images
    infrastructure_images = [
        (800, 600, '#3498db', 'Vikasana Office\nMandya', 'assets/images/infrastructure/office-building.jpg'),
        (800, 600, '#3498db', 'Community Center\nDevelopment', 'assets/images/infrastructure/community-center.jpg'),
        (800, 600, '#3498db', 'Training Session\nCapacity Building', 'assets/images/infrastructure/training-session.jpg'),
        (800, 600, '#3498db', 'Village Meeting\nGram Panchayath', 'assets/images/infrastructure/village-meeting.jpg')
    ]
    
    # Impact stories
    impact_images = [
        (600, 400, '#27ae60', 'Success Story 1\nCommunity Impact', 'assets/images/impact/success-story-1.jpg'),
        (600, 400, '#27ae60', 'Success Story 2\nTransformation', 'assets/images/impact/success-story-2.jpg'),
        (600, 400, '#27ae60', 'Success Story 3\nEmpowerment', 'assets/images/impact/success-story-3.jpg')
    ]
    
    # Logo and branding
    logo_images = [
        (200, 200, '#4CAF50', 'VIKASANA\nLogo', 'assets/images/logos/vikasana-logo.png'),
        (300, 200, '#2196F3', 'FCRA\nCertificate', 'assets/images/logos/fcra-certificate.png'),
        (300, 200, '#FF9800', '80G\nTax Exemption', 'assets/images/logos/80g-certificate.png'),
        (150, 150, '#000000', 'Police\nPartner', 'assets/images/logos/partner-police.png'),
        (150, 150, '#007BFF', 'Bank\nPartner', 'assets/images/logos/partner-bank.png')
    ]
    
    all_images = leadership_images + activity_images + infrastructure_images + impact_images + logo_images
    return all_images

def main():
    """Main function to create all images"""
    print("Vikasana Institute - Local Image Creator")
    print("=" * 50)
    
    # Create directory structure
    create_directory_structure()
    
    # Get all images to create
    images = create_all_images()
    
    # Create images
    success_count = 0
    total_count = len(images)
    
    for width, height, color, text, filename in images:
        try:
            create_placeholder_image(width, height, color, text, filename)
            success_count += 1
        except Exception as e:
            print(f"Failed to create {filename}: {e}")
    
    print("=" * 50)
    print(f"Image creation complete: {success_count}/{total_count} images created")
    print(f"Images saved in: assets/images/")
    
    # Create update script for HTML
    create_html_update_script()

def create_html_update_script():
    """Create a script to update HTML with local image paths"""
    script_content = """#!/usr/bin/env python3
"""
Script to update HTML with local image paths
"""

import re

def update_html_images():
    # Read the HTML file
    with open('index.html', 'r') as f:
        content = f.read()
    
    # Replace placeholder URLs with local paths
    replacements = {
        'https://via.placeholder.com/400x400/4CAF50/FFFFFF?text=President': 'assets/images/leadership/president.jpg',
        'https://via.placeholder.com/400x400/2196F3/FFFFFF?text=Secretary': 'assets/images/leadership/secretary.jpg',
        'https://via.placeholder.com/400x400/FF9800/FFFFFF?text=Vice+President': 'assets/images/leadership/vice-president.jpg',
        'https://via.placeholder.com/400x400/9C27B0/FFFFFF?text=Founder': 'assets/images/leadership/founder.jpg',
        'https://via.placeholder.com/400x400/E91E63/FFFFFF?text=Treasurer': 'assets/images/leadership/treasurer.jpg',
        'https://via.placeholder.com/400x400/00BCD4/FFFFFF?text=Member': 'assets/images/leadership/member-mamatha.jpg',
        'https://via.placeholder.com/400x400/795548/FFFFFF?text=Member': 'assets/images/leadership/member-radhamani.jpg',
        'https://via.placeholder.com/400x400/607D8B/FFFFFF?text=Member': 'assets/images/leadership/member-mangegowda.jpg',
        'https://via.placeholder.com/800x600/4CAF50/FFFFFF?text=World+Environment+Day': 'assets/images/activities/world-environment-day.jpg',
        'https://via.placeholder.com/800x600/2196F3/FFFFFF?text=COVID+Relief': 'assets/images/activities/covid-relief.jpg',
        'https://via.placeholder.com/800x600/FF9800/FFFFFF?text=Childline+Awareness': 'assets/images/activities/childline-awareness.jpg',
        'https://via.placeholder.com/800x600/9C27B0/FFFFFF?text=Agriculture': 'assets/images/activities/agriculture-development.jpg',
        'https://via.placeholder.com/800x600/E91E63/FFFFFF?text=Health+Camp': 'assets/images/activities/health-camp.jpg',
        'https://via.placeholder.com/800x600/795548/FFFFFF?text=Water+Resources': 'assets/images/activities/water-resources.jpg',
        'https://via.placeholder.com/800x600/607D8B/FFFFFF?text=Housing+Project': 'assets/images/infrastructure/housing-project.jpg',
        'https://via.placeholder.com/600x400/27ae60/FFFFFF?text=Success+Story+1': 'assets/images/impact/success-story-1.jpg',
        'https://via.placeholder.com/600x400/27ae60/FFFFFF?text=Success+Story+2': 'assets/images/impact/success-story-2.jpg',
        'https://via.placeholder.com/600x400/27ae60/FFFFFF?text=Success+Story+3': 'assets/images/impact/success-story-3.jpg'
    }
    
    # Apply replacements
    for old_url, new_path in replacements.items():
        content = content.replace(old_url, new_path)
    
    # Write updated HTML
    with open('index.html', 'w') as f:
        f.write(content)
    
    print("HTML updated with local image paths!")

if __name__ == "__main__":
    update_html_images()
"""
    
    with open('update-html-images.py', 'w') as f:
        f.write(script_content)
    
    print("Created: update-html-images.py")

if __name__ == "__main__":
    main()
"""
    
    with open('assets/images/update-html-images.py', 'w') as f:
        f.write(script_content)
    
    print("Created: assets/images/update-html-images.py")

if __name__ == "__main__":
    main()
