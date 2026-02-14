# Location-Based Architecture Documentation

## Overview

The FEVOURD-K platform implements a comprehensive location-based architecture with Karnataka as the default region and full support for future national expansion. The system is designed to tag all content (NGOs, campaigns, events, programs) with precise location information following a three-level hierarchy.

## Location Hierarchy

### Level 1: State
- **Primary administrative division**
- Contains geographical and demographic information
- Default: Karnataka (KA)
- Future-ready for all Indian states

### Level 2: District  
- **Secondary administrative division**
- Belongs to a specific state
- Contains statistical data (population, literacy rate, etc.)
- Karnataka has 31 districts

### Level 3: City
- **Tertiary administrative division**
- Belongs to a specific district
- Types: City, Town, Village
- Contains pincode and coordinates

## Database Schema

### States Table
```sql
- id (primary)
- name (string)
- code (string, e.g., "KA")
- is_active (boolean)
- is_default (boolean) - Karnataka is default
- country_code (string)
- latitude (decimal)
- longitude (decimal)
- timezone (string)
- population (integer)
- area_km2 (integer)
```

### Districts Table
```sql
- id (primary)
- name (string)
- state_id (foreign key)
- code (string)
- is_active (boolean)
- latitude (decimal)
- longitude (decimal)
- population (integer)
- area_km2 (integer)
- headquarters (string)
- literacy_rate (decimal)
- description (text)
```

### Cities Table
```sql
- id (primary)
- name (string)
- district_id (foreign key)
- state_id (foreign key)
- code (string)
- is_active (boolean)
- latitude (decimal)
- longitude (decimal)
- population (integer)
- area_km2 (integer)
- pincode (string)
- type (enum: city, town, village)
- description (text)
```

## Location Tagging

All content entities include location fields:
- `state_id` (required)
- `district_id` (optional, but recommended)
- `city_id` (optional)

### Supported Entities
- NGOs
- Campaigns
- Events
- Programs
- Users (for location-based filtering)

## Services and Components

### LocationService
Central service for location-related operations:
- `getDefaultLocation()` - Returns Karnataka as default
- `getLocationHierarchy()` - Gets locations by level
- `getLocationContext()` - Builds location context
- `validateLocation()` - Validates location data
- `getLocationStats()` - Provides location statistics

### LocationController
API endpoints for location operations:
- `/api/locations/states` - Get all states
- `/api/locations/districts` - Get districts for state
- `/api/locations/cities` - Get cities for district
- `/api/locations/set` - Set user location
- `/api/locations/stats` - Get location statistics
- `/api/locations/search` - Search locations

### LocationContext Middleware
Automatically handles location context for all requests:
- Sets default location (Karnataka) if not set
- Handles location changes
- Shares location data with all views
- Provides location filters for content

### HasLocation Trait
Reusable trait for models with location tagging:
- Provides relationships (state, district, city)
- Offers location-based scopes
- Includes helper methods for location display
- Supports location validation

### LocationSelector Component
Frontend component for location selection:
- Hierarchical dropdowns (State → District → City)
- Real-time location statistics
- Location search functionality
- Responsive design

## Usage Examples

### Filtering Content by Location
```php
// Get NGOs in Karnataka
$ngos = NGO::inState(1)->get();

// Get campaigns in Bangalore district
$campaigns = Campaign::inDistrict(5)->get();

// Get events in specific city
$events = Event::inCity(100)->get();

// Complex location filtering
$programs = Program::inLocation([
    'state_id' => 1,
    'district_id' => 5,
    'city_id' => 100
])->get();
```

### Location-Based Scopes
```php
// Near location (same district)
$nearbyNGOs = NGO::nearLocation([
    'state_id' => 1,
    'district_id' => 5
])->get();

// Same state only
$stateCampaigns = Campaign::inSameStateAs($ngo)->get();
```

### Frontend Location Context
```javascript
// Access current location in views
const location = page.props.location_context;
const filters = page.props.location_filters;

// Location display
<span>{{ location.display_name }}</span>
```

## Default Configuration

### Karnataka as Default Region
- State: Karnataka (KA)
- Districts: 31 districts
- Cities: All major cities, towns, and villages
- Timezone: Asia/Kolkata
- Country Code: IN

### Location Detection
1. **Session-based**: User's preferred location stored in session
2. **Default fallback**: Karnataka if no location set
3. **IP-based**: Future enhancement for auto-detection
4. **Manual selection**: User can change location anytime

## Future Expansion

### National Readiness
The architecture is designed for easy expansion to other states:

1. **Add new states** through admin panel
2. **Import district/city data** via CSV or API
3. **Multi-state support** already built-in
4. **Regional customization** per state

### Scalability Features
- **Caching**: Location data cached for performance
- **Lazy loading**: Districts/cities loaded on demand
- **Search functionality**: Fast location search
- **Statistics**: Real-time location-based analytics

## API Endpoints

### Location Hierarchy
```
GET /api/locations/states                    - All states
GET /api/locations/districts?state_id=1     - Districts in state
GET /api/locations/cities?district_id=5     - Cities in district
```

### Location Management
```
POST /api/locations/set                      - Set user location
GET /api/locations/current                   - Get current location
POST /api/locations/reset                     - Reset to default
GET /api/locations/stats?state_id=1          - Location statistics
```

### Search and Discovery
```
GET /api/locations/search?q=bangalore&type=all  - Search locations
GET /api/locations/hierarchy?level=states     - Get hierarchy
```

## Performance Considerations

### Caching Strategy
- Location hierarchies cached for 1 hour
- Location statistics cached for 30 minutes
- User location context stored in session

### Database Optimization
- Indexed location fields for fast queries
- Composite indexes for multi-level filtering
- Foreign key constraints for data integrity

### Frontend Optimization
- Lazy loading of district/city data
- Debounced search requests
- Component-level caching for location data

## Security Considerations

### Data Validation
- All location data validated before storage
- Hierarchical validation (city must belong to district, etc.)
- SQL injection protection through Eloquent

### Access Control
- Location-based content filtering
- User permissions respected in location context
- Admin-only location management

## Testing

### Unit Tests
- LocationService methods
- Model relationships and scopes
- Validation rules

### Integration Tests
- Location API endpoints
- Middleware functionality
- Frontend component behavior

### Performance Tests
- Large dataset handling
- Concurrent location changes
- Cache effectiveness

## Maintenance

### Data Updates
- Location data updated through admin panel
- Bulk import/export capabilities
- Automated data validation

### Monitoring
- Location-based analytics
- Performance metrics for location queries
- Error tracking for location services

This architecture provides a robust, scalable foundation for location-based features while maintaining excellent performance and user experience.
