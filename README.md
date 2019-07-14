# Running the script
`php shipment-discount.php input.txt`

## Requirements
* PHP: ^7.2.19

## Assumptions
- PHP server date and time is set correctly.
- PHP server has valid timezone settings therefore time is always compatible with the time seen in input and output data.
- PHP server has valid locale settings therefore server settings on decimal and thousand separators are ok for the output.
- Transaction records are ordered by a transaction date in the input file - the oldest is on top, the newest is at the end.
- Transaction records do not contain records from the future (relates to the assumption on timezone settings mentioned above).
