# Student-s-Union-Shop-2
A simple php&amp;MySQL integration 
Github repository: github.com/tklocshem/Union-Shop-Web-Store
Example Login Account:

Email: exampleAccount@example.com
Password: User12345!

Main features of this backend :
User registration: the application allows the functionality to sign up new users.

Neatly Structured Code: The code is organized and structured in a clean and maintainable manner.

Calculated product reviews: reviews are calacluated, averaged and displayed, providing users an overall rating for a product.

Product information page: the web application has been modified from assessment 1, removing the sessionStorage HTML5 API to use PHP GET method variables in order to access the page information. This is used when a user clicks an item to reveal more information, the PHP retrieves the data from the database and presents on an item.php page.

Submit/Present reviews: logged in users are presented with an option to leave a review. A review includes a title, description and rating. Reviews are presented for each product item (tbl_reviews) even if the user is not logged in.

Secure passwords: passwords are stored using bcrypt hashing and salting and not raw text.

Sign Up Functionality: the application provides a user-friendly sign-up functionality that allows new users to create an account. The sign-up process includes input validation, password strength checks, and email verification to ensure a secure and smooth registration experience. Users can then log in with their newly created accounts to access the shopping cart and other features.

Responsive Dynamic Content: the website's dynamic content is responsive and adapts to different screen sizes and devices.

Professional Looking and Functional Web Application: The website has a professional look and feel, providing a pleasant user experience.

User Order List View: The application provides a dedicated order list view for logged-in users, allowing them to easily track their past and current orders. This feature includes information about each order, such as order date and items purchased.


Brief of this project:

This website allows the shopper to browse the firm's range of products and services, view product photos or images, and view product pricing information. Customers can shop online using a variety of computers and devices, including desktops, laptops, tablets, and smartphones.
1. Home (index.php)
 
The first page or starting point for navigation that provides visitors a first impression. It is an HTML file with embedded PHP and JavaScript code for the home page of a Student Shop website. It provides a header, footer, UCLan logo, text, video content in the form of an iFrame embedded on YouTube and tags used to link from one page to another. 

The PHP session_start() function is called at the beginning to start a new session or resume an existing one. This allows the website to store user-specific information and maintain the user's logged-in state. The HTML structure is defined with a header, main content area, and footer. The header contains the UCLan logo, a heading, and navigation links. The PHP code inside the header checks if the user is logged in and displays appropriate navigation links accordingly (My Orders and Logout if logged in, Login and Sign Up if not). The main content area displays a heading, a paragraph with a brief introduction, and two embedded YouTube videos. The PHP code in this section connects to a database, retrieves offers from the 'tbl_offers' table, and displays them in a card layout. The footer contains three columns with useful links, contact information, and the location of the Students' Union.

2. Products (products.php)

Provides access to all products (t-shirts, hoodies, and jumpers). Shopper can view product image and color, read product description, and view product pricing information. This code is a PHP script that generates an HTML page for an online Student Shop. The page displays a list of products, which can be filtered by product type (All, T-shirts, Hoodies, Jumpers) or searched by a keyword. Users can also add products to their cart and navigate to other pages of the website, such as Home, Cart, Login, and Sign Up.

The code connects to a MySQL database to fetch product information and dynamically generates the product list based on the selected filters or search term. It also handles user sessions to display different navigation options for logged-in users, such as My Orders and Logout. Using localStorage the page also enables users to add items to the shopping cart.

3. Cart (cart.php)
 
Using localStorage the page provides functionality to view the shopping cart with items added. Shopper can view product image and color, read product description, and view product pricing information. The page also allows the user to remove products or erase the entire cart, return to the products page, or proceed to checkout. The checkout button is conditionally displayed based on the user's login status using PHP. If the user is not logged in, they are prompted to log in before proceeding to checkout.

4. Item (item.php)

The items page can only be accessed by clicking on an item in the products view. Users can view the image in a larger scale, read product description, and view product pricing information, its reviews, and a form to submit a review if the user is logged in. Using localStorage the page also enables users to add items to the shopping cart.

It has a PHP script that starts a session to keep track of the user's login status and other session data. The header includes navigation links, which are displayed differently depending on whether the user is logged in or not. The main content area displays the product details, fetched from the database using the product_id passed in the URL. The average rating and reviews for the product are fetched from the database and displayed on the page. If the user is logged in, a form to submit a review is displayed. Otherwise, a message prompting the user to log in is shown. If the user submits a review, the script checks again if the user is logged in and inserts the review into the database.

5. Sign Up (signup.php)

The page allows users to sign up for an account by providing their full name, email address, password, confirm password and address. The PHP script checks if the email is already registered, if the passwords match, and if the password meets certain requirements (at least one number, one uppercase and lowercase letter, and at least 8 or more characters).  If all the conditions are met, it hashes the password and inserts the user data into the database. If there are any errors, it displays appropriate error messages. The page also uses CSS for styling, Font Awesome library for displaying icons, and JavaScript for form validation and interactivity (e.g., showing/hiding password requirements, toggling password visibility).

6. Login (login.php)

The page has a PHP script for a login page of a student shop website. The page allows users to log in to their accounts to access features like checking out items in their cart, viewing their past orders, and logging out.

The code starts by initializing a session and connecting to the database. It then checks if the login form has been submitted. If the form is submitted, it validates the email and password entered by the user. If the email is not found in the database, it displays an error message. If the password is incorrect, it also displays an error message. If the email and password are correct, the user is logged in, and the page redirects to the cart page.

7. Logout (logout.php)

The page has a PHP script that starts a new session or resumes an existing one using session_start() allowing the script to access session variables. Then it destroys the current session using session_destroy() removing all session data, effectively logging out the user or clearing any stored session information. After then it redirects the user to the home page "index.php" using the header() function. Finally, it terminates the script execution using exit() to ensure no further code is executed after the redirect.

8. Connect to a MySQL database (connect.php)

The page has a PHP script that establishes a connection to a MySQL database.
error_page.php: the page provides a user-friendly 404 error page that informs the user that the requested page could not be found and offers alternative navigation options.

10. Checkout (checkout.php)

The page has a PHP script that handles placing an order for a user in an online shopping cart system by starting a session to access user data across multiple pages, including the "connect.php" file to establish a database connection, and retrieving the cart data from the POST request as a JSON string, which is then converted into a PHP associative array. The script prepares the order data by obtaining the current date and time, user ID from the session, and a comma-separated list of product IDs from the cart data, then constructs an SQL query to insert the order data into the "tbl_orders" table in the database, and if successful, an alert is displayed to the user confirming their order and redirecting them to the "cart.php" page. If there is an error, an error message is displayed with the details. Finally, the script closes the database connection using mysqli_close().
