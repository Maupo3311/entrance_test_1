## Task
```
Create a CRUD admin panel for managing authors and books.
An author can have many books and a book can have many authors.

Author's fields - full name in separate fields. 
There should not be two authors with the same name.
Book fields: Title, year of publication, ISBN, number of pages.
There should not be two books with the same combination of title 
and ISBN or title and year of publication.
```
## Additional task
```
Provide the ability to attach a book cover image,
in jpeg or png format, which will be stored as a file
```

## Deploying the project
#### Install dependencies and run docker container
```bash
composer install
```
#### Start symfony server
```bash
symfony server:start
```
