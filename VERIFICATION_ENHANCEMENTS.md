# Collection Verification Enhancements

## Overview
Enhanced the collection verification system to include payment status for co-funded applications and improved filtering capabilities.

## Changes Made

### 1. Payment Status Display for Co-funded Applications

#### Backend Changes:
- **AdminVerificationController.php**: Added `application.monetaryReturn` relationship to the query to fetch payment information
- **Application.php**: Updated season relationship to include `loan_type` field

#### Frontend Changes:
- **Verification Modal**: Added payment status display for co-funded collection verifications
  - Shows payment status (paid/pending/failed) with color coding
  - Shows payment amount when available
  - Only displays for co-funded applications in collection verification

### 2. Application Reference Number Filtering

#### Backend Changes:
- **AdminVerificationController.php**: Enhanced filter logic to include application reference number search
  - Updated both single type and mixed type queries
  - Added `orWhereHas('application')` clause to search reference numbers

#### Frontend Changes:
- **Verification Table**: 
  - Added new "Payment Status" column
  - Updated application column to show reference number
  - Updated search placeholder to indicate reference number search capability
  - Added payment status badges with appropriate color coding
  - Updated colspan for empty state messages

### 3. Features Added

#### Payment Status Column
- **Co-funded Collection Verifications**: Shows payment status badge (paid/pending/failed)
- **Other Verifications**: Shows loan type (Co-funded/Complete Loan)

#### Enhanced Search
- Search now includes:
  - Farmer name
  - Farmer registration number  
  - Farmer phone number
  - **Application reference number** (NEW)

#### Modal Enhancements
- Application reference number display
- Loan type display
- Payment status for co-funded applications
- Payment amount when available

## Usage

### Filtering by Application Reference
Users can now search for verifications using the application reference number in the search field.

### Payment Status Monitoring
For co-funded applications in collection verification:
- **Green badge**: Payment completed
- **Yellow badge**: Payment pending
- **Red badge**: Payment failed

### Modal Information
The verification modal now provides comprehensive information including:
- Application reference number
- Loan type (Co-funded vs Complete Loan)
- Payment status and amount (for co-funded applications)

## Technical Notes

### Database Relationships
- Added `monetaryReturn` relationship loading for payment information
- Enhanced season relationship to include loan_type

### Performance Considerations
- Maintained existing pagination and filtering performance
- Added minimal overhead for payment status queries
- Used eager loading to prevent N+1 queries

### Compatibility
- Changes are backward compatible
- Existing functionality remains unchanged
- New features gracefully handle missing data