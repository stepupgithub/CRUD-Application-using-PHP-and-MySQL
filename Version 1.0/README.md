# CRUD-Application-using_PHP

## Highlights
1. This is **Version 1** of my application. It provides the first TWO basic functionalities of a complete website, i.e., **CREATE and READ**. 
1. By using this application a user can create and store information regarding the name of a vehicle, the year in which if was made and it's mileage in our Database.(we can maintain multiple records)
1. This application uses **HTML5**, **Bootstrap and JavaScript** as it's Front End languages and **JQuery** as the JavaScript library.
1. This application uses **PHP 7** as it's Back End language.
1. All interactions follow the **POST-Redirect** pattern where appropriate.
1. It prevents any form of **HTML OR SQL Injection**.

## Links
* CRUD Application [Link!](https://en.wikipedia.org/wiki/Create,_read,_update_and_delete)
* Front End Development [Link!](https://en.wikipedia.org/wiki/Front-end_web_development)
* Back End Development [Link!](https://en.wikipedia.org/wiki/Back-end_web_development)
* SQL Injection [Link!](https://en.wikipedia.org/wiki/Code_injection#SQL_injection)

## Requirements
* Operating system:	Windows | macOS
* MAMP-A complete package of Database management system(MySQL) and Web Server(Apache) **[RECOMMENDED]**
  MAMP [Download link!](https://www.mamp.info/en/downloads/)
  
## Getting Started 
1. To start with, first we will **download** all the files and keep track of the location.
1. Then we Start the **Apache and MySQL server** via **MAMP**. [The screen of MAMP](https://documentation.mamp.info/en/MAMP-Mac/First-Steps/)
1. Then we click on **Open WebStart icon**. This will open MAMP Home page.
1. Now we open the same page on a new tab. Go to **Tools->phpMyAdmin**. This will open MySQL Home page.
1. Now we need a **Database and a table** inside it to run this application. Please follow the steps to do the same:
      1. Click on the **SQL** tab.
      
      1. Paste the commands given below and click on **GO**.  
      _create database misc;_  
      _GRANT ALL ON misc.* TO 'fred'@'localhost' IDENTIFIED BY 'zap';_  
      _GRANT ALL ON misc.* TO 'fred'@'127.0.0.1' IDENTIFIED BY 'zap';_  
      
      1. Paste the commands given below and click on **GO**.  
      _CREATE TABLE autos (_  
      _auto_id INT UNSIGNED NOT NULL AUTO_INCREMENT KEY,_  
      _make VARCHAR(128),_  
      _year INTEGER,_  
      _mileage INTEGER_  
      _);_  
      
 1. We are all set now. **We now go to the previously opened MAMP home page and set the path of our index.php(that we have downloaded) in the url of the page.**  
 
 ### HERE WE GO!
 
**NOTE:**
1. (For Macintosh users only)  
* In the **pdo.php** file, it is stated **port=3306**. Just change it to **port=8889** and save it.  
2. (For both Macintosh and Windows users) 
* Login Username(Email): **anything of your choice**.
* Login Password: **php123**.  

## Thank you for viewing :)

#### This is my first project in development. Hope you'll like this. Please fork this repository to contribute, just to quench our mutual curiosity. Further improvement will be done soon.  

#### If you find any issue with this program, feel free to report this to my GitHub account, or mail it to: _priteshsahani24@gmail.com_ . Your efforts will be appreciated!
      

