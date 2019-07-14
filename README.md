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

## Leftovers, improvements left for the future
- Some methods have way too much parameters (3+) in their description, it would be better to pack-group them into objects and pass it as one.
- Some methods have arrays coming in as arguments. Using collection object may be a bit more type-safe in some of cases.
- Some methods have class name as type hint (e.g. DateTime), consider using interfaces instead. I have tried to minimize most of the objects usage but some are still left.
- Avoid using "new ClassName" type of construct inside classes, it may be better to: 1. pass such objects already created, or 2. create these objects in constructor, or 3. use factory got from 1 or 2.
- More advanced templating solution may be introduced in the place the output to CLI is being done.
- Repository patterns may be used to deal with data currently stored in arrays (prices, carriers, packages), DB and ORM may be under considerations as well if expanding the solution, taking data from remote, etc.
- OutputManager may be refactored to it's own class (e.g. OutputCli), abstract class (e.g. Output), and interface so attaching other output engines (e.g. OutputFile) would become easier later on.
- Use CarrierFrance object in the OutputItem object instead of the string (code). Same with Package object/string. This would eliminate codes from CLI input flying around in the code.
- Connect PriceFranceEur with Carrier and Package objects information
