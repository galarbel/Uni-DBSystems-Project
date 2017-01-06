# Database Systems - Semester A - Project #

Team: Nitzan, Dror, Ariel, Gal

## **DB Populate** ##

### Process For Populating DB ###

1. Run PHP insert_toTempTable.php with LL params ( latitude start & finish, and longitude start & finish)
2. Run SQL SP - dbPopulate_ins_data_from_temp_table (it runs the following SPs: dbPopulate_ins_categories, dbPopulate_ins_cities, dbPopulate_ins_states, dbPopulate_ins_places)
3. Run PHP insert_places_info.php - it will add additional info needed per place


### TODO ###
1. finish running on all USA (started at 31, currently at 41 out of 48)
1. finish dbPopulate_ins_categories
2. finish dbPopulate_ins_cities
3. create dbPopulate_ins_states
4. create dbPopulate_ins_places
5. create dbPopulate_ins_data_from_temp_table 
6. make php - insert_places_info.php  (this should include hours, rating, price, REVIEWS! + any other relevant info)
7. run insert_places_info.php for all the places

## **DB Queries** ##
... to do :)


## **Client** ##
...to do :)