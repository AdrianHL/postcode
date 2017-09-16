## UK Postcodes Import and Lookup

**Laravel 5.5** project that imports UK postcodes and offers an API to search postodes (for example by LAT/LONG).

## How it works

### Import Postcodes

Before looking for a postcode you will have to import them. To this effect the project provides a command that download and import the UK postcodes info into the database.

The command can be run as follows:
>php artisan import:postcodes

This command is a one off unless you want to update the records with new downloaded data. Please take into account that it will be slow to run but it will show the progress information while it is running.

*Note*: Before running the command please create an empty file in database/sqlite/database.sqlite. This can be done with
>touch database/sqlite/database.sqlite

### Postcode search

The project offer two endpoints to search postcodes:

**Partial Match**

The endpoint */api/postcodes/{partialSearch}* accepts a string in partialSearch
 
This endpoint gives a paginated result of postcodes partially matching by postcode (pcd).

Example */api/postcodes/BH11A*


**Latitude and Longitude**

The endpoint */api/postcodes/search* accepts the following parameters
- lat - required latitude
- long - required longitude
- dt - optional - distance search

This endpoint gives a paginated result of postcodes found within the distance provided using the latitude and longitude provided.

Example */api/postcodes/search?lat=53.607145&long=-2.309467&dt=2*

## License

The *UK Postcodes Import and Lookup* project is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
