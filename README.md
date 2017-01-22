# Database Systems - Semester A - Project #

Team: Nitzan, Dror, Ariel, Gal

### Questions for Amit ###
1. What to do about polluted / non-real data (since foursquare is user input....)
2. using Stored Procedures
3. using https://github.com/joshcam/PHP-MySQLi-Database-Class for PHP-SQL -- BUT only using it's "rawQuery" method

## **Project Checklist** ##
1. Finish DB Populate
2. Finish Server
3. Finish Client
4. Review Project Requirements
5. Prepare Presentation/Explanation/Document/Whatever according to requirements
6. Test everything on Nova Server

## **DB Populate** ##

#### Process For Populating DB ####

1. Run PHP insert_toTempTable.php with LL params ( latitude start & finish, and longitude start & finish)
2. Run SQL SP - dbPopulate_ins_data_from_temp_table
3. Run PHP insert_places_info.php - it will add additional info needed per place


#### TODO ####
1. finish running on all USA (started at 31, currently at 41 out of 48)
7. run insert_places_info.php for all the places

## **Server** ##

#### TODO ####

1. Start thinking about what Queries
2. develop Queries (probably as stored procedures)
3. develop PHP files to connect DB & Client calls


## **Client** ##

1. places search
2. places list
3. place page
4. add review
5. day search result
6. replacements options menu
7. show replacement details
8. replace mocks with requests