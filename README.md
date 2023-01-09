STATUS: recently AHA changed their website so you can no longer check online if a course was activated or not, until that is fixed, aha-elearning-spider is broken

# AHA eLearning Spider
A PHP project to check which AHA eLearning codes are used or unused

![Screen Shot 2021-12-22 at 15 51 09](https://user-images.githubusercontent.com/382183/147153789-88ce0e85-89c8-4923-9c4b-bac027af8709.jpg)

You provide a list of eLearning URLs. This tool will check each target to see if they are already used or not.

## Features

 - Uses PHP and SQLite
 - Works out of the box, zero-click installation
 - Uses `curl` for accessing target web pages
 - Caches downloaded information

We use this project in a production environment with many people accessing it simultaneously for multiple clients.

## Installation

Install the `source/` directory onto your web server. Access that website using a browser.

Alternatively, you can run this program locally using PHP's built-in web server:

    php -S localhost:8000 -t source/

## Contributing

This project uses Semantic Versioning. See database implementation details in [DATABASE_SCHEMA.txt].
