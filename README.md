# PH Zip Codes
A PHP library that provides a collection of zip codes and barangays in the Philippines, ideal for applications that need location-based information in the Philippines. **Note**: Cities in the NCR (National Capital Region) may contain multiple zip codes.

## Disclaimer
- The data mapping is done manually, so there may be some incorrect or incomplete data, including potential typographical errors.
- Data may be outdated as some information is subject to change over time.
- Some municipalities may contain empty barangay entries.

### PhAddress
- static methods
- `illuminate/collections` dependent
#### Available Methods (Chained Structure)
- `region($region = "")`: Retrieves a list of all regions. If `$region` is specified, it fetches data for that specific region if it exists. 
- `province($province = "")`: Returns a list of provinces within the specified `$region`. If `$province` is provided, it fetches data for that specific province within the given region.  
  **Note**: Calling `province` without specifying a `$region` will return an empty result.
- `municipality($municipality = "")`:  
  Returns a list of municipalities within the specified `$region` and `$province`. If `$municipality` is provided, it fetches data for that specific municipality within the given region and province.  
  **Note**: Calling `municipality` without specifying both `$region` and `$province` will return an empty result.
- In the case of NCR, `zip_codes` in cities (in municipality(...) is a list of associative arrays where each entry maps the keys `zip_code` and `location` to their respective values.
Sample:
```
    # REGION
    $region = PhAddress::region() # returns list of regions
    $region = PhAddress::region(REGION 5) # returns region 5 provinces 

    # PROVINCE
    $province = $region->province() # returns list of provinces in 'REGION 5'
    $province = $region->province(ALBAY) # returns 'ALBAY' municipalities in 'REGION 5'

    # MUNICIPALITY
    $municipality = $province->municipality() # returns list of municipalities of 'ALBAY' in 'REGION 5'
    $municipality = $province->municipality(MALILIPOT) # returns 'MALILIPOT' data including zip_code and barangays
```
