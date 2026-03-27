#!/usr/bin/env python3
"""
Create simple placeholder images for Vikasana website
"""

from PIL import Image, ImageDraw, ImageFont
import os

def create_directories():
    """Create image directories"""
    dirs = ['leadership', 'activities', 'infrastructure', 'impact', 'logos']
    for d in dirs:
        os.makedirs(f'assets/images/{d}', exist_ok=True)
        print(f"Created: assets/images/{d}")

def create_image(width, height, color, text, filename):
    """Create a simple placeholder image"""
    img = Image.new('RGB', (width, height), color)
    draw = ImageDraw.Draw(img)
    
    try:
        font = ImageFont.truetype("/System/Library/Fonts/Arial.ttf", 20)
    except:
        font = ImageFont.load_default()
    
    # Center text
    bbox = draw.textbbox((0, 0), text, font=font)
    text_width = bbox[2] - bbox[0]
    text_height = bbox[3] - bbox[1]
    x = (width - text_width) // 2
    y = (height - text_height) // 2
    
    draw.text((x, y), text, fill="white", font=font)
    img.save(filename)
    print(f"Created: {filename}")

def main():
    print("Creating Vikasana placeholder images...")
    create_directories()
    
    # Leadership images
    create_image(400, 400, '#4CAF50', 'Dr. R L Jagadish', 'assets/images/leadership/president.jpg')
    create_image(400, 400, '#2196F3', 'Mahesh Chandra Guru', 'assets/images/leadership/secretary.jpg')
    create_image(400, 400, '#FF9800', 'H D Nagesh', 'assets/images/leadership/vice-president.jpg')
    create_image(400, 400, '#9C27B0', 'Keshavan Namboodari', 'assets/images/leadership/founder.jpg')
    create_image(400, 400, '#E91E63', 'S Balasubramanian', 'assets/images/leadership/treasurer.jpg')
    create_image(400, 400, '#00BCD4', 'Mamatha', 'assets/images/leadership/member-mamatha.jpg')
    create_image(400, 400, '#795548', 'Radhamani', 'assets/images/leadership/member-radhamani.jpg')
    create_image(400, 400, '#607D8B', 'B J Mangegowda', 'assets/images/leadership/member-mangegowda.jpg')
    
    # Activity images
    create_image(800, 600, '#4CAF50', 'World Environment Day', 'assets/images/activities/world-environment-day.jpg')
    create_image(800, 600, '#2196F3', 'COVID-19 Relief', 'assets/images/activities/covid-relief.jpg')
    create_image(800, 600, '#FF9800', 'Childline Awareness', 'assets/images/activities/childline-awareness.jpg')
    create_image(800, 600, '#9C27B0', 'Gender Equality', 'assets/images/activities/gender-discrimination.jpg')
    create_image(800, 600, '#E91E63', 'Health Camp', 'assets/images/activities/health-camp.jpg')
    create_image(800, 600, '#00BCD4', 'Agriculture Development', 'assets/images/activities/agriculture-development.jpg')
    create_image(800, 600, '#795548', 'Water Resources', 'assets/images/activities/water-resources.jpg')
    create_image(800, 600, '#607D8B', 'Housing Project', 'assets/images/activities/housing-project.jpg')
    
    # Infrastructure images
    create_image(800, 600, '#3498db', 'Vikasana Office', 'assets/images/infrastructure/office-building.jpg')
    create_image(800, 600, '#3498db', 'Community Center', 'assets/images/infrastructure/community-center.jpg')
    create_image(800, 600, '#3498db', 'Training Session', 'assets/images/infrastructure/training-session.jpg')
    create_image(800, 600, '#3498db', 'Village Meeting', 'assets/images/infrastructure/village-meeting.jpg')
    
    # Impact images
    create_image(600, 400, '#27ae60', 'Success Story 1', 'assets/images/impact/success-story-1.jpg')
    create_image(600, 400, '#27ae60', 'Success Story 2', 'assets/images/impact/success-story-2.jpg')
    create_image(600, 400, '#27ae60', 'Success Story 3', 'assets/images/impact/success-story-3.jpg')
    
    # Logo images
    create_image(200, 200, '#4CAF50', 'VIKASANA', 'assets/images/logos/vikasana-logo.png')
    create_image(300, 200, '#2196F3', 'FCRA', 'assets/images/logos/fcra-certificate.png')
    create_image(300, 200, '#FF9800', '80G', 'assets/images/logos/80g-certificate.png')
    create_image(150, 150, '#000000', 'POLICE', 'assets/images/logos/partner-police.png')
    create_image(150, 150, '#007BFF', 'BANK', 'assets/images/logos/partner-bank.png')
    
    print("All images created successfully!")

if __name__ == "__main__":
    main()
