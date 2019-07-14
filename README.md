# Running the script
`php shipment-discount.php input.txt`

## Requirements
* PHP: ^7.2.19

## Assumptions
- PHP server has correct timezone settings, it is always compatible with the input and output data.
- Transaction records are ordered by a transaction date in the input file - the oldest is on top, the newest is at the end.
